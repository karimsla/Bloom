<?php

namespace DoraBundle\Controller;

use DoraBundle\Entity\Staff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Staff controller.
 *
 * @Route("staff")
 */
class StaffController extends Controller
{
    /**
     * Lists all staff entities.
     *
     * @Route("/", name="staff_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $staff = $em->getRepository('DoraBundle:Staff')->findAll();

        return $this->render('@Dora/staff/index.html.twig', array(
            'staff' => $staff,
        ));
    }

    /**
     * Disable staff account.
     *
     * @Route("/disable/{id}", name="staff_disable")
     * @Method("GET")
     */
    public function disableAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $staff = $em->getRepository('DoraBundle:Staff')->find($id);
        $staff->setEnabled(false);
        $em->flush();

        return $this->redirectToRoute("staff_index");
    }
    /**
     * enable staff account.
     *
     * @Route("/enable/{id}", name="staff_enable")
     * @Method("GET")
     */
    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $staff = $em->getRepository('DoraBundle:Staff')->find($id);
        $staff->setEnabled(true);
        $em->flush();
        return $this->redirectToRoute("staff_index");
    }


    /**
     * Creates a new staff entity.
     *
     * @Route("/new", name="staff_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $staff = new Staff();
        $form = $this->createForm('DoraBundle\Form\StaffType', $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $staff->addRole("ROLE_STAFF");
            $staff->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($staff);
            $em->flush();

            return $this->redirectToRoute('staff_show', array('id' => $staff->getId()));
        }else if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('@Dora/staff/new.html.twig', array(
                'staff' => $staff,
                'form' => $form->createView(),
                "error"=>"Verifier les champs"
            ));
        }

        return $this->render('@Dora/staff/new.html.twig', array(
            'staff' => $staff,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a staff entity.
     *
     * @Route("/{id}", name="staff_show")
     * @Method("GET")
     */
    public function showAction(Staff $staff)
    {
        $deleteForm = $this->createDeleteForm($staff);

        return $this->render('@Dora/staff/show.html.twig', array(
            'staff' => $staff,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing staff entity.
     *
     * @Route("/{id}/edit", name="staff_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Staff $staff)
    {
        $deleteForm = $this->createDeleteForm($staff);
        $editForm = $this->createForm('DoraBundle\Form\StaffType', $staff);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->render('@Dora/staff/edit.html.twig', array( 'edit_form' => $editForm->createView(),"msg"=>"Modfié avec succés",'id' => $staff->getId()));
        }else if($editForm->isSubmitted() &&!$editForm->isValid()){
            return $this->render('@Dora/staff/edit.html.twig', array('edit_form' => $editForm->createView(),"error"=>"Les champs sont invalides",'id' => $staff->getId()));
        }

        return $this->render('@Dora/staff/edit.html.twig', array(
            'staff' => $staff,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a staff entity.
     *
     * @Route("/{id}", name="staff_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Staff $staff)
    {
        $form = $this->createDeleteForm($staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($staff);
            $em->flush();
        }

        return $this->redirectToRoute('staff_index');
    }

    /**
     * Creates a form to delete a staff entity.
     *
     * @param Staff $staff The staff entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Staff $staff)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('staff_delete', array('id' => $staff->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
