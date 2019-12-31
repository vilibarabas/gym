<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Evolution;
use AppBundle\Entity\Profile;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BasePagesController extends Controller
{
    private $default_mas_inmultire = 2.2;
    private $default_mas_variable = 15;
    private $default_k_cal_differences_deficit = 500; // 300 - 700 kcal in functie de intensitate 3500 kcal = 0,5 kg - grasime
    private $default_k_cal_differences_surplus = 300; // nivel optim de crestere in masa
    private $protein_balance = 2;
    // 0 miscarte / day (0,9 - 1,8) nivel ridicat de miscare
    // poate sa ajunga pana la 2.0-2.5 gr/kg corp daca nivelul de grasime corporala ajunge sub 10%
    private $grasimi_balance = 0.3; // [0.3 - 0.4]


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//
//        if(!$user) {
//            return $this->redirectToRoute('fos_user_security_login');
//        }
        // replace this example code with whatever you need
        return $this->render('AppBundle:base:home.html.twig', [

        ]);
    }

    /**
     * @Route("/error", name="page_not_found")
     */
    public function error(Request $request)
    {

//        $user = $this->getUser();
//
//        if(!$user) {
//            return $this->redirectToRoute('fos_user_security_login');
//        }
        // replace this example code with whatever you need
        return $this->render('AppBundle:base:page_not_found.html.twig', [

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
            ->add('want_weight', NumberType::class)
            ->add('height', NumberType::class)
            ->add('age', NumberType::class)
            ->add('gen', ChoiceType::class, array(
                'choices'  => array(
                    'Masculin' => 'M',
                    'Feminin' => 'F',
                )));

        $this->setDefaultValues($form);


        if ($request->isMethod('post')) {
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $formData = $form->getData();
            if($user) {
                $this->setProfile($user, $formData);
            }

            $result = $this->calculData($formData, $user);


            $obiectiv = 'mentinere';

            if($formData['weight'] != $formData['want_weight'])
                $obiectiv = $formData['weight'] < $formData['want_weight'] ? 'ingrasare' : 'slabire';

            return $this->render('AppBundle:base:calculate-form-result.html.twig', array(
                'result' => $result,
                'obiectiv' => $obiectiv
            ));
        }

        return $this->render('AppBundle:base:calculate-form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function setProfile($user, $formData) {
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('AppBundle:Profile')
            ->findOneBy(['user_id' => $user->getId()]);

        if(!$profile) {
            $profile = new Profile();
        }

        $profile->setUserId($user->getId());
        $profile->setHeight($formData['height']);
        $profile->setGen($formData['gen']);
        $em->persist($profile);
        $em->flush();
        $evolution = new Evolution();
        $evolution->setWeight($formData['weight']);
        $evolution->setTime(new \DateTime());
        $evolution->setProfile($profile);
        $em->persist($evolution);
        $em->flush();
    }

    private function calculData($formData, $user) {
        //caloriile necesare pentru corpul tau

        $this->setObiectiveIntensiti($formData, $user);

        $Kcal = $formData['weight'] * $this->default_mas_inmultire * $this->default_mas_variable;

        if($formData['weight'] < $formData['want_weight']) {
            $obiectiveKcal = $Kcal + $this->default_k_cal_differences_surplus;
        }
        elseif($formData['weight'] > $formData['want_weight']){
            $obiectiveKcal = $Kcal - $this->default_k_cal_differences_deficit;
        }
        else {
            $obiectiveKcal = $Kcal;
        }

        /*
         * proteina 4kcal / gr
         * carbo    4kcal / gr
         * grasimi  9kcal / gr
         *
         */

        $proteine = $this->protein_balance * $formData['weight'];
        $grasimi = $this->grasimi_balance * $this->default_mas_inmultire * $formData['weight'];
        $carbo = ($obiectiveKcal - ( 4 * $proteine + 9 * $grasimi)) / 4;

        return [
            'kcal_mentinere' => $Kcal,
            'kcal_obiectiv' => $obiectiveKcal,
            'proteina' => $proteine,
            'grasimi' => $grasimi,
            'carbo' => $carbo,
            'total' => $proteine + $carbo + $grasimi
        ];
    }

    private function setObiectiveIntensiti($formData, $user) {
        //set by lifestyle;
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
                $form->get('gen')->setData($profile->getGen());
                $form->get('height')->setData($profile->getHeight());
                $evolutions = $profile->getEvolutions();
                
                if($evolutions->first()) {
                    $form->get('weight')->setData($evolutions->last()->getWeight());
                }
            }

        }
    }
}
