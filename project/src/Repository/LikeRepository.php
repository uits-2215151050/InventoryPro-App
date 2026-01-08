<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findByUserAndItem(User $user, Item $item): ?Like
    {
        return $this->findOneBy(['user' => $user, 'item' => $item]);
    }

    public function hasUserLiked(User $user, Item $item): bool
    {
        return $this->findByUserAndItem($user, $item) !== null;
    }

    public function countByItem(Item $item): int
    {
        return $this->count(['item' => $item]);
    }
}
