<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BillState
 *
 * @ORM\Table(name="bill_state")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BillStateRepository")
 */
class BillState
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
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="billState")
     */
    protected $bills;
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
     * Set name
     *
     * @param string $name
     *
     * @return BillState
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return BillState
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
}
