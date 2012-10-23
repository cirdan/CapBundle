<?php
namespace SF\CapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SF\UserBundle\Entity\User;
use SF\CapBundle\Entity\CapRunner;


/**
 * SF\CapBundle\Entity\CapUser
 *
 * @ORM\Entity(repositoryClass="SF\CapBundle\Entity\CapUserRepository")
 */
class CapUser extends User
{

    /**
     * @ORM\OneToOne(targetEntity="SF\CapBundle\Entity\CapRunner")
     * @ORM\JoinColumn(name="runner_id", referencedColumnName="id")
     */
    protected $runner;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $firstname;




    /**
     * Set firstname
     *
     * @param string $salt
     * @return CapUser
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Set lastname
     *
     * @param string $salt
     * @return CapUser
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
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


    /**
     * Constructor
     */
    public function __construct()
    {
    }
    
}