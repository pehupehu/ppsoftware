<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class CategoryRepository
 * @package App\Repository\Financial
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function getCreditQuery(): Query
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->addSelect('c');

        $query
            ->leftJoin('p.childrens', 'c');

        $query
            ->where('p.type = :type')
            ->andWhere('p.parent IS NULL');

        $query
            ->orderBy('p.name, c.name');

        $query
            ->setParameters([
                ':type' => Category::CREDIT,
            ]);

        return $query->getQuery();
    }

    /**
     * @return Query
     */
    public function getDebitQuery(): Query
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->addSelect('c');

        $query
            ->leftJoin('p.childrens', 'c');

        $query
            ->where('p.type = :type')
            ->andWhere('p.parent IS NULL');

        $query
            ->orderBy('p.name, c.name');

        $query
            ->setParameters([
                ':type' => Category::DEBIT,
            ]);

        return $query->getQuery();
    }

    /**
     * @return Category
     */
    public function getTransferTransactionFromCategory(): Category
    {
        /** @var Category $category */
        $category = $this->findOneBy(['type' => Category::TTFC]);
        return $category;
    }

    /**
     * @return Category
     */
    public function getTransferTransactionToCategory(): Category
    {
        /** @var Category $category */
        $category = $this->findOneBy(['type' => Category::TTTC]);
        return $category;
    }
}
