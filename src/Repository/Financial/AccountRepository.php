<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Account;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class AccountRepository
 * @package App\Repository\Financial
 */
class AccountRepository extends EntityRepository
{
    /**
     * @param User $loggedUser
     * @return \Doctrine\ORM\Query
     */
    public function loadAccounts(User $loggedUser): Query
    {
        $query = $this->createQueryBuilder('a');

        $query
            ->leftJoin('a.users', 'u');

        $query
            ->where('a.creator = :creator OR u.id = :user_id');

        $query
            ->setParameters([
                ':creator' => $loggedUser,
                ':user_id' => $loggedUser->getId(),
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

        // Account's creator
        if ($with_creator) {
            $allowedUsers[] = $account->getCreator();
        }

        // Other users
        /** @var User $user */
        foreach ($account->getUsers() as $user) {
            $allowedUsers[] = $user;
        }

        dump($allowedUsers);

        return $allowedUsers;
    }

    /**
     * @param Account $account
     */
    public function getUnallowedUsers(Account $account)
    {
        $unallowedUsers = [];

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
