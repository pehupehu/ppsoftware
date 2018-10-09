<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;

class SwitchUserSubscriber implements EventSubscriberInterface
{
    public function onSwitchUser(SwitchUserEvent $event)
    {
        $request = $event->getRequest();

        if ($request->hasSession() && ($session = $request->getSession())) {
            /** @var User $user */
            $user = $event->getTargetUser();
            $session->set('_locale', $user->getLocale());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // constant for security.switch_user
            SecurityEvents::SWITCH_USER => 'onSwitchUser',
        );
    }
}
