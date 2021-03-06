<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\PersonalProgram;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Entity\User;

class LoginListener extends PersonalProgramSetsHelper
{
    protected $em;
    protected $container;

    public function __construct($container, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function onAuthenticationSuccess( InteractiveLoginEvent $event )
    {
        $user = $event->getAuthenticationToken()->getUser();
        $this->setPersonalProgram($user);
    }
}