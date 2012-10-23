<?php

namespace SF\CapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SF\CapBundle\Entity\CapRunner;

/**
 * Sortie controller.
 *
 */
class CapRunnerController extends \SF\UserBundle\Controller\DefaultController
{

  public function recognizeAction(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $runner=$em->getRepository('SFCapBundle:CapRunner')->findOneByHash($hash);
        if($this->get("security.context")->isGranted('IS_AUTHENTICATED_FULLY') && $this->getUser()->getRunner()==$runner ){
          return $this->redirect($this->generateUrl('sf_cap_homepage'));
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
