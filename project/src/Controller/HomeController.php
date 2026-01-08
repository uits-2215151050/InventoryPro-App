<?php

namespace App\Controller;

use App\Repository\InventoryRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(InventoryRepository $inventoryRepo, TagRepository $tagRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'latest_inventories' => $inventoryRepo->findLatest(6),
            'popular_inventories' => $inventoryRepo->findMostPopular(5),
            'tag_cloud' => $tagRepo->getTagCloud(30),
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(InventoryRepository $inventoryRepo): Response
    {
        $query = $_GET['q'] ?? '';
        $inventories = $query ? $inventoryRepo->search($query) : [];

        return $this->render('home/search.html.twig', [
            'query' => $query,
            'inventories' => $inventories,
        ]);
    }

    #[Route('/tag/{name}', name: 'app_tag')]
    public function tag(string $name, InventoryRepository $inventoryRepo): Response
    {
        return $this->render('home/search.html.twig', [
            'query' => '#' . $name,
            'inventories' => $inventoryRepo->findByTag($name),
        ]);
    }
}
