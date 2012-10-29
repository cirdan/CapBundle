<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SF\CapBundle\Entity\RunnerWeight;
use SF\CapBundle\Form\RunnerWeightType;

/**
 * RunnerWeight controller.
 *
 */
class RunnerWeightController extends Controller
{
    /**
     * Lists all RunnerWeight entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SFCapBundle:RunnerWeight')->findByUser($this->getUser());

        
        return $this->render('SFCapBundle:RunnerWeight:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a RunnerWeight entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:RunnerWeight')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RunnerWeight entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SFCapBundle:RunnerWeight:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Displays a form to create a new RunnerWeight entity.
     *
     */
    public function newAction()
    {
        $entity = new RunnerWeight();
        $form   = $this->createForm(new RunnerWeightType(), $entity);

        return $this->render('SFCapBundle:RunnerWeight:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new RunnerWeight entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new RunnerWeight();
        $form = $this->createForm(new RunnerWeightType(), $entity);
        $form->bind($request);
        $entity->setUser($this->getUser());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('weight_show', array('id' => $entity->getId())));
        }

        return $this->render('SFCapBundle:RunnerWeight:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RunnerWeight entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:RunnerWeight')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RunnerWeight entity.');
        }

        $editForm = $this->createForm(new RunnerWeightType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SFCapBundle:RunnerWeight:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing RunnerWeight entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:RunnerWeight')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RunnerWeight entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RunnerWeightType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('weight_edit', array('id' => $id)));
        }

        return $this->render('SFCapBundle:RunnerWeight:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a RunnerWeight entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SFCapBundle:RunnerWeight')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RunnerWeight entity.');
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
