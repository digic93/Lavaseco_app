<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author diego
 */

namespace LavasecoBundle\Entity;
 
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;
 
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $phoneNumber; 
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="sellerUser")
     */
    protected $saleBills;
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="customerUser")
     */
    protected $customerbills;
    
    /**
     * @ORM\OneToMany(targetEntity="BillHistory", mappedBy="user")
     */
    protected $billHistories;

    public function __construct() {
        parent::__construct();
        
        $this->saleBills = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerbills = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Add saleBill
     *
     * @param \LavasecoBundle\Entity\Bill $saleBill
     *
     * @return User
     */
    public function addSaleBill(\LavasecoBundle\Entity\Bill $saleBill)
    {
        $this->saleBills[] = $saleBill;

        return $this;
    }

    /**
     * Remove saleBill
     *
     * @param \LavasecoBundle\Entity\Bill $saleBill
     */
    public function removeSaleBill(\LavasecoBundle\Entity\Bill $saleBill)
    {
        $this->saleBills->removeElement($saleBill);
    }

    /**
     * Get saleBills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSaleBills()
    {
        return $this->saleBills;
    }

    /**
     * Add customerbill
     *
     * @param \LavasecoBundle\Entity\Bill $customerbill
     *
     * @return User
     */
    public function addCustomerbill(\LavasecoBundle\Entity\Bill $customerbill)
    {
        $this->customerbills[] = $customerbill;

        return $this;
    }

    /**
     * Remove customerbill
     *
     * @param \LavasecoBundle\Entity\Bill $customerbill
     */
    public function removeCustomerbill(\LavasecoBundle\Entity\Bill $customerbill)
    {
        $this->customerbills->removeElement($customerbill);
    }

    /**
     * Get customerbills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerbills()
    {
        return $this->customerbills;
    }

    /**
     * Add billHistory
     *
     * @param \LavasecoBundle\Entity\BillHistory $billHistory
     *
     * @return User
     */
    public function addBillHistory(\LavasecoBundle\Entity\BillHistory $billHistory)
    {
        $this->billHistories[] = $billHistory;

        return $this;
    }

    /**
     * Remove billHistory
     *
     * @param \LavasecoBundle\Entity\BillHistory $billHistory
     */
    public function removeBillHistory(\LavasecoBundle\Entity\BillHistory $billHistory)
    {
        $this->billHistories->removeElement($billHistory);
    }

    /**
     * Get billHistories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillHistories()
    {
        return $this->billHistories;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function getName(){
        return $this->getFirstName() . " " . $this->getLastName();
    } 
}
