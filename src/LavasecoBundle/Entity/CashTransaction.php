<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CashTransaction
 *
 * @ORM\Table(name="cash_transaction")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\CashTransactionRepository")
 */
class CashTransaction {

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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="turn", type="integer")
     */
    private $turn;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="cashTransactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",  nullable = false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="TypeTransaction", inversedBy="cashTransactions")
     * @ORM\JoinColumn(name="type_transaction_id", referencedColumnName="id",  nullable = false)
     */
    protected $typeTransaction;

    /**
     * @ORM\ManyToOne(targetEntity="SalePoint", inversedBy="cashTransactions")
     * @ORM\JoinColumn(name="sale_point_id", referencedColumnName="id",  nullable = false)
     */
    protected $salePoint;

    public function __construct() {
        $this->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
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
     * Set payment
     *
     * @param string $payment
     *
     * @return CashTransaction
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
     * Set description
     *
     * @param string $description
     *
     * @return CashTransaction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set turn
     *
     * @param integer $turn
     *
     * @return CashTransaction
     */
    public function setTurn($turn)
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * Get turn
     *
     * @return integer
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * Set user
     *
     * @param \LavasecoBundle\Entity\User $user
     *
     * @return CashTransaction
     */
    public function setUser(\LavasecoBundle\Entity\User $user)
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

    /**
     * Set typeTransaction
     *
     * @param \LavasecoBundle\Entity\TypeTransaction $typeTransaction
     *
     * @return CashTransaction
     */
    public function setTypeTransaction(\LavasecoBundle\Entity\TypeTransaction $typeTransaction)
    {
        $this->typeTransaction = $typeTransaction;

        return $this;
    }

    /**
     * Get typeTransaction
     *
     * @return \LavasecoBundle\Entity\TypeTransaction
     */
    public function getTypeTransaction()
    {
        return $this->typeTransaction;
    }

    /**
     * Set salePoint
     *
     * @param \LavasecoBundle\Entity\SalePoint $salePoint
     *
     * @return CashTransaction
     */
    public function setSalePoint(\LavasecoBundle\Entity\SalePoint $salePoint)
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
}
