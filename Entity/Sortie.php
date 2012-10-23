<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SF\CapBundle\Entity\Sortie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SF\CapBundle\Entity\SortieRepository")
 */
class Sortie
{
    /**
     * @ORM\ManyToOne(targetEntity="SF\CapBundle\Entity\CapRunner", inversedBy="Sorties")
     * @ORM\JoinColumn(name="runner_id", referencedColumnName="id")
     */
    protected $runner;

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
    private $date;
    /**
     * @var \Time $time
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;

    /**
     * @var integer $duration
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var integer $distance
     *
     * @ORM\Column(name="distance", type="integer")
     */
    private $distance;
    /**
     * @var text $comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \Date $date
     * @return Sortie
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \Time $time
     * @return Sortie
     */
    public function setTime($time)
    {
        $this->time = $time;
    
        return $this;
    }

    /**
     * Get time
     *
     * @return \Time 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Sortie
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     * @return Sortie
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    
        return $this;
    }

    /**
     * Get distance
     *
     * @return integer 
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Sortie
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }








    /**
     * Set runner
     *
     * @param SF\CapBundle\Entity\CapRunner $runner
     * @return Sortie
     */
    public function setRunner(\SF\CapBundle\Entity\CapRunner $runner = null)
    {
        $this->runner = $runner;
    
        return $this;
    }

    /**
     * Get runner
     *
     * @return SF\CapBundle\Entity\CapRunner 
     */
    public function getRunner()
    {
        return $this->runner;
    }

    public function getSpeed()
    {
        $speed=$this->duration/($this->distance/1000);
        $nbMin=floor($speed);
        $nbSec=floor(60*($speed-$nbMin));
        return new \DateInterval('PT'.$nbMin.'M'.$nbSec.'S');
    }


    public function __construct()
    {
        $this->time=new \DateTime();
        $this->date=new \DateTime();
    }

}