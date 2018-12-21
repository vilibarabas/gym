<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FoodCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Foodcategory controller.
 *
 * @Route("admin/foodcategory")
 */
class FoodCategoryController extends Controller
{
    /**
     * Lists all foodCategory entities.
     *
     * @Route("/", name="admin_foodcategory_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $foodCategories = $em->getRepository('AppBundle:FoodCategory')->findAll();

        return $this->render('foodcategory/index.html.twig', array(
            'foodCategories' => $foodCategories,
        ));
    }

    /**
     * Creates a new foodCategory entity.
     *
     * @Route("/new", name="admin_foodcategory_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $foodCategory = new Foodcategory();
        $form = $this->createForm('AppBundle\Form\FoodCategoryType', $foodCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($foodCategory);
            $em->flush();

            return $this->redirectToRoute('admin_foodcategory_show', array('id' => $foodCategory->getId()));
        }

        return $this->render('foodcategory/new.html.twig', array(
            'foodCategory' => $foodCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a foodCategory entity.
     *
     * @Route("/{id}", name="admin_foodcategory_show")
     * @Method("GET")
     */
    public function showAction(FoodCategory $foodCategory)
    {
        $deleteForm = $this->createDeleteForm($foodCategory);

        return $this->render('foodcategory/show.html.twig', array(
            'foodCategory' => $foodCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing foodCategory entity.
     *
     * @Route("/{id}/edit", name="admin_foodcategory_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FoodCategory $foodCategory)
    {
        $deleteForm = $this->createDeleteForm($foodCategory);
        $editForm = $this->createForm('AppBundle\Form\FoodCategoryType', $foodCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_foodcategory_edit', array('id' => $foodCategory->getId()));
        }

        return $this->render('foodcategory/edit.html.twig', array(
            'foodCategory' => $foodCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a foodCategory entity.
     *
     * @Route("/{id}", name="admin_foodcategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FoodCategory $foodCategory)
    {
        $form = $this->createDeleteForm($foodCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($foodCategory);
            $em->flush();
        }

        return $this->redirectToRoute('admin_foodcategory_index');
    }

    /**
     * Creates a form to delete a foodCategory entity.
     *
     * @param FoodCategory $foodCategory The foodCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FoodCategory $foodCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_foodcategory_delete', array('id' => $foodCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
