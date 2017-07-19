<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BillDetail
 *
 * @ORM\Table(name="bill_detail")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BillDetailRepository")
 */
class BillDetail {

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
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=0, scale=0)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $observation;

    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="billDetails")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id", nullable=false)
     */
    protected $bill;

    /**
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="billDetails")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false)
     */
    protected $service;

    /**
     * @ORM\OneToMany(targetEntity="StateObjectRecevidService", mappedBy="billDetail")
     */
    protected $stateObjectRecevidServices;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return BillDetail
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return BillDetail
     */
    public function setPrice($price) {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return BillDetail
     */
    public function setBill(\LavasecoBundle\Entity\Bill $bill = null) {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get bill
     *
     * @return \LavasecoBundle\Entity\Bill
     */
    public function getBill() {
        return $this->bill;
    }

    /**
     * Set service
     *
     * @param \LavasecoBundle\Entity\Service $service
     *
     * @return BillDetail
     */
    public function setService(\LavasecoBundle\Entity\Service $service = null) {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \LavasecoBundle\Entity\Service
     */
    public function getService() {
        return $this->service;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->stateObjectRecevidServices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add stateObjectRecevidService
     *
     * @param \LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService
     *
     * @return BillDetail
     */
    public function addStateObjectRecevidService(\LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService) {
        $this->stateObjectRecevidServices[] = $stateObjectRecevidService;

        return $this;
    }

    /**
     * Remove stateObjectRecevidService
     *
     * @param \LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService
     */
    public function removeStateObjectRecevidService(\LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService) {
        $this->stateObjectRecevidServices->removeElement($stateObjectRecevidService);
    }

    /**
     * Get stateObjectRecevidServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStateObjectRecevidServices() {
        return $this->stateObjectRecevidServices;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return BillDetail
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

}
