<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BillHistory
 *
 * @ORM\Table(name="bill_history")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BillHistoryRepository")
 */
class BillHistory
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
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="billHistories")
     * @ORM\JoinColumn(name="bill_id", referencedColumnName="id")
     */
    protected $bill;

    /**
     * @ORM\ManyToOne(targetEntity="ProcessState", inversedBy="billHistories")
     * @ORM\JoinColumn(name="process_state_id", referencedColumnName="id")
     */
    protected $processState;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="billHistories")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return BillHistory
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set bill
     *
     * @param \LavasecoBundle\Entity\Bill $bill
     *
     * @return BillHistory
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
     * Set processState
     *
     * @param \LavasecoBundle\Entity\ProcessState $processState
     *
     * @return BillHistory
     */
    public function setProcessState(\LavasecoBundle\Entity\ProcessState $processState = null)
    {
        $this->processState = $processState;

        return $this;
    }

    /**
     * Get processState
     *
     * @return \LavasecoBundle\Entity\ProcessState
     */
    public function getProcessState()
    {
        return $this->processState;
    }

    /**
     * Set user
     *
     * @param \LavasecoBundle\Entity\User $user
     *
     * @return BillHistory
     */
    public function setUser(\LavasecoBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \LavasecoBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
