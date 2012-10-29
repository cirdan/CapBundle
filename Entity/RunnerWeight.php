<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SF\CapBundle\Entity\RunnerWeight
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SF\CapBundle\Entity\RunnerWeightRepository")
 */
class RunnerWeight
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SF\CapBundle\Entity\CapRunner", inversedBy="runnerWeights")
     * @ORM\JoinColumn(name="runner_id", referencedColumnName="id")
     */
    protected $runner;

    /**
     * @var float $weight
     *
     * @ORM\Column(name="weight", type="float")
     */
    private $weight;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set weight
     *
     * @param float $weight
     * @return RunnerWeight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    
        return $this;
    }

    /**
     * Get weight
     *
     * @return float 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return RunnerWeight
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }


    

    /**
     * Set runner
     *
     * @param SF\CapBundle\Entity\CapRunner $runner
     * @return CapRunner
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


}
