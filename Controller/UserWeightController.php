<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SF\CapBundle\Entity\UserWeight;
use SF\CapBundle\Form\UserWeightType;

/**
 * UserWeight controller.
 *
 */
class UserWeightController extends Controller
{
    /**
     * Lists all UserWeight entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SFCapBundle:UserWeight')->findByUser($this->getUser());

        
        return $this->render('SFCapBundle:UserWeight:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a UserWeight entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:UserWeight')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserWeight entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SFCapBundle:UserWeight:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Displays a form to create a new UserWeight entity.
     *
     */
    public function newAction()
    {
        $entity = new UserWeight();
        $form   = $this->createForm(new UserWeightType(), $entity);

        return $this->render('SFCapBundle:UserWeight:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new UserWeight entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new UserWeight();
        $form = $this->createForm(new UserWeightType(), $entity);
        $form->bind($request);
        $entity->setUser($this->getUser());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('weight_show', array('id' => $entity->getId())));
        }

        return $this->render('SFCapBundle:UserWeight:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserWeight entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:UserWeight')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserWeight entity.');
        }

        $editForm = $this->createForm(new UserWeightType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SFCapBundle:UserWeight:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing UserWeight entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:UserWeight')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserWeight entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserWeightType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('weight_edit', array('id' => $id)));
        }

        return $this->render('SFCapBundle:UserWeight:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserWeight entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SFCapBundle:UserWeight')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserWeight entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('weight'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
