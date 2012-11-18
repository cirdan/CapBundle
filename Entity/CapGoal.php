<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SF\CapBundle\Entity\CapGoal
 *
 * @ORM\Entity(repositoryClass="SF\CapBundle\Entity\CapGoalRepository")
 */
class CapGoal
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="SF\CapBundle\Entity\CapGoalSubscription", mappedBy="goal")
     */
    protected $subscriptions;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $subscribers
     *
     */
    protected $subscribers;

    /**
     * @ORM\ManyToOne(targetEntity="SF\CapBundle\Entity\CapRunner", inversedBy="ownedGoals")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @var integer $duration
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var boolean $isPublic
     *
     * @ORM\Column(name="isPublic", type="boolean", nullable=true)
     */
    private $isPublic;

    /**
     * @var boolean $automatic
     *
     * @ORM\Column(name="automatic", type="boolean", nullable=true)
     */
    private $automatic;

    /**
     * @var boolean $permanent
     *
     * @ORM\Column(name="permanent", type="boolean", nullable=true)
     */
    private $permanent;

    /**
     * @var integer $distance
     *
     * @ORM\Column(name="distance", type="integer", nullable=true)
     */
    private $distance;
    /**
     * @var integer $delay
     *
     * @ORM\Column(name="delay", type="integer", nullable=true)
     */
    private $delay;
    /**
     * @var integer $sum
     *
     * @ORM\Column(name="sum", type="integer", nullable=true)
     */
    private $sum;
    /**
     * @var text $comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var text $name
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * Add subscription
     *
     * @param SF\CapBundle\Entity\CapGoalSubscription $subscription
     * @return CapGoal
     */
    public function addSubscription(\SF\CapBundle\Entity\CapGoalSubscription $subscription)
    {
        $this->subscriptions[] = $subscription;
    
        return $this;
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
     * Set subscriptions
     *
     * @param Doctrine\Common\Collections\Collection $subscriptions
     * @return Goal
     */
    public function setSubscription($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    
        return $this;
    }

    /**
     * Get subscribers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSubscribers()
    {
        $subscriptions=$this->getSubscriptions();
        $this->subscribers = new \Doctrine\Common\Collections\ArrayCollection();
        foreach( $subscriptions as $subscription ) {
            $this->subscribers->add($subscription->getRunner());
        }  
        return $this->subscribers;
    }


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


    public function getSpeed()
    {
        $speed=$this->getDuration()/($this->getDistance()/1000);
        $nbMin=floor($speed);
        $nbSec=floor(60*($speed-$nbMin));
        return new \DateInterval('PT'.$nbMin.'M'.$nbSec.'S');
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
     * Set delay
     *
     * @param integer $delay
     * @return Sortie
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    
        return $this;
    }

    /**
     * Get delay
     *
     * @return integer 
     */
    public function getDelay()
    {
        return $this->delay;
    }


    /**
     * Set sum
     *
     * @param integer $sum
     * @return Sortie
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
    
        return $this;
    }

    /**
     * Get sum
     *
     * @return integer 
     */
    public function getSum()
    {
        return $this->sum;
    }



    /**
     * Set name
     *
     * @param string $name
     * @return Sortie
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return CapGoal
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    
        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * isOwnedBy
     *
     * @return boolean 
     */
    public function isOwnedBy(\SF\CapBundle\Entity\CapRunner $runner)
    {
        return $this->owner===$runner;
    }

    /**
     * Set owner
     *
     * @param SF\CapBundle\Entity\CapRunner $owner
     * @return Sortie
     */
    public function setOwner(\SF\CapBundle\Entity\CapRunner $owner)
    {
        $this->owner = $owner;
    
        return $this;
    }

    /**
     * Get owner
     *
     * @return SF\CapBundle\Entity\CapRunner 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscribers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function __toString(){
        return $this->getName();
    }


}