<?php

namespace App\Repository\Financial;

use Doctrine\ORM\EntityRepository;

/**
 * Class TypeOfTransactionRepository
 * @package App\Repository\Financial
 */
class TypeOfTransactionRepository extends EntityRepository
{
    public function loadTypes()
    {
        $query = $this->createQueryBuilder('t');
        return $query->getQuery();
    }
}
