<?php

namespace App\Repository\Financial;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package App\Repository\Financial
 */
class CategoryRepository extends EntityRepository
{
    public function loadCredit()
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->leftJoin('p.childrens', 'c');

        $query
            ->where('p.credit = 1')
            ->andWhere('p.parent IS NULL');
        

        return $query->getQuery();
    }

    public function loadDebit()
    {
        $query = $this->createQueryBuilder('p');

        $query
            ->leftJoin('p.childrens', 'c');

        $query
            ->where('p.debit = 1')
            ->andWhere('p.parent IS NULL');

        return $query->getQuery();
    }
}
