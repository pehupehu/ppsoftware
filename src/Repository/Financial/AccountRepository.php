<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Bank;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AccountRepository
 * @package App\Repository\Financial
 */
class AccountRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    private function getAccountsQueryBuilder(): QueryBuilder
    {
        $query = $this->createQueryBuilder('a');

        $query
            ->addSelect('a')
            ->addSelect('u')
            ->addSelect('t')
            ->addSelect('b');

        $query
            ->innerJoin('a.users', 'u')
            ->innerJoin('a.typeOfAccount', 't')
            ->innerJoin('a.bank', 'b');

        return $query;
    }

    /**
     * @param User $user
     * @return Query
     */
    public function getAccountsQueryForOneUser(User $user): Query
    {
        $query = $this->getAccountsQueryBuilder();

        $query
            ->where('u = :user');

        $query
            ->setParameters([
                ':user' => $user,
            ]);

        return $query->getQuery();
    }

    /**
     * @param User $user
     * @param Bank $bank
     * @return Query
     */
    public function getAccountsQueryForOneUserAndOneBank(User $user, Bank $bank): Query
    {
        $query = $this->getAccountsQueryBuilder();

        $query
            ->where('u = :user')
            ->andWhere('b = :bank');

        $query
            ->setParameters([
                ':user' => $user,
                ':bank' => $bank,
            ]);

        return $query->getQuery();
    }

    /**
     * @param Account $account
     * @param bool $with_creator
     */
    public function getAllowedUsers(Account $account, bool $with_creator = true)
    {
        $allowedUsers = [];

        foreach ($account->getUsers() as $user) {
            if ($with_creator) {
                $allowedUsers[] = $user;
            } elseif (!$account->isCreator($user)) {
                $allowedUsers[] = $user;
            }
        }

        return $allowedUsers;
    }

    /**
     * @param Account $account
     */
    public function getUnallowedUsers(Account $account)
    {
        $unallowedUsers = [];

        /** @var UserRepository $userRepo */
        $userRepo = $this->getEntityManager()->getRepository(User::class);

        $allowedUsers = $this->getAllowedUsers($account);
        /** @var User $user */
        foreach ($userRepo->loadUsers()->execute() as $user) {
            if (!in_array($user, $allowedUsers)) {
                $unallowedUsers[] = $user;
            }
        }

        return $unallowedUsers;
    }
}
