<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use SF\CapBundle\Entity\Sortie;
use SF\CapBundle\Form\SortieType;
use SF\CapBundle\Entity\CapRunner;
use SF\CapBundle\Entity\DateTimeFrench;



/**
 * Sortie controller.
 *
 */
class SortieController extends Controller
{
    /**
     * Lists all Sortie entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SFCapBundle:Sortie')->findByRunner($this->get("CapRunner"));

        return $this->render('SFCapBundle:Sortie:index.html.twig', array(
            'entities' => $entities,
            'runner' => $this->get("CapRunner")
        ));
    }

    /**
     * Finds and displays a Sortie entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:Sortie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sortie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SFCapBundle:Sortie:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'runner' => $this->get("CapRunner")       
        ));
    }

    /**
     * Displays a form to create a new Sortie entity.
     *
     */
    public function newAction()
    {
        $entity = new Sortie();
        $form   = $this->createForm(new SortieType(), $entity);

        return $this->render('SFCapBundle:Sortie:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'runner' => $this->get("CapRunner")
        ));
    }

    /**
     * Creates a new Sortie entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Sortie();
        $form = $this->createForm(new SortieType(), $entity);
        $form->bind($request);
        $runner=$this->get("NewCapRunner");
        $entity->setRunner($runner);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            //We save the runner to set hasData on
            $runner->setHasData(true);
            $em->persist($runner);

            $em->flush();

            return $this->redirect($this->generateUrl('sf_cap_homepage'));
        }

        return $this->render('SFCapBundle:Sortie:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'runner' => $this->get("CapRunner")
        ));
    }

    /**
     * Displays a form to edit an existing Sortie entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:Sortie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sortie entity.');
        }

        $editForm = $this->createForm(new SortieType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SFCapBundle:Sortie:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'runner' => $this->get("CapRunner")
        ));
    }

    /**
     * Edits an existing Sortie entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SFCapBundle:Sortie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sortie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SortieType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);

            $em->flush();

            return $this->redirect($this->generateUrl('sortie_edit', array('id' => $id)));
        }

        return $this->render('SFCapBundle:Sortie:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'runner' => $this->get("CapRunner")
        ));
    }

    /**
     * Deletes a Sortie entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SFCapBundle:Sortie')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sortie entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sortie'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
    * Return a ajax response
    */
    public function resumeAction(){
       $request = $this->get('request');
       $sortieId=$request->get('sortieId');
       $runnerId=$request->get('runnerId');
       $em=$this->getDoctrine()->getEntityManager();
       $lastRun=$em->getRepository('SFCapBundle:Sortie')->findOneById($sortieId);

        $return=json_encode(
            $this->renderView(
                'SFCapBundle:Sortie:sortieResume.html.twig', array(
                    "lastRun"=>$lastRun
                )
            )
        );//jscon encode the array
       return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
    }
    public function resumeJourAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
        $runnerId=$request->get('runnerId');
        $date=new \Datetime($request->get('date'));
        $runner=$em->getRepository('SFCapBundle:CapRunner')->findOneById($runnerId);
        $em=$this->getDoctrine()->getEntityManager();
        $dayRuns=$em->getRepository('SFCapBundle:Sortie')->getDayRuns($date,$runner);
        $return=json_encode(
            $this->renderView(
                'SFCapBundle:Sortie:sortieResumeJour.html.twig', array(
                    "dayRuns"=>$dayRuns,
                    "date"=>$date
                )
            )
        );//jscon encode the array
       return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
    }

    public function plotterDataAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
        $nbDays=min(100,$request->get('nbDays'));
        $way=$request->get('way');
        $runner=$this->get("CapRunner");
        if($way=='forward'){
            $endDate=new DateTimeFrench($request->get('date'));
            $beginDate=new DateTimeFrench($request->get('date'));
            $endDate->add(new \DateInterval('P'.$nbDays.'D'));
        }else{
            $endDate=new DateTimeFrench($request->get('date'));
            $beginDate=new DateTimeFrench($request->get('date'));
            $beginDate->sub(new \DateInterval('P'.$nbDays.'D'));
        }
        $return=json_encode(
           $em->getRepository('SFCapBundle:Sortie')->getDailyData($beginDate,$endDate,$runner)
        );//jscon encode the array
       return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
    }


}
