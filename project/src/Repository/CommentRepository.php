<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[]
     */
    public function findByInventory(Inventory $inventory): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.author', 'a')
            ->addSelect('a')
            ->where('c.inventory = :inventory')
            ->setParameter('inventory', $inventory)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get comments since a given ID (for real-time updates)
     * @return Comment[]
     */
    public function findNewComments(Inventory $inventory, int $lastId): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.author', 'a')
            ->addSelect('a')
            ->where('c.inventory = :inventory')
            ->andWhere('c.id > :lastId')
            ->setParameter('inventory', $inventory)
            ->setParameter('lastId', $lastId)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
