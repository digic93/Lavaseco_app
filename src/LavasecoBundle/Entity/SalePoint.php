<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SalePoint
 *
 * @ORM\Table(name="sale_point")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\SalePointRepository")
 */
class SalePoint {

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
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="device_id", type="string", length=50, unique=true, nullable= true)
     */
    private $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=0)
     */
    private $balance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="turn", type="integer")
     */
    private $turn;

    /**
     * @var int
     *
     * @ORM\Column(name="bill_consecutive", type="integer")
     */
    private $billConsecutive;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_open", type="boolean")
     */
    private $isOpen;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="CashTransaction", mappedBy="salePoint")
     */
    protected $cashTransactions;
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="salePoint")
     */
    protected $bills;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="BranchOffice", inversedBy="salePoints")
     * @ORM\JoinColumn(name="branch_office_id", referencedColumnName="id")
     */
    protected $branchOffice;

    
    /**
     * Constructor
     */
    public function __construct() {
        $this->setTurn(1);
        $this->setBalance(0);
        $this->setUpdatedAt();
        $this->setIsOpen(false);
        $this->setIsActive(false);
        $this->setBillConsecutive(1);
        $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $this->cashTransactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SalePoint
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
     * Set balance
     *
     * @param string $balance
     *
     * @return SalePoint
     */
    public function setBalance($balance) {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SalePoint
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return SalePoint
     */
    public function setUpdatedAt() {
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set turn
     *
     * @param integer $turn
     *
     * @return SalePoint
     */
    public function setTurn($turn) {
        $this->turn = $turn;

        return $this;
    }

    /**
     * Get turn
     *
     * @return integer
     */
    public function getTurn() {
        return $this->turn;
    }

    /**
     * Set billConsecutive
     *
     * @param integer $billConsecutive
     *
     * @return SalePoint
     */
    public function setBillConsecutive($billConsecutive) {
        $this->billConsecutive = $billConsecutive;

        return $this;
    }

    /**
     * Get billConsecutive
     *
     * @return integer
     */
    public function getBillConsecutive() {
        return $this->billConsecutive;
    }

    /**
     * Set isOpen
     *
     * @param boolean $isOpen
     *
     * @return SalePoint
     */
    public function setIsOpen($isOpen) {
        $this->isOpen = $isOpen;

        return $this;
    }

    /**
     * Get isOpen
     *
     * @return boolean
     */
    public function getIsOpen() {
        return $this->isOpen;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return SalePoint
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Add cashTransaction
     *
     * @param \LavasecoBundle\Entity\CashTransaction $cashTransaction
     *
     * @return SalePoint
     */
    public function addCashTransaction(\LavasecoBundle\Entity\CashTransaction $cashTransaction) {
        $this->cashTransactions[] = $cashTransaction;

        return $this;
    }

    /**
     * Remove cashTransaction
     *
     * @param \LavasecoBundle\Entity\CashTransaction $cashTransaction
     */
    public function removeCashTransaction(\LavasecoBundle\Entity\CashTransaction $cashTransaction) {
        $this->cashTransactions->removeElement($cashTransaction);
    }

    /**
     * Get cashTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCashTransactions() {
        return $this->cashTransactions;
    }

    /**
     * Set deviceId
     *
     * @param string $deviceId
     *
     * @return SalePoint
     */
    public function setDeviceId($deviceId) {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * Get deviceId
     *
     * @return string
     */
    public function getDeviceId() {
        return $this->deviceId;
    }
    
    public function setNextTurn(){
        $this->turn += 1;
    }

    public function getCreatedAtString() {
        return $this->createdAt->format('d/m/Y H:i');
    }

    public function getUpdateAtString() {
        return $this->updatedAt->format('d/m/Y H:i');
    }
    

    /**
     * Add bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return SalePoint
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
     * Set branchOffice
     *
     * @param \LavasecoBundle\Entity\BranchOffice $branchOffice
     *
     * @return SalePoint
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
    
    public function getBranchOfficeName()
    {
        return $this->branchOffice->getName();
    }
}
