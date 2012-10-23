<?php
namespace SF\CapBundle;

use Doctrine\ORM\Mapping as ORM;
use SF\CapBundle\Entity\CapRunner;
use Symfony\Component\HttpFoundation\Session\Session;

class CapRunnerFactory
{    
	static function get($secucontext,$session,$em,$create)
    {

        //L'utilisateur est connecté : on renvoie son runner
		if( $secucontext && $secucontext->isGranted('IS_AUTHENTICATED_FULLY') ){
			return $secucontext->getToken()->getUser()->getRunner();
		}else{
			//$session = new Session();
	        //Le runnerId est en session
			if($session->get("runnerId") > 0){
				return $em->find('SFCapBundle:CapRunner', $session->get("runnerId"));
	        //Le runnerId n'est pas en session
			}else{
				if($create){
			        $runner = new CapRunner;
			        $em->persist($runner);
			        $em->flush();
			        $session->set("runnerId",$runner->getId());
			        return($runner);
				}else{
			        return null;
				}
			}
		}
    }
}
