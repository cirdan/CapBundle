<?php
namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
#use SF\CapUserBundle\Entity\CapUser;


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
     * Add sorties
     *
     * @param SF\CapBundle\Entity\Sortie $sorties
     * @return Runner
     */
    public function addSortie(\SF\CapBundle\Entity\Sortie $sorties)
    {
        $this->sorties[] = $sorties;
    
        return $this;
    }

    /**
     * Remove sorties
     *
     * @param SF\CapBundle\Entity\Sortie $sorties
     */
    public function removeSortie(\SF\CapBundle\Entity\Sortie $sorties)
    {
        $this->sorties->removeElement($sorties);
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
    
}