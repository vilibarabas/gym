<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BasePagesController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

//        $user = $this->getUser();
//
//        if(!$user) {
//            return $this->redirectToRoute('fos_user_security_login');
//        }
        // replace this example code with whatever you need
        return $this->render('AppBundle:base:home.html.twig', [

        ]);
    }

    /**
     * @Route("/calculate", name="calculate_page")
     * @Method({"GET", "POST"})
     */
    public function calculateAction(Request $request)
    {
        $form = $this->createForm('AppBundle\Form\CalculateType');

        $form->add('weight', NumberType::class, [
            'attr'=> ['autocomplete'=> 'off']
        ])
            ->add('height', NumberType::class)
            ->add('age', NumberType::class);

        $this->setDefaultValues($form);


        if ($request->isMethod('post')) {
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('AppBundle:base:calculate.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function setDefaultValues($form) {
        $user = $this->getUser();

        if($user) {
            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('AppBundle:Profile')
                ->findOneBy(['user_id' => $user->getId()]);
            if($profile) {
                //\Kint::dump($profile);
                $form->get('age')->setData($profile->getAge());
                $form->get('height')->setData($profile->getHeight());
                $evolutions = $profile->getEvolutions();

                if(!empty($evolutions)) {
                    $form->get('weight')->setData($evolutions->last()->getWeight());
                }
            }

        }
    }
}
