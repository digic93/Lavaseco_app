<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\CustomerRepository")
 */
class Customer {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=70, nullable=true, unique=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=50, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_Number", type="string", length=50, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_visit", type="datetime")
     */
    private $lastVisit;

    /**
     * @var int
     *
     * @ORM\Column(name="total_visits", type="integer")
     */
    private $totalVisits;

    /**
     * @var int
     *
     * @ORM\Column(name="total_spent", type="integer")
     */
    private $totalSpent;
    
    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=50, nullable=true)
     */
    private $uuid;
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="customer")
     */
    protected $bills;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="customer")
     */
    protected $addressApp;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Customer
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * Set password
     *
     * @param string $password
     *
     * @return Customer
     */
    public function setPassword($password) {
        $this->password =  hash('sha256',$password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }    

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Customer
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Customer
     */
    public function setPoints($points) {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return int
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return Customer
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set lastVisit
     *
     * @param \DateTime $lastVisit
     *
     * @return Customer
     */
    public function setLastVisit($lastVisit) {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    /**
     * Get lastVisit
     *
     * @return \DateTime
     */
    public function getLastVisit() {
        return $this->lastVisit;
    }

    /**
     * Set totalVisits
     *
     * @param integer $totalVisits
     *
     * @return Customer
     */
    public function setTotalVisits($totalVisits) {
        $this->totalVisits = $totalVisits;

        return $this;
    }

    /**
     * Get totalVisits
     *
     * @return int
     */
    public function getTotalVisits() {
        return $this->totalVisits;
    }

    /**
     * Set totalSpent
     *
     * @param integer $totalSpent
     *
     * @return Customer
     */
    public function setTotalSpent($totalSpent) {
        $this->totalSpent = $totalSpent;

        return $this;
    }

    /**
     * Get totalSpent
     *
     * @return int
     */
    public function getTotalSpent() {
        return $this->totalSpent;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->setPoints(0);
        $this->setTotalSpent(0);
        $this->setTotalVisits(0);
        $this->setLastVisit(new \DateTime(date('Y-m-d H:i:s')));
        $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $this->bills = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return Customer
     */
    public function addBill(\LavasecoBundle\Entity\Bill $bill) {
        $this->bills[] = $bill;

        return $this;
    }

    /**
     * Remove bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     */
    public function removeBill(\LavasecoBundle\Entity\Bill $bill) {
        $this->bills->removeElement($bill);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBills() {
        return $this->bills;
    }

    public function getLastVisitString() {
        return $this->lastVisit->format('d/m/Y H:i');
    }

    public function getCreatedAtString() {
        return $this->createdAt->format('d/m/Y H:i');
    }


    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return Customer
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Add addressApp
     *
     * @param \LavasecoBundle\Entity\Address $addressApp
     *
     * @return Customer
     */
    public function addAddressApp(\LavasecoBundle\Entity\Address $addressApp)
    {
        $this->addressApp[] = $addressApp;

        return $this;
    }

    /**
     * Remove addressApp
     *
     * @param \LavasecoBundle\Entity\Address $addressApp
     */
    public function removeAddressApp(\LavasecoBundle\Entity\Address $addressApp)
    {
        $this->addressApp->removeElement($addressApp);
    }

    /**
     * Get addressApp
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddressApp()
    {
        return $this->addressApp;
    }
}
