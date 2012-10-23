<?php

namespace SF\CapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SF\SFCapBundle\Entity\Sortie;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $runner=$this->get('CapRunner');
        return $this->render(
            'SFCapBundle:Default:index.html.twig',
            array(
                "totalLast7days"=>$em->getRepository('SFCapBundle:Sortie')->totalLastDays(7,$runner),
                "totalLast30days"=>$em->getRepository('SFCapBundle:Sortie')->totalLastDays(30,$runner),
                "totalLast365days"=>$em->getRepository('SFCapBundle:Sortie')->totalLastDays(365,$runner),
                "totalLastdays"=>$em->getRepository('SFCapBundle:Sortie')->totalBetween(null,null,$runner),
                "plotter"=>$em->getRepository('SFCapBundle:Sortie')->getLastDailyData(14,$runner),
                "lastRun"=>$em->getRepository('SFCapBundle:Sortie')->getLastRun($runner),
                "runner"=>$runner
                )
            );
    }
    public function collectiveDataAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        return $this->render(
            'SFCapBundle:Default:collectiveData.html.twig',
            array(
                "totalLast7days"=>$em->getRepository('SFCapBundle:Sortie')->totalLastDays(7),
                "totalLast30days"=>$em->getRepository('SFCapBundle:Sortie')->totalLastDays(30),
                "totalLast365days"=>$em->getRepository('SFCapBundle:Sortie')->totalLastDays(365),
                "totalLastdays"=>$em->getRepository('SFCapBundle:Sortie')->totalBetween(null,null)
                )
            );
    }
    public function JSMainAction()
    {
        return $this->render(
            'SFCapBundle:Default:JSMain.js.twig',
            array(
                )
            );
    }

}
