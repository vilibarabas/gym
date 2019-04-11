<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Traits\PersonalProgramTrait;

class AdminPersonalProgramController extends Controller {

    use PersonalProgramTrait;
    /**
     * @Route("admin/personalProgram/{user}", name="admin_personal_program_by_user")
     * @Method({"GET", "POST"})
     */
    public function personalProgramAction(Request $request, User $user)
    {
        $personalProgram = $this->getPersonalProgramByUserId($user->getId());

        foreach ($personalProgram->getLifeStyle() as $style) {
            if($lifeStyle = $this->getLifestyleSelectedValue($style, 1)) {
                $style->setLifeStyleSelect($lifeStyle);
            }
        }

        return $this->render('AppBundle:admin:personal-planning-admin-all-data.html.twig', array(
            'user' => $user,
            'personalProgram' => $personalProgram
        ));
    }
}
