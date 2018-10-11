<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Bank;
use Doctrine\ORM\EntityRepository;

/**
 * Class BankRepository
 * @package App\Repository\Financial
 */
class BankRepository extends EntityRepository
{
    public function loadBanks()
    {
        $query = $this->createQueryBuilder('b');
        return $query->getQuery();
    }
}
