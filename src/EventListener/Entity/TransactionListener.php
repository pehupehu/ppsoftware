<?php

namespace App\EventListener\Entity;

use App\Entity\Financial\Transaction;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class TransactionListener
 * @package App\EventListener\Entity
 */
class TransactionListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $transaction = $args->getEntity();

        if ($transaction instanceof Transaction) {
            $current_balance = $transaction->getAccount()->getCurrentBalance();
            if ($transaction->isCredit()) {
                $current_balance += $transaction->getAmount();
            } else {
                $current_balance -= $transaction->getAmount();
            }
            $transaction->getAccount()->setCurrentBalance($current_balance);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $transaction = $args->getEntity();

        if ($transaction instanceof Transaction) {
            $entityManager = $args->getEntityManager();
            $uow = $entityManager->getUnitOfWork();
            $changes = $uow->getEntityChangeSet($transaction);

            if (isset($changes['amount'])) {
                $current_balance = $transaction->getAccount()->getCurrentBalance();
                if ($transaction->isCredit()) {
                    $current_balance += ($changes['amount'][1] - $changes['amount'][0]);
                } else {
                    $current_balance -= ($changes['amount'][1] - $changes['amount'][0]);
                }
                $transaction->getAccount()->setCurrentBalance($current_balance);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $transaction = $args->getEntity();

        if ($transaction instanceof Transaction) {
            $current_balance = $transaction->getAccount()->getCurrentBalance();
            if ($transaction->isCredit()) {
                $current_balance -= $transaction->getAmount();
            } else {
                $current_balance += $transaction->getAmount();
            }
            $transaction->getAccount()->setCurrentBalance($current_balance);
        }
    }
}