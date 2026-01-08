<?php

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\Item;
use App\Entity\Like;
use App\Repository\ItemRepository;
use App\Repository\LikeRepository;
use App\Service\CustomIdGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/inventory/{inventoryId}/item', requirements: ['inventoryId' => '\d+'])]
class ItemController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ItemRepository $itemRepo,
        private LikeRepository $likeRepo,
        private CustomIdGenerator $idGenerator
    ) {
    }

    #[Route('/new', name: 'item_new')]
    #[IsGranted('ROLE_USER')]
    public function new(int $inventoryId): Response
    {
        $inventory = $this->em->getRepository(Inventory::class)->find($inventoryId);
        if (!$inventory || !$inventory->hasWriteAccess($this->getUser())) {
            throw $this->createAccessDeniedException();
        }

        $item = new Item();
        $item->setInventory($inventory);
        $item->setCreatedBy($this->getUser());
        $item->setCustomId($this->idGenerator->generate($inventory));

        $this->em->persist($item);
        $this->em->flush();

        return $this->redirectToRoute('item_edit', [
            'inventoryId' => $inventoryId,
            'id' => $item->getId(),
        ]);
    }

    #[Route('/{id}', name: 'item_show', requirements: ['id' => '\d+'])]
    public function show(int $inventoryId, Item $item): Response
    {
        $user = $this->getUser();
        $hasLiked = $user ? $this->likeRepo->hasUserLiked($user, $item) : false;

        return $this->render('item/show.html.twig', [
            'item' => $item,
            'inventory' => $item->getInventory(),
            'has_liked' => $hasLiked,
            'can_edit' => $user && $item->getInventory()->hasWriteAccess($user),
        ]);
    }

    #[Route('/{id}/edit', name: 'item_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function edit(int $inventoryId, Item $item): Response
    {
        if (!$item->getInventory()->hasWriteAccess($this->getUser())) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'inventory' => $item->getInventory(),
        ]);
    }

    #[Route('/{id}/save', name: 'item_save', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function save(int $inventoryId, Item $item, Request $request): JsonResponse
    {
        if (!$item->getInventory()->hasWriteAccess($this->getUser())) {
            return new JsonResponse(['error' => 'Access denied'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $clientVersion = $data['version'] ?? 0;

        // Optimistic locking
        if ($item->getVersion() !== $clientVersion) {
            return new JsonResponse([
                'success' => false,
                'error' => 'version_conflict',
            ], 409);
        }

        // Update custom ID if provided
        if (isset($data['customId']) && $data['customId'] !== $item->getCustomId()) {
            // Validate format based on inventory's custom ID format
            $item->setCustomId($data['customId']);
        }

        // Update custom field values
        $fieldTypes = ['customString', 'customText', 'customNumber', 'customLink', 'customBool'];
        foreach ($fieldTypes as $prefix) {
            for ($i = 1; $i <= 3; $i++) {
                $key = $prefix . $i;
                if (array_key_exists($key, $data)) {
                    $item->setFieldValue($key, $data[$key]);
                }
            }
        }

        $item->setUpdatedAt(new \DateTime());
        $item->incrementVersion();

        try {
            $this->em->flush();
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'unique_custom_id_per_inventory')) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'duplicate_id',
                    'message' => 'This custom ID already exists in this inventory.',
                ], 409);
            }
            throw $e;
        }

        return new JsonResponse([
            'success' => true,
            'version' => $item->getVersion(),
        ]);
    }

    #[Route('/{id}/delete', name: 'item_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(int $inventoryId, Item $item): Response
    {
        if (!$item->getInventory()->hasWriteAccess($this->getUser())) {
            throw $this->createAccessDeniedException();
        }

        $this->em->remove($item);
        $this->em->flush();

        $this->addFlash('success', 'Item deleted.');
        return $this->redirectToRoute('inventory_show', ['id' => $inventoryId]);
    }

    #[Route('/bulk-delete', name: 'item_bulk_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function bulkDelete(int $inventoryId, Request $request): JsonResponse
    {
        $inventory = $this->em->getRepository(Inventory::class)->find($inventoryId);
        if (!$inventory || !$inventory->hasWriteAccess($this->getUser())) {
            return new JsonResponse(['error' => 'Access denied'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        foreach ($ids as $id) {
            $item = $this->itemRepo->find($id);
            if ($item && $item->getInventory()->getId() === $inventoryId) {
                $this->em->remove($item);
            }
        }

        $this->em->flush();

        return new JsonResponse(['success' => true, 'deleted' => count($ids)]);
    }

    #[Route('/{id}/like', name: 'item_like', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function toggleLike(int $inventoryId, Item $item): JsonResponse
    {
        $user = $this->getUser();
        $existingLike = $this->likeRepo->findByUserAndItem($user, $item);

        if ($existingLike) {
            $this->em->remove($existingLike);
            $liked = false;
        } else {
            $like = new Like();
            $like->setUser($user);
            $like->setItem($item);
            $this->em->persist($like);
            $liked = true;
        }

        $this->em->flush();

        return new JsonResponse([
            'liked' => $liked,
            'count' => $item->getLikeCount(),
        ]);
    }
}
