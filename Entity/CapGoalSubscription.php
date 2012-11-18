<?php

namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SF\CapBundle\Entity\CapGoalSubscription
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SF\CapBundle\Entity\CapGoalSubscriptionRepository")
 */
class CapGoalSubscription
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
     * @ORM\ManyToOne(targetEntity="SF\CapBundle\Entity\CapRunner", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="runner_id", referencedColumnName="id")
     */
    protected $runner;
    /**
     * @ORM\ManyToOne(targetEntity="SF\CapBundle\Entity\CapGoal", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="goal_id", referencedColumnName="id")
     */
    protected $goal;

    /**
     * @var \DateTime $scheduledDate
     *
     * @ORM\Column(name="scheduledDate", type="date", nullable=true)
     */
    protected $scheduledDate;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @var \DateTime $achievementDate
     *
     * @ORM\Column(name="achievementDate", type="date",nullable=true)
     */
    protected $achievementDate;


    /**
     * Set date
     *
     * @param \DateTime $date
     * @return CapGoalSubscription
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
     * Set scheduledDate
     *
     * @param \DateTime $scheduledDate
     * @return CapGoalSubscription
     */
    public function setScheduledDate($scheduledDate)
    {
        $this->scheduledDate = $scheduledDate;
    
        return $this;
    }

    /**
     * Get scheduledDate
     *
     * @return \DateTime
     */
    public function getScheduledDate()
    {
        return $this->scheduledDate;
    }

    /**
     * Set achievementDate
     *
     * @param \DateTime $achievementDate
     * @return CapGoalSubscription
     */
    public function setAchievementDate($achievementDate)
    {
        $this->achievementDate = $achievementDate;
    
        return $this;
    }

    /**
     * Get achievementDate
     *
     * @return \DateTime
     */
    public function getAchievementDate()
    {
        return $this->achievementDate;
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
     * Set runner
     *
     * @param SF\CapBundle\Entity\CapRunner $runner
     * @return CapGoalSubscription
     */
    public function setRunner(\SF\CapBundle\Entity\CapRunner $runner)
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


    /**
     * Set goal
     *
     * @param SF\CapBundle\Entity\CapGoal $goal
     * @return CapGoalSubscription
     */
    public function setGoal(\SF\CapBundle\Entity\CapGoal $goal)
    {
        $this->goal = $goal;
    
        return $this;
    }

    /**
     * Get goal
     *
     * @return SF\CapBundle\Entity\CapGoal 
     */
    public function getGoal()
    {
        return $this->goal;
    }

    public function __toString(){
        return "Subscription between runner ".$this->getRunner()." and goal ".$this->getGoal().".";
    }


}