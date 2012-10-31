<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SF\CapBundle\Entity\GeoTracker
 *
 * @ORM\Table()
 */
class Geotracker
{
    /**
     * @ORM\OneToOne(targetEntity="SF\CapBundle\Entity\CapSortie", inversedBy="sortie")
     * @ORM\JoinColumn(name="sortie_id", referencedColumnName="id")
     */
    protected $sortie;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Date $date
     *
     * @ORM\Column(name="date", type="date")
     */
    public function __construct()
    {
    }

}