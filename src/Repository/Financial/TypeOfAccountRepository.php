<?php

namespace App\Repository\Financial;

use App\Entity\Financial\TypeOfAccount;
use Doctrine\ORM\EntityRepository;

/**
 * Class TypeOfAccountRepository
 * @package App\Repository\Financial
 */
class TypeOfAccountRepository extends EntityRepository
{
    public function loadTypes()
    {
        $query = $this->createQueryBuilder('t');
        return $query->getQuery();
    }
}
