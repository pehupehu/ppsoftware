<?php

namespace App\Repository\Financial;

use App\Entity\Financial\TypeOfAccount;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class TypeOfAccountRepository
 * @package App\Repository\Financial
 */
class TypeOfAccountRepository extends EntityRepository
{
    /**
     * @return Query
     */
    public function getTypesQuery(): Query
    {
        $query = $this->createQueryBuilder('t');
        return $query->getQuery();
    }
}
