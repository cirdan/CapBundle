<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SF\CapBundle\Entity\CapGoal;
use SF\CapBundle\Entity\CapGoalSubscription;
use SF\CapBundle\Form\CapGoalType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
/**
 * CapGoal controller.
 *
 * @Route("/goal")
 */
class CapGoalController extends Controller
{
    /**
     * Lists all CapGoal entities.
     *
     * @Route("/", name="goal")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SFCapBundle:CapGoal')->findPotentialForRunner($this->get("NewCapRunner"));
        //$entities = $em->getRepository('SFCapBundle:CapGoal')->findSubscribedByRunner($this->get("CapRunner"));
        //$entities = $em->getRepository('SFCapBundle:CapGoal')->findPendingByRunner($this->get("CapRunner"));

        return array(
            'entities' => $entities,
            'runner' => $this->get("CapRunner")
        );
    }
    /**
     * Lists all CapGoal entities.
     *
     * @Route("/", name="goal_badges")
     * @Template()
     */
    public function badgesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SFCapBundle:CapGoal')->findAchievedBadgesByRunner($this->get("CapRunner"));
        //$entities = $em->getRepository('SFCapBundle:CapGoal')->findPendingByRunner($this->get("CapRunner"));

        return array(
            'entities' => $entities,
            'runner' => $this->get("CapRunner")
        );
    }

    /**
     * Finds and displays a CapGoal entity.
     *
     * @Route("/{id}/show", name="goal_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:CapGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CapGoal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'runner' => $this->get("CapRunner")
        );
    }

    /**
     * Displays a form to create a new CapGoal entity.
     *
     * @Route("/new", name="goal_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CapGoal();
        $form   = $this->createForm(new CapGoalType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'runner' => $this->get("CapRunner")
        );
    }

    /**
     * Creates a new CapGoal entity.
     *
     * @Route("/create", name="goal_create")
     * @Method("POST")
     * @Template("SFCapBundle:CapGoal:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new CapGoal();
        $form = $this->createForm(new CapGoalType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setOwner($this->get('CapRunner'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'runner' => $this->get("CapRunner")
        );
    }

    /**
     * Displays a form to edit an existing CapGoal entity.
     *
     * @Route("/{id}/edit", name="goal_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:CapGoal')->find($id);

        // A goal can be edited only by its owner
        $runner=$this->get("CapRunner");
        if(!$entity->isOwnedBy($runner)){
            throw new AccessDeniedException();
        }


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CapGoal entity.');
        }

        $editForm = $this->createForm(new CapGoalType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'runner'      => $runner
        );
    }

    /**
     * Subscribe current runner to an existing CapGoal entity.
     *
     * @Route("/{id}/subscribe", name="goal_subscribe")
     * @Template()
     */
    public function subscribeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:CapGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CapGoal entity.');
        }
        $runner=$this->get('CapRunner');

        $subscription = $em->getRepository('SFCapBundle:CapGoalSubscription')->findOneBy(array('runner'=>$runner,'goal'=>$entity));
        if(!$subscription){
            $subscription = new CapGoalSubscription;
            $subscription->setRunner($runner);
            $subscription->setGoal($entity);
            $subscription->setDate(new \DateTime());
            $em->persist($subscription);
            $em->flush();
        }
        
        return $this->redirect($this->generateUrl('goals'));
    }

    /**
     * unubscribe current runner to an existing CapGoal entity.
     *
     * @Route("/{id}/unsubscribe", name="goal_unsubscribe")
     * @Template("")
     */
    public function unsubscribeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:CapGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CapGoal entity.');
        }
        $runner=$this->get('CapRunner');

        $subscription = $em->getRepository('SFCapBundle:CapGoalSubscription')->findOneBy(array('runner'=>$runner,'goal'=>$entity));
        if($subscription){
            $em->remove($subscription);
            $em->flush();
        }
        
        return $this->redirect($this->generateUrl('goals'));
    }

    /**
     * Edits an existing CapGoal entity.
     *
     * @Route("/{id}/update", name="goal_update")
     * @Method("POST")
     * @Template("SFCapBundle:CapGoal:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:CapGoal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CapGoal entity.');
        }
        // A goal can be edited only by its owner
        $runner=$this->get("CapRunner");
        if(!$entity->isOwnedBy($runner)){
            throw new AccessDeniedException("Not yours");
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CapGoalType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('goal_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'runner' => $this->get("CapRunner")
        );
    }

    /**
     * Deletes a CapGoal entity.
     *
     * @Route("/{id}/delete", name="goal_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SFCapBundle:CapGoal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CapGoal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('goals'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
