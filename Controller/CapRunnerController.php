<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Co;

use SF\CapBundle\Entity\CapRunner;

/**
 * Sortie controller.
 *
 */
class CapRunnerController extends Co
{

  public function recognizeAction(Request $request, $hash)
    {
        // On est identifié : redirection page d'accueil
        if($this->get("security.context")->isGranted('IS_AUTHENTICATED_FULLY') && $this->getUser()->getRunner()==$runner ){
          return $this->redirect($this->generateUrl('sf_cap_homepage'));
        }
        $em = $this->getDoctrine()->getEntityManager();
        $runner=$em->getRepository('SFCapBundle:CapRunner')->findOneByHash($hash);
        if($runner){
          $user = $em->createQuery("select u from SFCapUserBundle:CapUser u where u.runner=?1")
                    ->setParameter(1, $runner->getId()) // or 
                    ->getResult();
          if($user){
            $user=array_pop($results);
          }
          //$user=$em->getRepository('SFCapUserBundle:CapUser')->findOneBy(array('runner' => $runner->getId()));
          if($user){
            return $this->redirect($this->generateUrl('login'));
          }
        }
        $session=$this->get("session");
        $session->invalidate();
        $this->get("security.context")->setToken(null);
        if($runner){
          $session->set('runnerId',$runner->getId());
        }else{
          $session->remove('runnerId');
        }
    return $this->redirect($this->generateUrl('sf_cap_homepage'));
    }

  public function byeAction(Request $request)
    {
      $session=$this->get("session");
      $session->invalidate();
      $this->get("security.context")->setToken(null);
      return $this->redirect($this->generateUrl('sf_cap_homepage'));
    }


}
