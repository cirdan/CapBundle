<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use SF\CapBundle\Entity\Sortie;
use SF\CapBundle\Form\SortieType;
use SF\CapBundle\Entity\CapRunner;
use SF\CapBundle\Entity\DateTimeFrench;
use SF\CapBundle\Entity\CapGoalSubscription;



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
            $this->updateRunnerGoals($entity);
            $this->updateRunnerBadges($entity);
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
            $this->updateRunnerGoals($entity);
            $this->updateRunnerBadges($entity);
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

    private function updateRunnerGoals($entity){

        $em = $this->getDoctrine()->getManager();

        // We update the goals completion for the current user
        // We first get the subscribed AND unreached goals
        $pending=$em->getRepository('SFCapBundle:CapGoalSubscription')->findPendingByRunner($this->get("CapRunner"));
        // For each goal, let's see if it has been reached
        foreach($pending as $subscription){
            $goal=$subscription->getGoal();
            $achieved=$this->compareGoalCompletion($entity,$goal);
            if($achieved){
                $subscription->setAchievementDate($entity->getDate());
                $em->persist($subscription);
                $em->flush();
            }
        // Return the reached goals
        }
    }
    private function updateRunnerBadges($entity){

        $em = $this->getDoctrine()->getManager();

        // We update the goals completion for the current user
        // We first get the subscribed AND unreached goals
        $pending=$em->getRepository('SFCapBundle:CapGoal')->findPotentialBadgesByRunner($this->get("CapRunner"));
        // For each goal, let's see if it has been reached
        foreach($pending as $goal){
//            echo $goal['goal']."<hr />";
            $achieved=$this->compareGoalCompletion($entity,$goal['goal']);
            // Consider scheduled date
            if($achieved){
                $subscription=new CapGoalSubscription;
                $subscription->setRunner($this->get("CapRunner"));
                $subscription->setGoal($goal['goal']);
                $subscription->setDate(new \DateTime());
                $subscription->setAchievementDate($entity->getDate());
                $em->persist($subscription);
                $em->flush();
            }
        // Return the reached goals
        }
//        die($pending);
    }
    private function compareGoalCompletion($entity,$goal){

        $em = $this->getDoctrine()->getManager();
        $achieved=true;
        if(!$goal->getSum()){
            if(!$goal->getDelay()){
                if(!$goal->getDuration()){
                    if($goal->getDistance()){
                        if($entity->getDistance()<$goal->getDistance()){
                            $achieved=false;
                            echo "raté par distance <br />";
                        }
                    }
                }else{
                    if($goal->getDistance()){
                        // We have to consider speed in addition
                        $goalSpeed=$goal->getDistance()/$goal->getDuration();
                        $effectiveSpeed=$entity->getDistance()/$entity->getDuration();
                        if($entity->getDistance()<$goal->getDistance() OR $entity->getDuration()<$goal->getDuration() OR $effectiveSpeed<$goalSpeed){
                            $achieved=false;
                            echo "raté par distance ou durée ou vitesse <br />";
                        }
                    }else{
                        if($entity->getDuration()<$goal->getDuration()){
                            $achieved=false;
                            echo "raté par durée <br />";
                        }
                    }
                }
            }else{
                throw new \Exception('This goal seems misconfigured');
                /*if(!$goal->getDuration()){
                    if($goal->getDistance()){

                    }
                }else{
                    if($goal->getDistance()){

                    }else{
                        
                    }
                }*/
            }
        }else{
            if(!$goal->getDelay()){
                $totalDistance=$em->getRepository('SFCapBundle:Sortie')->totalBetween(null,null,$this->get("CapRunner"))*1000;
                $totalDuration=$em->getRepository('SFCapBundle:Sortie')->totalDurationBetween(null,null,$this->get("CapRunner"));
                if(!$goal->getDuration()){
                    if($goal->getDistance()){
                        if($totalDistance<$goal->getDistance()){
                            $achieved=false;
                            echo "raté par cumul duration : objectif : ".$goal->getDuration()." en ".$goal->getDelay()." jours, réalisé : ".$totalDuration."<br />";
                        }
                    }
                }else{
                    if($goal->getDistance()){
                        // We have to consider speed in addition
                        $goalSpeed=$goal->getDistance()/$goal->getDuration();
                        $effectiveSpeed=$totalDistance/$totalDuration;
                        if($totalDistance<$goal->getDistance() OR $totalDuration<$goal->getDuration() OR $effectiveSpeed<$goalSpeed){
                            $achieved=false;
                            echo "raté par cumul duration ou distance";
                        }
                    }else{
                        if($totalDuration<$goal->getDuration()){
                            $achieved=false;
                            echo "raté par cumul duration ou distance";
                        }
                    }
                }
            }else{
                $totalDistance=$em->getRepository('SFCapBundle:Sortie')->totalLastDays($goal->getDelay(),$this->get("CapRunner"))*1000;
                $totalDuration=$em->getRepository('SFCapBundle:Sortie')->totalDurationLastDays($goal->getDelay(),$this->get("CapRunner"));
                if(!$goal->getDuration()){
                    if($goal->getDistance()){
                        if($totalDistance<$goal->getDistance()){
                            $achieved=false;
                            echo "raté par cumul duration : objectif : ".$goal->getDuration()." en ".$goal->getDelay()." jours, réalisé : ".$totalDuration."<br />";
                        }
                    }
                }else{
                    if($goal->getDistance()){
                        if($totalDistance<$goal->getDistance() OR $totalDuration<$goal->getDuration()){
                            $achieved=false;
                            echo "raté par cumul duration ou distance";
                        }
                    }else{
                        if($totalDuration<$goal->getDuration()){
                            $achieved=false;
                            echo "raté par cumul duration ou distance";
                        }
                    }
                }
            }
        }

        /*if(!$goal->getDelay()){ // We consider a single run : the current one
            // Consider duration
            if($goal->getDuration() && $entity->getDuration()<$goal->getDuration()){
                $achieved=false;
                echo "raté par durée <br />";
            }
            // Consider distance
            if($goal->getDistance() && $entity->getDistance()<$goal->getDistance()){
                $achieved=false;
                echo "raté par distance <br />";
            }
        }else{ // We consider multiple runs
            $totalDistance=$em->getRepository('SFCapBundle:Sortie')->totalLastDays($goal->getDelay(),$this->get("CapRunner"))*1000;
            $totalDuration=$em->getRepository('SFCapBundle:Sortie')->totalDurationLastDays($goal->getDelay(),$this->get("CapRunner"));
            // Consider duration
            if($goal->getDuration() && $totalDuration<$goal->getDuration()){
                $achieved=false;
                echo "raté par cumul duration : objectif : ".$goal->getDuration()." en ".$goal->getDelay()." jours, réalisé : ".$totalDuration."<br />";
            }
            // Consider distance
            if($goal->getDistance() && $totalDistance<$goal->getDistance()){
                $achieved=false;
                echo "raté par cumul distance : objectif : ".$goal->getDistance()." en ".$goal->getDelay()." jours, réalisé : ".$totalDistance."<br />";
            }
        }*/
        // Consider scheduled date
        if($achieved){
            return true;
        }else{
            return false;
        }
    }

}
