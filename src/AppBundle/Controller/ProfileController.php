<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;


class ProfileController extends Controller
{

    /**
     * Creates a new profile entity.
     *
     * @Route("/profile-change", name="profile_change")
     * @Method({"GET", "POST"})
     */
    public function changeAction(Request $request)
    {
        $user = $this->getUser();

        if($user) {
            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('AppBundle:Profile')
            ->findOneBy(['user_id' => $user->getId()]);
            if(!$profile)
                $profile = new Profile();

            $profile->setUserId($user->getId());
        }
        else {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $form = $this->createForm('AppBundle\Form\ProfileType', $profile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();

            return $this->redirectToRoute('profile_show', array('id' => $profile->getId()));
        }

        return $this->render('AppBundle:profile:profile-layout.html.twig', array(
            'profile' => $profile,
            'form' => $form->createView(),
            'page' => 'edit'
        ));
    }

    /**
     * Lists all profile entities.
     *
     * @Route("/profile", name="user_profile_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $profiles = $em->getRepository('AppBundle:Profile')->findAll();

        return $this->render('profile/index.html.twig', array(
            'profiles' => $profiles,
            'page' => 'list'
        ));
    }


    /**
     * Finds and displays a profile entity.
     *
     * @Route("/profile-show", name="profile_show")
     * @Method("GET")
     */
    public function showAction()
    {
        $user = $this->getUser();

        if($user) {
            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('AppBundle:Profile')
                ->findOneBy(['user_id' => $user->getId()]);
            if(!$profile)
                return $this->redirectToRoute('profile_change');
        }
        else {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $deleteForm = $this->createDeleteForm($profile);
        return $this->render('AppBundle:profile:profile-layout.html.twig', array(
            'profile' => $profile,
            'delete_form' => $deleteForm->createView(),
            'page' => 'show'
        ));
    }

    /**
     * Displays a form to edit an existing profile entity.
     *
     * @Route("/{id}/edit", name="profile_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Profile $profile)
    {
        $deleteForm = $this->createDeleteForm($profile);
        $editForm = $this->createForm('AppBundle\Form\ProfileType', $profile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_edit', array('id' => $profile->getId()));
        }

        return $this->render('profile/edit.html.twig', array(
            'profile' => $profile,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a profile entity.
     *
     * @Route("/profile/{id}", name="profile_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Profile $profile)
    {
        $form = $this->createDeleteForm($profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($profile);
            $em->flush();
        }

        return $this->redirectToRoute('profile_index');
    }

    /**
     * Creates a form to delete a profile entity.
     *
     * @param Profile $profile The profile entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Profile $profile)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profile_delete', array('id' => $profile->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
