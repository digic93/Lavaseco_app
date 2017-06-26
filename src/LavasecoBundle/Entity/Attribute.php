<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attribute
 *
 * @ORM\Table(name="attribute")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\AttributeRepository")
 */
class Attribute
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
     * @ORM\OneToMany(targetEntity="ServiceAttribute", mappedBy="attribute")
     */
    protected $serviceAttributes;

    /**
     * @ORM\OneToMany(targetEntity="BillDetail", mappedBy="attribute")
     */
    protected $billDetails;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviceAttributes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Attribute
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
     * Add serviceAttribute
     *
     * @param \LavasecoBundle\Entity\ServiceAttribute $serviceAttribute
     *
     * @return Attribute
     */
    public function addServiceAttribute(\LavasecoBundle\Entity\ServiceAttribute $serviceAttribute)
    {
        $this->serviceAttributes[] = $serviceAttribute;

        return $this;
    }

    /**
     * Remove serviceAttribute
     *
     * @param \LavasecoBundle\Entity\ServiceAttribute $serviceAttribute
     */
    public function removeServiceAttribute(\LavasecoBundle\Entity\ServiceAttribute $serviceAttribute)
    {
        $this->serviceAttributes->removeElement($serviceAttribute);
    }

    /**
     * Get serviceAttributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceAttributes()
    {
        return $this->serviceAttributes;
    }

    /**
     * Add billDetail
     *
     * @param \LavasecoBundle\Entity\BillDetail $billDetail
     *
     * @return Attribute
     */
    public function addBillDetail(\LavasecoBundle\Entity\BillDetail $billDetail)
    {
        $this->billDetails[] = $billDetail;

        return $this;
    }

    /**
     * Remove billDetail
     *
     * @param \LavasecoBundle\Entity\BillDetail $billDetail
     */
    public function removeBillDetail(\LavasecoBundle\Entity\BillDetail $billDetail)
    {
        $this->billDetails->removeElement($billDetail);
    }

    /**
     * Get billDetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillDetails()
    {
        return $this->billDetails;
    }
}
