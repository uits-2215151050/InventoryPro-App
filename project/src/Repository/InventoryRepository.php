<?php

namespace App\Repository;

use App\Entity\Inventory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    /**
     * @return Inventory[]
     */
    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.creator', 'u')
            ->addSelect('u')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Top inventories by item count
     * @return Inventory[]
     */
    public function findMostPopular(int $limit = 5): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.creator', 'u')
            ->leftJoin('i.items', 'it')
            ->addSelect('u')
            ->addSelect('COUNT(it.id) as HIDDEN itemCount')
            ->groupBy('i.id')
            ->orderBy('itemCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Inventory[]
     */
    public function findByCreator(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.creator = :user')
            ->setParameter('user', $user)
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Inventory[]
     */
    public function findWithWriteAccess(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.writers', 'w')
            ->where('w = :user')
            ->orWhere('i.isPublic = true')
            ->setParameter('user', $user)
            ->andWhere('i.creator != :user')
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Full-text search
     * @return Inventory[]
     */
    public function search(string $query, int $limit = 20): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.creator', 'u')
            ->leftJoin('i.tags', 't')
            ->addSelect('u')
            ->where('MATCH(i.title, i.description) AGAINST(:query IN BOOLEAN MODE) > 0')
            ->orWhere('t.name LIKE :likeQuery')
            ->setParameter('query', $query)
            ->setParameter('likeQuery', '%' . $query . '%')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Inventory[]
     */
    public function findByTag(string $tagName, int $limit = 20): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.tags', 't')
            ->leftJoin('i.creator', 'u')
            ->addSelect('u')
            ->where('t.name = :tagName')
            ->setParameter('tagName', $tagName)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
