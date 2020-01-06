<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Traits\PersonalProgramTrait;
use AppBundle\Entity\PersonalProgram;


class AdminPersonalProgramController extends Controller {

    use PersonalProgramTrait;
    /**
     * @Route("admin/personalProgram/{user}", name="admin_personal_program_by_user")
     * @Method({"GET", "POST"})
     */
    public function personalProgramAction(Request $request, User $user)
    {
        $personalProgram = $this->getPersonalProgramByUserId($user->getId());


        if(!$personalProgram) {
            return $this->redirectToRoute('page_not_found');
        }

        foreach ($personalProgram->getLifeStyle() as $style) {
            //\kint::dump($style);
            if($lifeStyle = $this->getLifestyleSelectedValue($style, 1)) {
                $style->setLifeStyleSelect($lifeStyle);
            }
        }

        return $this->render('AppBundle:admin:personal-planning-admin-all-data.html.twig', array(
            'user' => $user,
            'personalProgram' => $personalProgram
        ));
    }

    /**
     * @Route("admin/personalPrograms", name="admin_personal_program_list")
     * @Method({"GET", "POST"})
     */
    public function personalProgramsAction(Request $request)
    {
        $personalPrograms = $this->getPersonalPrograms();
    
        
        return $this->render('AppBundle:admin:personal-planning-admin-all-programs.html.twig', array(
            'personalPrograms' => $personalPrograms
        ));
    }

    /**
     * @Route("admin/personalProgramDelete/{id}", name="admin_personal_program_delete")
     * @Method({"GET", "POST"})
     */
    public function personalProgramDeleteAction(Request $request, PersonalProgram $personalProgram)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($personalProgram);
        $em->flush();

        return $this->redirectToRoute('admin_personal_program_list');
    }
}
