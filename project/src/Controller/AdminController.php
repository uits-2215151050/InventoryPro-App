<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_users')]
    public function users(UserRepository $userRepo): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepo->findAllForAdmin(),
        ]);
    }

    #[Route('/user/{id}/block', name: 'admin_user_block', methods: ['POST'])]
    public function blockUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsBlocked(!$user->isBlocked());
        $em->flush();

        $this->addFlash('success', $user->isBlocked() ? 'User blocked.' : 'User unblocked.');
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/user/{id}/toggle-admin', name: 'admin_user_toggle_admin', methods: ['POST'])]
    public function toggleAdmin(User $user, EntityManagerInterface $em): Response
    {
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {
            $roles = array_filter($roles, fn($r) => $r !== 'ROLE_ADMIN');
            $this->addFlash('success', 'Admin role removed.');
        } else {
            $roles[] = 'ROLE_ADMIN';
            $this->addFlash('success', 'Admin role granted.');
        }

        $user->setRoles(array_values($roles));
        $em->flush();

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/user/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($user->getId() === $currentUser->getId()) {
            $this->addFlash('error', 'Cannot delete yourself.');
            return $this->redirectToRoute('admin_users');
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted.');
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/api/users/search', name: 'admin_api_users_search')]
    public function searchUsers(Request $request, UserRepository $userRepo): Response
    {
        $query = $request->query->get('q', '');
        $users = $userRepo->searchByNameOrEmail($query);

        return $this->json(array_map(fn(User $u) => [
            'id' => $u->getId(),
            'name' => $u->getName(),
            'email' => $u->getEmail(),
        ], $users));
    }
}
