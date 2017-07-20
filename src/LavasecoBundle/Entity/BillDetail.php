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
     * @ORM\Column(name="observation", type="text")
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
     * @ORM\OneToMany(targetEntity="ObjectStateReceivedService", mappedBy="billDetail")
     */
    protected $objectStateReceivedService;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectStateReceivedService = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return BillDetail
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return BillDetail
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return BillDetail
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
     * Set bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return BillDetail
     */
    public function setBill(\LavasecoBundle\Entity\Bill $bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get bill
     *
     * @return \LavasecoBundle\Entity\Bill
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set service
     *
     * @param \LavasecoBundle\Entity\Service $service
     *
     * @return BillDetail
     */
    public function setService(\LavasecoBundle\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \LavasecoBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Add objectStateReceivedService
     *
     * @param \LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService
     *
     * @return BillDetail
     */
    public function addObjectStateReceivedService(\LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService)
    {
        $this->objectStateReceivedService[] = $objectStateReceivedService;

        return $this;
    }

    /**
     * Remove objectStateReceivedService
     *
     * @param \LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService
     */
    public function removeObjectStateReceivedService(\LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService)
    {
        $this->objectStateReceivedService->removeElement($objectStateReceivedService);
    }

    /**
     * Get objectStateReceivedService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectStateReceivedService()
    {
        return $this->objectStateReceivedService;
    }
}
