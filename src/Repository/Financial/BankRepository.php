<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Bank;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BankRepository
 * @package App\Repository\Financial
 */
class BankRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    private function getBanksQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('b');
    }

    /**
     * @return Query
     */
    public function getBanksQuery(): Query
    {
        $query = $this->getBanksQueryBuilder();
        return $query->getQuery();
    }

    /**
     * @param User $user
     * @return Query
     */
    public function getBanksQueryForOneUser(User $user): Query
    {
        $query = $this->getBanksQueryBuilder();

        $query
            ->addSelect('a')
            ->addSelect('u');

        $query
            ->innerJoin('b.accounts', 'a')
            ->innerJoin('a.users', 'u');

        $query
            ->where('u = :user');

        $query
            ->setParameters([
                ':user' => $user,
            ]);

        return $query->getQuery();
    }

}
