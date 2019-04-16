<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Evolution;
use AppBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Evolution controller.
 *
 */
class EvolutionController extends Controller
{
    /**
     * Lists all evolution entities.
     *
     * @Route("/evolutions", name="evolution_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $user = $this->getUser();
        $profile = new Profile();

        if($user) {
            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('AppBundle:Profile')
                ->findOneBy(['user_id' => $user->getId()]);
        }

        if(!$profile) {
            return $this->redirectToRoute('profile_change');
        }

        $evolutions = $profile->getEvolutions();

        if($evolutions->isEmpty()) {
            return $this->redirectToRoute('personal_evolutions');
        }

        $evol = $this->generateChartEvolution($evolutions);

        return $this->render('AppBundle:profile:profile-layout.html.twig', array(
            'evolutions' => $evolutions,
            'page' => 'evolutions',
            'evol' => json_encode($evol)
        ));
    }

    private function generateChartEvolution($evolutions) {
        $ev = [];
        $exact = null;
        foreach($evolutions as $evol) {

            if(!$exact) {
                $exact = $this->getOptimalWeight($evol->getProfile());
                if(!$exact) {
                    return [];
                }
            }
            $ev[date_format($evol->getTime(), 'Y-m-d')] = [$exact, $evol->getWeight()];
        }

        return $ev;
    }

    private function getOptimalWeight($profile) {
        if($profile->getHeight() && $profile->getAge() && $profile->getGen())
            return $this->calculByYear($profile->getHeight(), $profile->getAge(), $profile->getGen());

        return false;
    }

    private function calculByYear($height, $year, $sex) {
        \kint::dump($sex);
        switch($sex) {
            case 'M' :
                $ex = 50 + 0.75 * ($height - 150) + ($year - 20)/4;
                break;
            case 'F' :
                $ex = (50 + 0.75 * ($height - 150) + ($year - 20)/4) * 0.9;
                break;
        }

        return $ex;
    }


    /**
     * Creates a new profile entity.
     *
     * @Route("/personal-evolutions", name="personal_evolutions")
     * @Method({"GET", "POST"})
     */
    public function evolutionsAction(Request $request)
    {

        $evolution = new Evolution();
        $user = $this->getUser();
        if($user) {

            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('AppBundle:Profile')
            ->findOneBy(['user_id' => $user->getId()]);

            if(!$profile)
                $profile = new Profile();
            else
                $evolution->setProfile($profile);
        }
        else {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $form = $this->createForm('AppBundle\Form\EvolutionType', $evolution);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($evolution);
            $em->flush();

            return $this->redirectToRoute('evolution_index');
        }

        return $this->render('AppBundle:profile:profile-layout.html.twig', array(
            'evolution' => $evolution,
            'form' => $form->createView(),
            'page' => 'evolutions-edit',
            'delete_form' => null,
        ));
    }

    /**
     * Displays a form to edit an existing evolution entity.
     *
     * @Route("evolution/{id}/edit", name="evolution_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Evolution $evolution)
    {
        $deleteForm = $this->createDeleteForm($evolution);
        $editForm = $this->createForm('AppBundle\Form\EvolutionType', $evolution);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evolution_index');
        }

        return $this->render('AppBundle:profile:profile-layout.html.twig', array(
            'evolution' => $evolution,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'page' => 'evolutions-edit'
        ));
    }

    /**
     * Deletes a evolution entity.
     *
     * @Route("evolution/{id}", name="evolution_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Evolution $evolution)
    {
        $form = $this->createDeleteForm($evolution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evolution);
            $em->flush();
        }

        return $this->redirectToRoute('evolution_index');
    }

    /**
     * Creates a form to delete a evolution entity.
     *
     * @param Evolution $evolution The evolution entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evolution $evolution)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evolution_delete', array('id' => $evolution->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
