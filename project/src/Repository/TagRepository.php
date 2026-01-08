<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Autocomplete search for tags
     * @return Tag[]
     */
    public function searchByPrefix(string $prefix, int $limit = 10): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.name LIKE :prefix')
            ->setParameter('prefix', $prefix . '%')
            ->setMaxResults($limit)
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get tags with inventory count for tag cloud
     * @return array<array{tag: Tag, count: int}>
     */
    public function getTagCloud(int $limit = 50): array
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'COUNT(i.id) as inventoryCount')
            ->leftJoin('t.inventories', 'i')
            ->groupBy('t.id')
            ->orderBy('inventoryCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
