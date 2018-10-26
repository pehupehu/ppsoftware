<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Account;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Class TransactionRepository
 * @package App\Repository\Financial
 */
class TransactionRepository extends EntityRepository
{
    const MAX_RESULT = 5;

    /**
     * @param Account $account
     * @param int $max
     * @return Query
     */
    public function getLastTransactionsQueryForOneAccount(Account $account, int $max = self::MAX_RESULT): Query
    {
        $query = $this->createQueryBuilder('t');

        $query
            ->addSelect('a');

        $query
            ->innerJoin('t.account', 'a');

        $query
            ->where('a = :account');

        $query
            ->setParameters([
                ':account' => $account
            ]);

        $query
            ->orderBy('t.date', 'DESC');

        $query
            ->setMaxResults($max);

        return $query->getQuery();
    }

    /**
     * @param Account $account
     * @param int $max
     */
    public function addLastTransactionsToOneAccount(Account $account, int $max = self::MAX_RESULT)
    {
        $query = $this->getLastTransactionsQueryForOneAccount($account, $max);
        
        $transactions = new ArrayCollection();
        foreach ($query->execute() as $transaction) {
            $transactions->add($transaction);
        }

        $account->setLastTransactions($transactions);
    }

    /**
     * @param array $accounts
     * @param int $max
     */
    public function addLastTransactionsToManyAccounts(array $accounts, int $max = self::MAX_RESULT)
    {
        /** @var Account $account */
        foreach ($accounts as $account) {
            $this->addLastTransactionsToOneAccount($account, $max);
        }
    }
}
