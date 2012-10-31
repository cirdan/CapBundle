<?php

namespace SF\CapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
#use SF\SFCapBundle\Entity\GeoTracker;


class GeoTrackerController extends Controller
{
    public function indexAction()
    {
        return $this->render('SFCapBundle:GeoTracker:index.html.twig');
    }
}
