<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Traits\PersonalProgramTrait;
use AppBundle\Entity\PersonalProgram;



class UserAdminController extends Controller {
	/**
     * @Route("/dashboard", name="admin-dashboard")
     */

	public function indexAction(Request $request)
    {

       $user = $this->getUser();
 
//
//        if(!$user) {
//            return $this->redirectToRoute('fos_user_security_login');
//        }
        // replace this example code with whatever you need
        return $this->render('AppBundle:admin:admin-dashboard.html.twig', [

        ]);
    }
}