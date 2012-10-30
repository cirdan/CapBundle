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
      // Dans tous les cas on supprime le runner en session
      $session=$this->get("session");
      $session->remove('runnerId');
      // On trouve le runner par son hash
      $em = $this->getDoctrine()->getEntityManager();
      $runner = $em->getRepository('SFCapBundle:CapRunner')->findOneByHash($hash);
      // A-t-il un user associé ?
      if($runner){
        $user=$em->getRepository('SFCapUserBundle:CapUser')->findOneBy(array('runner' => $runner->getId()));
        if($user){
          return $this->redirect($this->generateUrl('login'));
        }
      }
      // On déconnecte l'utilisateur courant
      $this->get("security.context")->setToken(null);
      // On met le runner en session
      if($runner){
        $session->set('runnerId',$runner->getId());
      }
      // Et direction la homepage...
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
