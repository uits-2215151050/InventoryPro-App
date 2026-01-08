<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(InventoryRepository $inventoryRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'my_inventories' => $inventoryRepo->findByCreator($user),
            'shared_inventories' => $inventoryRepo->findWithWriteAccess($user),
        ]);
    }

    #[Route('/api/user/theme', name: 'api_user_theme', methods: ['POST'])]
    public function updateTheme(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $theme = $data['theme'] ?? 'light';

        /** @var User|null $user */
        $user = $this->getUser();
        if ($user) {
            $user->setTheme($theme);
            $em->flush();
        }

        return new JsonResponse(['success' => true]);
    }

    #[Route('/api/user/locale', name: 'api_user_locale', methods: ['POST'])]
    public function updateLocale(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $locale = $data['locale'] ?? 'en';

        /** @var User|null $user */
        $user = $this->getUser();
        if ($user) {
            $user->setLocale($locale);
            $em->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}
