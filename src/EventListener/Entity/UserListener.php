<?php

namespace App\EventListener\Entity;

use App\Entity\User;
use App\Entity\UserActivity;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserListener
 * @package App\EventListener\Entity
 */
class UserListener
{
    /** @var array */
    private $logs = array();

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
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            if ($this->tokenStorage->getToken()) {
                /** @var User $loggedUser */
                $loggedUser = $this->tokenStorage->getToken()->getUser();

                $entityManager = $args->getEntityManager();
                $uow = $entityManager->getUnitOfWork();
                $changes = $uow->getEntityChangeSet($entity);

                $modified = [];
                foreach ($this->getMonitored() as $key) {
                    if (!isset($changes[$key])) {
                        continue;
                    }

                    // Locale -> change locale session
                    if ($key === 'locale' && $loggedUser === $entity) {
                        $this->session->set('_locale', $entity->getLocale());
                    }

                    // TODO gérer integer/string/etc
                    $modified[] = $key . ' ' . $changes[$key][0] . ' to ' . $changes[$key][1];
                }

                if (!empty($modified)) {
                    $userActivity = new UserActivity();
                    $userActivity->setCreatedAt(new \DateTime());
                    $userActivity->setLoggedUserId($loggedUser->getId());
                    $userActivity->setLoggedUser($loggedUser);
                    $userActivity->setUserId($entity->getId());
                    $userActivity->setUser($entity);
                    $userActivity->setType('USER_UPDATED');
                    $userActivity->setLibelle(implode(', ', $modified));
                    $this->logs[] = $userActivity;
                }
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        if (!empty($this->logs)) {
            try {
                $entityManager = $args->getEntityManager();
                foreach ($this->logs as $log) {
                    $entityManager->persist($log);
                }
                $entityManager->flush();
            } catch (\Exception $e) {
                // TODO
            }
        }
    }

    /**
     * @return array
     */
    private function getMonitored()
    {
        return [
            'firstname',
            'lastname',
            'isActive',
            'role',
            'locale',
        ];
    }
}