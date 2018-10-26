<?php

namespace App\Repository\Financial;

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
            ->where('p.credit = 1')
            ->andWhere('p.parent IS NULL');

        $query
            ->orderBy('p.name, c.name');

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
            ->where('p.debit = 1')
            ->andWhere('p.parent IS NULL');

        $query
            ->orderBy('p.name, c.name');

        return $query->getQuery();
    }
}
