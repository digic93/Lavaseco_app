<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bill
 *
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BillRepository")
 */
class Bill {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="consecutive", type="integer", unique=true)
     */
    private $consecutive;

    /**
     * @ORM\Column(name="observation", type="text")
     */
    private $observation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="PayDetail", mappedBy="bill")
     */
    protected $payDetails;

    /**
     * @ORM\OneToMany(targetEntity="BillDetail", mappedBy="bill")
     */
    protected $billDetails;

    /**
     * @ORM\OneToMany(targetEntity="BillHistory", mappedBy="bill")
     */
    protected $billHistories;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="saleBills")
     * @ORM\JoinColumn(name="seller_user_id", referencedColumnName="id", nullable=false)
     */
    protected $sellerUser;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="bills")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="BillState", inversedBy="bills")
     * @ORM\JoinColumn(name="bill_state_id", referencedColumnName="id", nullable=false)
     */
    protected $billState;

    /**
     * @ORM\ManyToOne(targetEntity="ProcessState", inversedBy="bills")
     * @ORM\JoinColumn(name="process_state_id", referencedColumnName="id", nullable=false)
     */
    protected $processState;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentAgreement", inversedBy="bills")
     * @ORM\JoinColumn(name="payment_agreement_id", referencedColumnName="id", nullable=false)
     */
    protected $paymentAgreement;

    /**
     * @ORM\ManyToOne(targetEntity="SalePoint", inversedBy="bills")
     * @ORM\JoinColumn(name="sale_point_id", referencedColumnName="id")
     */
    protected $salePoint;

    /**
     * Constructor
     */
    public function __construct() {
        $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $this->payDetails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->billDetails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->billHistories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set consecutive
     *
     * @param integer $consecutive
     *
     * @return Bill
     */
    public function setConsecutive($consecutive) {
        $this->consecutive = $consecutive;

        return $this;
    }

    /**
     * Get consecutive
     *
     * @return integer
     */
    public function getConsecutive() {
        return $this->consecutive;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return Bill
     */
    public function setObservation($observation) {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation() {
        return $this->observation;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Bill
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
     * Add payDetail
     *
     * @param \LavasecoBundle\Entity\PayDetail $payDetail
     *
     * @return Bill
     */
    public function addPayDetail(\LavasecoBundle\Entity\PayDetail $payDetail) {
        $this->payDetails[] = $payDetail;

        return $this;
    }

    /**
     * Remove payDetail
     *
     * @param \LavasecoBundle\Entity\PayDetail $payDetail
     */
    public function removePayDetail(\LavasecoBundle\Entity\PayDetail $payDetail) {
        $this->payDetails->removeElement($payDetail);
    }

    /**
     * Get payDetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayDetails() {
        return $this->payDetails;
    }

    /**
     * Add billDetail
     *
     * @param \LavasecoBundle\Entity\BillDetail $billDetail
     *
     * @return Bill
     */
    public function addBillDetail(\LavasecoBundle\Entity\BillDetail $billDetail) {
        $this->billDetails[] = $billDetail;

        return $this;
    }

    /**
     * Remove billDetail
     *
     * @param \LavasecoBundle\Entity\BillDetail $billDetail
     */
    public function removeBillDetail(\LavasecoBundle\Entity\BillDetail $billDetail) {
        $this->billDetails->removeElement($billDetail);
    }

    /**
     * Get billDetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillDetails() {
        return $this->billDetails;
    }

    /**
     * Add billHistory
     *
     * @param \LavasecoBundle\Entity\BillHistory $billHistory
     *
     * @return Bill
     */
    public function addBillHistory(\LavasecoBundle\Entity\BillHistory $billHistory) {
        $this->billHistories[] = $billHistory;

        return $this;
    }

    /**
     * Remove billHistory
     *
     * @param \LavasecoBundle\Entity\BillHistory $billHistory
     */
    public function removeBillHistory(\LavasecoBundle\Entity\BillHistory $billHistory) {
        $this->billHistories->removeElement($billHistory);
    }

    /**
     * Get billHistories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillHistories() {
        return $this->billHistories;
    }

    /**
     * Set sellerUser
     *
     * @param \LavasecoBundle\Entity\User $sellerUser
     *
     * @return Bill
     */
    public function setSellerUser(\LavasecoBundle\Entity\User $sellerUser) {
        $this->sellerUser = $sellerUser;

        return $this;
    }

    /**
     * Get sellerUser
     *
     * @return \LavasecoBundle\Entity\User
     */
    public function getSellerUser() {
        return $this->sellerUser;
    }

    /**
     * Set customer
     *
     * @param \LavasecoBundle\Entity\Customer $customer
     *
     * @return Bill
     */
    public function setCustomer(\LavasecoBundle\Entity\Customer $customer = null) {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \LavasecoBundle\Entity\Customer
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * Set billState
     *
     * @param \LavasecoBundle\Entity\BillState $billState
     *
     * @return Bill
     */
    public function setBillState(\LavasecoBundle\Entity\BillState $billState) {
        $this->billState = $billState;

        return $this;
    }

    /**
     * Get billState
     *
     * @return \LavasecoBundle\Entity\BillState
     */
    public function getBillState() {
        return $this->billState;
    }

    /**
     * Set processState
     *
     * @param \LavasecoBundle\Entity\ProcessState $processState
     *
     * @return Bill
     */
    public function setProcessState(\LavasecoBundle\Entity\ProcessState $processState) {
        $this->processState = $processState;

        return $this;
    }

    /**
     * Get processState
     *
     * @return \LavasecoBundle\Entity\ProcessState
     */
    public function getProcessState() {
        return $this->processState;
    }

    /**
     * Set paymentAgreement
     *
     * @param \LavasecoBundle\Entity\PaymentAgreement $paymentAgreement
     *
     * @return Bill
     */
    public function setPaymentAgreement(\LavasecoBundle\Entity\PaymentAgreement $paymentAgreement) {
        $this->paymentAgreement = $paymentAgreement;

        return $this;
    }

    /**
     * Get paymentAgreement
     *
     * @return \LavasecoBundle\Entity\PaymentAgreement
     */
    public function getPaymentAgreement() {
        return $this->paymentAgreement;
    }

    /**
     * Set salePoint
     *
     * @param \LavasecoBundle\Entity\SalePoint $salePoint
     *
     * @return Bill
     */
    public function setSalePoint(\LavasecoBundle\Entity\SalePoint $salePoint = null) {
        $this->salePoint = $salePoint;

        return $this;
    }

    /**
     * Get salePoint
     *
     * @return \LavasecoBundle\Entity\SalePoint
     */
    public function getSalePoint() {
        return $this->salePoint;
    }

    public function getCreatedAtString() {
        return $this->createdAt->format('d/m/Y H:i');
    }

}
