<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PayMethod
 *
 * @ORM\Table(name="pay_method")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\PayMethodRepository")
 */
class PayMethod
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
     * @ORM\OneToMany(targetEntity="PayDetail", mappedBy="payMethod")
     */
    protected $payDetails;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payDetails = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PayMethod
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
     * Add payDetail
     *
     * @param \LavasecoBundle\Entity\PayDetail $payDetail
     *
     * @return PayMethod
     */
    public function addPayDetail(\LavasecoBundle\Entity\PayDetail $payDetail)
    {
        $this->payDetails[] = $payDetail;

        return $this;
    }

    /**
     * Remove payDetail
     *
     * @param \LavasecoBundle\Entity\PayDetail $payDetail
     */
    public function removePayDetail(\LavasecoBundle\Entity\PayDetail $payDetail)
    {
        $this->payDetails->removeElement($payDetail);
    }

    /**
     * Get payDetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayDetails()
    {
        return $this->payDetails;
    }
}
