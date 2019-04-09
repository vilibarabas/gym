<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class RedirectAfterRegistrationSubscriber extends PersonalProgramSetsHelper implements EventSubscriberInterface
{
    private $em;
    private $container;
    private $token_storage;

    public function __construct(TokenStorage $token_storage)
    {
        //$this->em = $em;
        //$this->container = $container;
        $this->token_storage = $token_storage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {

    }

    public function onRegistrationSuccess(FormEvent $event) {
        $user = $this->token_storage->getToken()->getUser();

        \kint::dump($user);
        \kint::dump('xxx');
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationSuccess'
        ];
    }
}
