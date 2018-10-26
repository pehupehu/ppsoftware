<?php

namespace App\EventListener\Entity;

use App\Entity\Financial\Transaction;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TransactionListener
 * @package App\EventListener\Entity
 */
class TransactionListener
{
    /** @var array */
    private $persist = array();
    /** @var array */
    private $update = array();
    /** @var array */
    private $remove = array();

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var SessionInterface */
    private $session;

    /**
     * UserListener constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Transaction) {
            if ($this->tokenStorage->getToken()) {
                /** @var User $loggedUser */
                $loggedUser = $this->tokenStorage->getToken()->getUser();

                $entityManager = $args->getEntityManager();
                $uow = $entityManager->getUnitOfWork();
                $changes = $uow->getEntityChangeSet($entity);

                dump('prePersit');
                dump($changes);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if (count($this->persist)) {
            dump('postPersist');
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Transaction) {
            if ($this->tokenStorage->getToken()) {
                /** @var User $loggedUser */
                $loggedUser = $this->tokenStorage->getToken()->getUser();

                $entityManager = $args->getEntityManager();
                $uow = $entityManager->getUnitOfWork();
                $changes = $uow->getEntityChangeSet($entity);

                dump('preUpdate');
                dump($changes);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        if (count($this->update)) {
            dump('postUpdate');
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Transaction) {
            if ($this->tokenStorage->getToken()) {
                /** @var User $loggedUser */
                $loggedUser = $this->tokenStorage->getToken()->getUser();

                $entityManager = $args->getEntityManager();
                $uow = $entityManager->getUnitOfWork();
                $changes = $uow->getEntityChangeSet($entity);

                dump('preRemove');
                dump($changes);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        if (count($this->remove)) {
            dump('postRemove');
        }
    }
}