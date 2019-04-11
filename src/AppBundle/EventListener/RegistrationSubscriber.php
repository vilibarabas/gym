<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Entity\PersonalProgram;


const REGISTRATION_SUCCESS = 'fos_user.registration.success';

/**
 * Class UserRegisteredListener
 * @package NovemberFive\UserBundle\Listener
 */

class RegistrationSubscriber extends PersonalProgramSetsHelper
{
    protected $em;
    protected $container;
    protected $user;

    public function __construct(TokenStorage $tokenStorage, $container, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->container = $container;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $_route  = $request->attributes->get('_route');

        if($_route == 'fos_user_registration_confirmed') {
            $this->setPersonalProgram($this->user);
        }
    }
}
