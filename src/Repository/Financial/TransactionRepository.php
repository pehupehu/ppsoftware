<?php

namespace App\Repository\Financial;

use App\Entity\Financial\Account;
use App\Entity\Financial\Transaction;
use Doctrine\Bundle\DoctrineBundle\Twig\DoctrineExtension;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Year;

/**
 * Class TransactionRepository
 * @package App\Repository\Financial
 */
class TransactionRepository extends EntityRepository
{
    /**
     *
     */
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
                ':account' => $account,
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
        
        $results = $query->execute();
        $transactions = new ArrayCollection();
        foreach (array_reverse(array_keys($results)) as $key) {
            $transactions->add($results[$key]);
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

    /**
     * @param Account $account
     * @return array
     */
    public function getTransactionsYearForOneAccount(Account $account): array 
    {
        $query = $this->createQueryBuilder('t');
        $conf = $this->getEntityManager()->getConfiguration();
        $conf->addCustomDatetimeFunction('YEAR', Year::class);

        $query
            ->select('YEAR(t.date) as year');

        $query
            ->innerJoin('t.account', 'a');

        $query
            ->where('a = :account');

        $query
            ->setParameters([
                ':account' => $account,
            ]);

        $query
            ->groupBy('year');

        $years = [];
        foreach ($query->getQuery()->execute([], Query::HYDRATE_ARRAY) as $row) {
            $years[] = $row['year'];
        }

        return $years;
    }

    /**
     * @param Account $account
     * @param int $year
     * @return mixed
     */
    public function getTransactionsForOneAccount(Account $account, int $year)
    {
        $query = $this->createQueryBuilder('t');
        $conf = $this->getEntityManager()->getConfiguration();
        $conf->addCustomDatetimeFunction('YEAR', Year::class);

        $query
            ->addSelect('a, tot, c, p');

        $query
            ->innerJoin('t.account', 'a')
            ->innerJoin('t.typeOfTransaction', 'tot')
            ->innerJoin('t.category', 'c')
            ->leftJoin('c.parent', 'p');

        $query
            ->where('a = :account')
            ->andWhere('YEAR(t.date) = :year');

        $query
            ->setParameters([
                ':account' => $account,
                ':year' => $year,
            ]);

        $query
            ->orderBy('t.date, t.name');

        return $query->getQuery()->execute();
    }
}
