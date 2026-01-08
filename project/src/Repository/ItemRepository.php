<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @return Item[]
     */
    public function findByInventory(Inventory $inventory): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.inventory = :inventory')
            ->setParameter('inventory', $inventory)
            ->orderBy('i.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Full-text search within an inventory
     * @return Item[]
     */
    public function searchInInventory(Inventory $inventory, string $query): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.inventory = :inventory')
            ->andWhere('MATCH(i.customId, i.customString1, i.customString2, i.customString3) AGAINST(:query IN BOOLEAN MODE) > 0')
            ->setParameter('inventory', $inventory)
            ->setParameter('query', $query)
            ->getQuery()
            ->getResult();
    }

    /**
     * Global search across all items
     * @return Item[]
     */
    public function globalSearch(string $query, int $limit = 50): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.inventory', 'inv')
            ->addSelect('inv')
            ->where('MATCH(i.customId, i.customString1, i.customString2, i.customString3) AGAINST(:query IN BOOLEAN MODE) > 0')
            ->setParameter('query', $query)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get statistics for an inventory
     */
    public function getInventoryStats(Inventory $inventory): array
    {
        $qb = $this->createQueryBuilder('i')
            ->select('COUNT(i.id) as itemCount')
            ->addSelect('AVG(i.customNumber1) as avgNumber1')
            ->addSelect('AVG(i.customNumber2) as avgNumber2')
            ->addSelect('AVG(i.customNumber3) as avgNumber3')
            ->addSelect('MIN(i.customNumber1) as minNumber1')
            ->addSelect('MAX(i.customNumber1) as maxNumber1')
            ->addSelect('MIN(i.customNumber2) as minNumber2')
            ->addSelect('MAX(i.customNumber2) as maxNumber2')
            ->addSelect('MIN(i.customNumber3) as minNumber3')
            ->addSelect('MAX(i.customNumber3) as maxNumber3')
            ->where('i.inventory = :inventory')
            ->setParameter('inventory', $inventory);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Get most frequent string values for a field
     */
    public function getMostFrequentStringValues(Inventory $inventory, string $field, int $limit = 5): array
    {
        $allowedFields = ['customString1', 'customString2', 'customString3'];
        if (!in_array($field, $allowedFields)) {
            return [];
        }

        return $this->createQueryBuilder('i')
            ->select("i.{$field} as value, COUNT(i.id) as count")
            ->where('i.inventory = :inventory')
            ->andWhere("i.{$field} IS NOT NULL")
            ->setParameter('inventory', $inventory)
            ->groupBy("i.{$field}")
            ->orderBy('count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
