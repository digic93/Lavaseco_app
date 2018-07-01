<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\AddressRepository")
 */
class Address
{
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
     * @ORM\Column(name="nickname", type="string", length=50)
     */
    private $nickname;
      

    /**
     * @var string
     *
     * @ORM\Column(name="placeName", type="string", length=50)
     */
    private $placeName;
      
    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;
      
    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude; 
    
    /**
     * @ORM\Column(name="observation", type="text")
     */
    private $observation;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="addressApp")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="BranchOffice", inversedBy="addressApp")
     * @ORM\JoinColumn(name="branch_office_id", referencedColumnName="id")
     */
    protected $branchOffice;
    
     
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="addressDelivery")
     */
    protected $billsDelivery;
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="addressCollect")
     */
    protected $billsCollect;
    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bills = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Address
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set placeName
     *
     * @param string $placeName
     *
     * @return Address
     */
    public function setPlaceName($placeName)
    {
        $this->placeName = $placeName;

        return $this;
    }

    /**
     * Get placeName
     *
     * @return string
     */
    public function getPlaceName()
    {
        return $this->placeName;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return Address
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Set customer
     *
     * @param \LavasecoBundle\Entity\Customer $customer
     *
     * @return Address
     */
    public function setCustomer(\LavasecoBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \LavasecoBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return Address
     */
    public function addBill(\LavasecoBundle\Entity\Bill $bill)
    {
        $this->bills[] = $bill;

        return $this;
    }

    /**
     * Remove bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     */
    public function removeBill(\LavasecoBundle\Entity\Bill $bill)
    {
        $this->bills->removeElement($bill);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Add billsDelivery
     *
     * @param \LavasecoBundle\Entity\Bill $billsDelivery
     *
     * @return Address
     */
    public function addBillsDelivery(\LavasecoBundle\Entity\Bill $billsDelivery)
    {
        $this->billsDelivery[] = $billsDelivery;

        return $this;
    }

    /**
     * Remove billsDelivery
     *
     * @param \LavasecoBundle\Entity\Bill $billsDelivery
     */
    public function removeBillsDelivery(\LavasecoBundle\Entity\Bill $billsDelivery)
    {
        $this->billsDelivery->removeElement($billsDelivery);
    }

    /**
     * Get billsDelivery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillsDelivery()
    {
        return $this->billsDelivery;
    }

    /**
     * Add billsCollect
     *
     * @param \LavasecoBundle\Entity\Bill $billsCollect
     *
     * @return Address
     */
    public function addBillsCollect(\LavasecoBundle\Entity\Bill $billsCollect)
    {
        $this->billsCollect[] = $billsCollect;

        return $this;
    }

    /**
     * Remove billsCollect
     *
     * @param \LavasecoBundle\Entity\Bill $billsCollect
     */
    public function removeBillsCollect(\LavasecoBundle\Entity\Bill $billsCollect)
    {
        $this->billsCollect->removeElement($billsCollect);
    }

    /**
     * Get billsCollect
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillsCollect()
    {
        return $this->billsCollect;
    }

    /**
     * Set salePoint
     *
     * @param \LavasecoBundle\Entity\SalePoint $salePoint
     *
     * @return Address
     */
    public function setSalePoint(\LavasecoBundle\Entity\SalePoint $salePoint = null)
    {
        $this->salePoint = $salePoint;

        return $this;
    }

    /**
     * Get salePoint
     *
     * @return \LavasecoBundle\Entity\SalePoint
     */
    public function getSalePoint()
    {
        return $this->salePoint;
    }

    /**
     * Set branchOffice
     *
     * @param \LavasecoBundle\Entity\BranchOffice $branchOffice
     *
     * @return Address
     */
    public function setBranchOffice(\LavasecoBundle\Entity\BranchOffice $branchOffice = null)
    {
        $this->branchOffice = $branchOffice;

        return $this;
    }

    /**
     * Get branchOffice
     *
     * @return \LavasecoBundle\Entity\BranchOffice
     */
    public function getBranchOffice()
    {
        return $this->branchOffice;
    }
}
