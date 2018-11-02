<?php

namespace App\Repository\Financial;

use App\Entity\Financial\TypeOfTransaction;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class TypeOfTransactionRepository
 * @package App\Repository\Financial
 */
class TypeOfTransactionRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function getTypesQuery(): Query
    {
        $query = $this->createQueryBuilder('t');
        return $query->getQuery();
    }

    /**
     * @return TypeOfTransaction
     */
    public function getVirement(): TypeOfTransaction
    {
        /** @var TypeOfTransaction $typeOfTransaction */
        $typeOfTransaction = $this->findOneBy(['surname' => 'VIR']);
        return $typeOfTransaction;
    }
}
