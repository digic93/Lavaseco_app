<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PayDetail
 *
 * @ORM\Table(name="pay_detail")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\PayDetailRepository")
 */
class PayDetail
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
     * @ORM\Column(name="payment", type="decimal", precision=10, scale=0)
     */
    private $payment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    
    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="payDetails")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id")
     */
    protected $bill;
    
    /**
     * @ORM\ManyToOne(targetEntity="PayMethod", inversedBy="payDetails")
     * @ORM\JoinColumn(name="pay_method_id", referencedColumnName="id")
     */
    protected $payMethod;
 

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
     * Set payment
     *
     * @param string $payment
     *
     * @return PayDetail
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return string
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PayDetail
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return PayDetail
     */
    public function setBill(\LavasecoBundle\Entity\Bill $bill = null)
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
     * Set payMethod
     *
     * @param \LavasecoBundle\Entity\PayMethod $payMethod
     *
     * @return PayDetail
     */
    public function setPayMethod(\LavasecoBundle\Entity\PayMethod $payMethod = null)
    {
        $this->payMethod = $payMethod;

        return $this;
    }

    /**
     * Get payMethod
     *
     * @return \LavasecoBundle\Entity\PayMethod
     */
    public function getPayMethod()
    {
        return $this->payMethod;
    }
}
