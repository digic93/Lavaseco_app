<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeTransaction
 *
 * @ORM\Table(name="type_transaction")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\TypeTransactionRepository")
 */
class TypeTransaction
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
     * @ORM\OneToMany(targetEntity="CashTransaction", mappedBy="typeTransaction")
     */
    protected $cashTransactions;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return TypeTransaction
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
     * Constructor
     */
    public function __construct()
    {
        $this->cashTransactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cashTransaction
     *
     * @param \LavasecoBundle\Entity\CashTransaction $cashTransaction
     *
     * @return TypeTransaction
     */
    public function addCashTransaction(\LavasecoBundle\Entity\CashTransaction $cashTransaction)
    {
        $this->cashTransactions[] = $cashTransaction;

        return $this;
    }

    /**
     * Remove cashTransaction
     *
     * @param \LavasecoBundle\Entity\CashTransaction $cashTransaction
     */
    public function removeCashTransaction(\LavasecoBundle\Entity\CashTransaction $cashTransaction)
    {
        $this->cashTransactions->removeElement($cashTransaction);
    }

    /**
     * Get cashTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCashTransactions()
    {
        return $this->cashTransactions;
    }
}
