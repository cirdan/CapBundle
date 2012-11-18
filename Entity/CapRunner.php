<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SF\CapBundle\Entity\CapRunner
 *
 * @ORM\Entity(repositoryClass="SF\CapBundle\Entity\CapRunnerRepository")
 */
class CapRunner
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="SF\CapBundle\Entity\CapGoalSubscription", mappedBy="runner")
     */
    protected $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="SF\CapBundle\Entity\CapGoal", mappedBy="owner")
     */
    protected $ownedGoals;

    /**
     * @var \hasData $hasData
     *
     * @ORM\Column(name="hasData", type="boolean")
     */
    protected $hasData=false;

    /**
     * @var \hash $hash
     *
     * @ORM\Column(name="hash", type="string")
     */
    protected $hash;



    /**
     * @ORM\OneToMany(targetEntity="SF\CapBundle\Entity\Sortie", mappedBy="runner")
     */
    protected $sorties;

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
     * Add sortie
     *
     * @param SF\CapBundle\Entity\Sortie $sortie
     * @return Runner
     */
    public function addSortie(\SF\CapBundle\Entity\Sortie $sortie)
    {
        $this->sorties[] = $sortie;
    
        return $this;
    }

    /**
     * Remove sortie
     *
     * @param SF\CapBundle\Entity\Sortie $sortie
     */
    public function removeSortie(\SF\CapBundle\Entity\Sortie $sortie)
    {
        $this->sorties->removeElement($sortie);
    }

    /**
     * Get sorties
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSorties()
    {
        return $this->sorties;
    }
    /**
     * Get subscriptions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }
    
    /**
     * @ORM\OneToMany(targetEntity="SF\CapBundle\Entity\RunnerWeight", mappedBy="runner")
     */
    protected $runnerWeights;
    /**
     * Add weights
     *
     * @param SF\CapBundle\Entity\RunnerWeight $runnerWeight
     * @return Runner
     */
    public function addRunnerWeight(\SF\CapBundle\Entity\RunnerWeight $runnerWeight)
    {
        $this->runnerWeights[] = $runnerWeight;
    
        return $this;
    }

    /**
     * Remove weights
     *
     * @param SF\CapBundle\Entity\RunnerWeight $runnerWeight
     */
    public function removeRunnerWeight(\SF\CapBundle\Entity\RunnerWeight $runnerWeight)
    {
        $this->runnerWeights->removeElement($runnerWeight);
    }

    /**
     * Get sorties
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getrunnerWeights()
    {
        return $this->runnerWeights;
    }
    /**
     * Set hasData
     *
     * @param boolean $hasData
     * @return CapRunner
     */
    public function setHasData($hasData)
    {
        $this->hasData = $hasData;
    
        return $this;
    }

    /**
     * Get hasData
     *
     * @return boolean
     */
    public function getHasData()
    {
        return $this->hasData;
    }
    /**
     * Set hash
     *
     * @param boolean $hash
     * @return CapRunner
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sorties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->runnerWeights = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hash = md5(microtime());
    }

    public function __toString()
    {
        return $this->getHash();
    }
    
}