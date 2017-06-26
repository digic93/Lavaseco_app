<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\ServiceRepository")
 */
class Service
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
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=80)
     */
    private $img;
    
    /**
     * @ORM\ManyToOne(targetEntity="ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="service_category_id", referencedColumnName="id")
     */
    protected $serviceCategory;
    
    /**
     * @ORM\OneToMany(targetEntity="BillDetail", mappedBy="service")
     */
    protected $billDetails;
    
    /**
     * @ORM\OneToMany(targetEntity="ServiceAttribute", mappedBy="service")
     */
    protected $serviceAttributes;
    
    
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
     * @return Service
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
     * Set price
     *
     * @param string $price
     *
     * @return Service
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
     * Set description
     *
     * @param string $description
     *
     * @return Service
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
     * Set img
     *
     * @param string $img
     *
     * @return Service
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set serviceCategory
     *
     * @param \LavasecoBundle\Entity\ServiceCategory $serviceCategory
     *
     * @return Service
     */
    public function setServiceCategory(\LavasecoBundle\Entity\ServiceCategory $serviceCategory = null)
    {
        $this->serviceCategory = $serviceCategory;

        return $this;
    }

    /**
     * Get serviceCategory
     *
     * @return \LavasecoBundle\Entity\ServiceCategory
     */
    public function getServiceCategory()
    {
        return $this->serviceCategory;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billDetails = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add billDetail
     *
     * @param \LavasecoBundle\Entity\BillDetail $billDetail
     *
     * @return Service
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

    /**
     * Add serviceAttribute
     *
     * @param \LavasecoBundle\Entity\ServiceAttribute $serviceAttribute
     *
     * @return Service
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
}
