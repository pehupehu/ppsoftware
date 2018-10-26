<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Bank;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class BankRepository
 * @package App\Repository\Financial
 */
class BankRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function getBanksQuery(): Query
    {
        $query = $this->createQueryBuilder('b');
        return $query->getQuery();
    }
}
