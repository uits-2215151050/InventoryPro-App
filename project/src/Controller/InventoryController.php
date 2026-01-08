<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Inventory;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\InventoryRepository;
use App\Repository\ItemRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Service\CustomIdGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/inventory')]
class InventoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private InventoryRepository $inventoryRepo,
        private CategoryRepository $categoryRepo,
        private TagRepository $tagRepo,
        private ItemRepository $itemRepo,
        private CommentRepository $commentRepo,
        private UserRepository $userRepo
    ) {
    }

    #[Route('/new', name: 'inventory_new')]
    #[IsGranted('ROLE_USER')]
    public function new(): Response
    {
        $inventory = new Inventory();
        $inventory->setTitle('New Inventory');
        $inventory->setCreator($this->getUser());
        $this->em->persist($inventory);
        $this->em->flush();

        return $this->redirectToRoute('inventory_edit', ['id' => $inventory->getId()]);
    }

    #[Route('/{id}', name: 'inventory_show', requirements: ['id' => '\d+'])]
    public function show(Inventory $inventory): Response
    {
        return $this->render('inventory/show.html.twig', [
            'inventory' => $inventory,
            'items' => $this->itemRepo->findByInventory($inventory),
            'comments' => $this->commentRepo->findByInventory($inventory),
            'stats' => $this->itemRepo->getInventoryStats($inventory),
            'can_edit' => $this->canEdit($inventory),
            'can_write' => $this->canWrite($inventory),
        ]);
    }

    #[Route('/{id}/edit', name: 'inventory_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Inventory $inventory): Response
    {
        $this->denyAccessUnlessGranted('edit', $inventory);

        return $this->render('inventory/edit.html.twig', [
            'inventory' => $inventory,
            'categories' => $this->categoryRepo->findAllOrdered(),
            'can_edit' => true,
        ]);
    }

    #[Route('/{id}/autosave', name: 'inventory_autosave', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function autosave(Inventory $inventory, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $inventory);

        $data = json_decode($request->getContent(), true);
        $clientVersion = $data['version'] ?? 0;

        // Optimistic locking check
        if ($inventory->getVersion() !== $clientVersion) {
            return new JsonResponse([
                'success' => false,
                'error' => 'version_conflict',
                'message' => 'This inventory was modified by another user.',
            ], 409);
        }

        // Update basic fields
        if (isset($data['title']))
            $inventory->setTitle($data['title']);
        if (isset($data['description']))
            $inventory->setDescription($data['description']);
        if (isset($data['isPublic']))
            $inventory->setIsPublic($data['isPublic']);
        if (isset($data['imageUrl']))
            $inventory->setImageUrl($data['imageUrl']);
        if (isset($data['categoryId'])) {
            $category = $this->categoryRepo->find($data['categoryId']);
            $inventory->setCategory($category);
        }

        // Update custom ID format
        if (isset($data['customIdFormat'])) {
            $inventory->setCustomIdFormat($data['customIdFormat']);
        }

        // Update custom fields configuration
        $this->updateCustomFields($inventory, $data);

        // Update tags
        if (isset($data['tags'])) {
            $inventory->getTags()->clear();
            foreach ($data['tags'] as $tagName) {
                $tag = $this->tagRepo->findOneBy(['name' => $tagName]) ?? (new Tag())->setName($tagName);
                if (!$tag->getId())
                    $this->em->persist($tag);
                $inventory->addTag($tag);
            }
        }

        $inventory->setUpdatedAt(new \DateTime());
        $inventory->incrementVersion();
        $this->em->flush();

        return new JsonResponse([
            'success' => true,
            'version' => $inventory->getVersion(),
        ]);
    }

    #[Route('/{id}/delete', name: 'inventory_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Inventory $inventory): Response
    {
        $this->denyAccessUnlessGranted('edit', $inventory);

        $this->em->remove($inventory);
        $this->em->flush();

        $this->addFlash('success', 'Inventory deleted.');
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/{id}/access', name: 'inventory_access', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateAccess(Inventory $inventory, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('edit', $inventory);

        $data = json_decode($request->getContent(), true);
        $action = $data['action'] ?? '';
        $userId = $data['userId'] ?? null;

        if ($action === 'add' && $userId) {
            $user = $this->userRepo->find($userId);
            if ($user)
                $inventory->addWriter($user);
        } elseif ($action === 'remove' && $userId) {
            $user = $this->userRepo->find($userId);
            if ($user)
                $inventory->removeWriter($user);
        }

        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}/comment', name: 'inventory_comment', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addComment(Inventory $inventory, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $content = trim($data['content'] ?? '');

        if (empty($content)) {
            return new JsonResponse(['error' => 'Empty comment'], 400);
        }

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setInventory($inventory);
        $comment->setAuthor($this->getUser());

        $this->em->persist($comment);
        $this->em->flush();

        return new JsonResponse([
            'id' => $comment->getId(),
            'content' => $comment->getContent(),
            'author' => $comment->getAuthor()->getName() ?? $comment->getAuthor()->getEmail(),
            'authorId' => $comment->getAuthor()->getId(),
            'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/{id}/comments', name: 'inventory_comments_poll')]
    public function pollComments(Inventory $inventory, Request $request): JsonResponse
    {
        $lastId = (int) $request->query->get('lastId', 0);
        $comments = $this->commentRepo->findNewComments($inventory, $lastId);

        return new JsonResponse(array_map(fn(Comment $c) => [
            'id' => $c->getId(),
            'content' => $c->getContent(),
            'author' => $c->getAuthor()->getName() ?? $c->getAuthor()->getEmail(),
            'authorId' => $c->getAuthor()->getId(),
            'createdAt' => $c->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $comments));
    }

    #[Route('/api/tags/search', name: 'api_tags_search')]
    public function searchTags(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        $tags = $this->tagRepo->searchByPrefix($query);

        return new JsonResponse(array_map(fn(Tag $t) => $t->getName(), $tags));
    }

    private function updateCustomFields(Inventory $inventory, array $data): void
    {
        $types = ['String', 'Text', 'Number', 'Link', 'Bool'];
        foreach ($types as $type) {
            for ($i = 1; $i <= 3; $i++) {
                $prefix = 'custom' . $type . $i;
                if (isset($data[$prefix . 'State'])) {
                    $inventory->{'set' . ucfirst($prefix) . 'State'}($data[$prefix . 'State']);
                }
                if (isset($data[$prefix . 'Name'])) {
                    $inventory->{'set' . ucfirst($prefix) . 'Name'}($data[$prefix . 'Name']);
                }
                if (isset($data[$prefix . 'Description'])) {
                    $inventory->{'set' . ucfirst($prefix) . 'Description'}($data[$prefix . 'Description']);
                }
                if (isset($data[$prefix . 'ShowInTable'])) {
                    $inventory->{'set' . ucfirst($prefix) . 'ShowInTable'}($data[$prefix . 'ShowInTable']);
                }
                if (isset($data[$prefix . 'Order'])) {
                    $inventory->{'set' . ucfirst($prefix) . 'Order'}($data[$prefix . 'Order']);
                }
            }
        }
    }

    private function canEdit(Inventory $inventory): bool
    {
        $user = $this->getUser();
        if (!$user)
            return false;
        if ($user->isAdmin())
            return true;
        return $inventory->getCreator() === $user;
    }

    private function canWrite(Inventory $inventory): bool
    {
        $user = $this->getUser();
        if (!$user)
            return false;
        return $inventory->hasWriteAccess($user);
    }
}
