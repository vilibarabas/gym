<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Food;
use AppBundle\Entity\FoodCategory;
use AppBundle\Entity\LifeStyle;
use AppBundle\Entity\User;
use AppBundle\Entity\PersonalProgram;
use AppBundle\Traits\PersonalProgramTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ChangeLifeStyleController extends Controller
{
    use PersonalProgramTrait;

    private $life_style_form_key = 'life_Style';


    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @Route("/finish/{personalProgram}", name="finish_personal_form_questions")
     * @Method({"GET", "POST"})
     */
    public function finish_personal_form_questions(Request $request, PersonalProgram $personalProgram = null)
    {
        if(!$personalProgram) {
            return $this->redirectToRoute('page_not_found');
        }

        $form = $this->createForm('AppBundle\Form\CalculateType');

        $user = $this->getUser();

        $this->get('session')->set('personalProgramId', $personalProgram->getId());
        //print_r($this->get('session')->get('personalProgramId'));die;
        //$this->removePersonalProgram($personalProgram);

        return $this->render('AppBundle:base:personal-planning-finish-form.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'personalProgram' => $personalProgram
        ));
    }


    private function removePersonalProgram($personalProgram) {
        $entityManager = $this->getDoctrine()->getManager();

        foreach($personalProgram->getLifeStyle() as $style) {
            $entityManager->remove($style);
        }

        $entityManager->remove($personalProgram);
        $entityManager->flush();
    }
    /**
     * @Route("/foodPreferences/{foodCategory}/{personalProgram}/{end}", name="food_preferences")
     * @Method({"GET", "POST"})
     */
    public function test(Request $request, FoodCategory $foodCategory, PersonalProgram $personalProgram = null, $end = false)
    {
        $user = $this->getUser();
        $nextCategoryId = $this->getNextCagegory($foodCategory->getId(), $end);
        if (!$personalProgram) {
            $personalProgram = new PersonalProgram();
        }

        if ($user) {
            $personalProgram->setUserRefId($user);
            if ($existing_program = $this->getPersonalProgramByUserId($user->getId())) {
                $personalProgram = $existing_program;
            }
        }

        $form = $this->createForm('AppBundle\Form\FoodPreferencesType');

        foreach ($foodCategory->getFoods() as $food) {
            $form->add(str_replace(" ", "_", $food->getName()), ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
            ]);
        }

        if ($end) {
            $form->add(
                'finish',
                HiddenType::class);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->setLifeStyles($personalProgram, $data);

            if (array_key_exists('finish', $data)) {
                return $this->redirectToRoute('finish_personal_form_questions', ['personalProgram' => $personalProgram->getId()]);
            }

            return $this->redirectToRoute('food_preferences', ['foodCategory' => $nextCategoryId, 'end' => $end, 'personalProgram' => $personalProgram->getId()]);
        }

        $back = '';

        if ($beforefoodCategory = $this->getBeforeFoodCategory($foodCategory->getId())) {
            $back = $this->generateUrl('food_preferences',
                array(
                    'foodCategory' => $beforefoodCategory,
                    'personalProgram' => $personalProgram->getId()
                )
            );
        } else {

            $styles = array_keys($this->choices);


            $back = $this->generateUrl('life_style',
                array(
                    'style' => end($styles),
                    'personalProgram' => $personalProgram->getId()
                )
            );
        }


        return $this->render('AppBundle:base:lifestyleform.html.twig', array(
            'form' => $form->createView(),
            'end' => $end,
            'title' => $foodCategory->getName(),
            'back' => $back,
            'progress_bar_data' => $this->getProgressBarDatas(null, $foodCategory->getId()),
            'progress_settings' => $this->getProgressBarSettings()
        ));
    }

    private function getProgressBarDatas($style, $foodCagetory) {

        $datas = [];
        $i = 1;
        foreach($this->choices as $key => $choice) {
            if($style && $style == $key) {
                $datas[] = "@". $i;
            }
            else {
                $datas[] = (string) $i;
            }
            $i++;
        }

        $foodCategories = $this->getDoctrine()
            ->getRepository(FoodCategory::class)
            ->findAll();
        foreach($foodCategories as $category) {
            if($foodCagetory && $foodCagetory == $category->getId()) {
                $datas[] = "@". $i;
            }
            else {
                $datas[] = (string) $i;
            }
            $i++;
        }
        return json_encode($datas);
    }

    /**
     * @Route("/lifeStyle/{style}/{personalProgram}", name="life_style")
     * @Method({"GET", "POST"})
     */
    public function lifeStyle(Request $request, $style, PersonalProgram $personalProgram = null)
    {
        $user = $this->getUser();
//
//        if($user && $program = $this->getPersonalProgramByUserId($user->getId()) {
//
//        }

        if(!isset($this->choices[$style])) {
            return $this->redirectToRoute('page_not_found');
        }

        $form = $this->createForm('AppBundle\Form\LifeStyleType');

        $form->add($this->life_style_form_key, ChoiceType::class, array(
            'choices' => $this->choices[$style]['data'],
            'choices_as_values' => true,
            'multiple' =>false,
            'expanded'=>true,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$personalProgram)
                $personalProgram = new PersonalProgram();

            if($user) {
                $personalProgram->setUserRefId($user);
                if($existing_program = $this->getPersonalProgramByUserId($user->getId())) {
                    $personalProgram = $existing_program;
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($personalProgram);
            $em->flush();

            $foodCategory = $program = $this->getDoctrine()
                ->getRepository(FoodCategory::class)
                ->findBy(
                    array(),
                    array('sort' => 'ASC')
                );

            $data = $form->getData();

            $this->saveLifeStyle($personalProgram, $data, $style);
//            \Kint::dump($foodCategory);

            if($nextStyle = $this->getNextLifeStyle($style)) {
                return $this->redirectToRoute('life_style', ['style' => $nextStyle, 'personalProgram' => $personalProgram->getId()]);
            }

            if(isset($foodCategory[0])) {
                return $this->redirectToRoute('food_preferences', ['foodCategory' => $foodCategory[0]->getId(), 'personalProgram' => $personalProgram->getId()]);
            }
        }

        $back = $this->getBeforelifeStyle($style);

        if($back) {
            $back = $this->generateUrl('life_style',
                array(
                    'style' => $back,
                    'personalProgram' => $personalProgram->getId(),

                )
            );
        }

        return $this->render('AppBundle:base:lifestyleform.html.twig', array(
            'form' => $form->createView(),
            'end' => false,
            'title' => $this->choices[$style]['title'],
            'back' => $back,
            'progress_bar_data' => $this->getProgressBarDatas($style, null),
            'progress_settings' => $this->getProgressBarSettings()
        ));
    }

    private function getProgressBarSettings() {
        return json_encode([
            'element' => [
                'width' => '13%'
            ]
        ]);
    }

    private function getBeforelifeStyle($style) {
        $back = '';

        $styles = array_keys($this->choices);

        foreach($styles as $key => $stl) {
            if($stl == $style && $key > 0) {
                $back = $styles[$key - 1];
            }
        }

        return $back;
    }

    private function setLifeStyles($personalProgram, $formData)
    {
        $lifeStyle = new LifeStyle();
        $added = false;

        foreach ($formData as $key => $data) {
            if($data) {
                $food = $this->getFoodByName($key);
                if($food) {
                    $lifeStyle->addFood($food);
                    $added = true;
                }
            }
        }

        if($added) {
            $em = $this->getDoctrine()->getManager();
            if(!$personalProgram->getId()) {
                $em->persist($personalProgram);
                $em->flush();
            }
            $lifeStyle->setPersonalProgram($personalProgram);

            $em->persist($lifeStyle);
            $em->flush();
        }
    }

    private function getFoodByName($name) {
        $food = $this->getDoctrine()
            ->getRepository(Food::class)
            ->findByName($name);

        if(isset($food[0])) {
            return $food[0];
        }

        return null;
    }

    private function getBeforeFoodCategory($currentCategoryId) {
        $foodCategory = $program = $this->getDoctrine()
            ->getRepository(FoodCategory::class)
            ->findBy(
                array(),
                array('sort' => 'ASC')
            );

        $beforeCategory = null;
        $break = false;

        foreach($foodCategory as $key => $category) {
            if($break) {
                break;
            }

            if($currentCategoryId == $category->getId() && $beforeCategory && $beforeCategory != $currentCategoryId) {
                $break = true;
            }
            else {
                $beforeCategory = $category->getId();
            }
        }

        return $break ? $beforeCategory : null;
    }

    private function getNextCagegory($currentCategoryId, &$end = false) {
        $foodCategory = $program = $this->getDoctrine()
            ->getRepository(FoodCategory::class)
            ->findBy(
                array(),
                array('sort' => 'ASC')
            );

        $nextCategory = null;
        $break = false;

        foreach($foodCategory as $key => $category) {

            if($break) {
                $nextCategory = $category->getId();
                break;
            }
            if($currentCategoryId == $category->getId()) {

                if($key == count($foodCategory) - 1) {
                    $end = true;
                }
                $break = true;
            }
        }
        return $nextCategory;
    }



    private function getNextLifeStyle($current_style) {
        $lifestyles = array_keys($this->choices);
        $flag = 0;

        foreach($lifestyles as $style) {
            if($flag) {
                return $style;
            }
            if($style == $current_style) {
                $flag = 1;
            }
        }
        return null;
    }

    private function saveLifeStyle($personalProgram, $formData, $style) {
        $lifeStyle = new LifeStyle();
        $lifeStyle->setPersonalProgram($personalProgram);
        if(isset($formData[$this->life_style_form_key])) {
            $data = [];
            $data[$style] = $formData[$this->life_style_form_key];
            $lifeStyle->setLifeStyleSelect(json_encode($data));
            $em = $this->getDoctrine()->getManager();
            $em->persist($lifeStyle);
            $em->flush();
        }
    }

//    private function getLifeStyle($id, $lifestyle) {
//        $em = $this->getDoctrine()->getEntityManager();
//        $query = $em->createQueryBuilder()
//            ->select('l.id, l.life_style_select')
//            ->from('AppBundle:LifeStyle', 'l')
//            ->where('l.life_style_select LIKE "%'. $lifestyle.'%"')
//            ->andWhere('l.life_style_select LIKE "%'. $lifestyle.'%"');
//
////        $lifestyle = $this->getDoctrine()
////            ->getRepository(LifeStyle::class)
////            ->findByPersonalProgram($id);
////
////        return $lifestyle;
//
//        $criteria = new \Doctrine\Common\Collections\Criteria();
//        $criteria
//            ->orWhere($criteria->expr()->contains('life_style_select', $lifestyle));
////            ->orWhere($criteria->expr()->contains('domains', 'b'));
//
//        $groups = $em
//            ->getRepository(LifeStyle::class)
//            ->matching($criteria);
//        \Kint::dump($groups);
//    }
}
