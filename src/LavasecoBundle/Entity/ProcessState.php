<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProcessState
 *
 * @ORM\Table(name="process_state")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\ProcessStateRepository")
 */
class ProcessState {

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
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="processState")
     */
    protected $bills;

    /**
     * @ORM\OneToMany(targetEntity="BillHistory", mappedBy="processState")
     */
    protected $billHistories;

    /**
     * Constructor
     */
    public function __construct() {
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
     * Set name
     *
     * @param string $name
     *
     * @return ProcessState
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
     * Add billHistory
     *
     * @param \LavasecoBundle\Entity\BillHistory $billHistory
     *
     * @return ProcessState
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
     * Add bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return ProcessState
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
