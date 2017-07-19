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
     * @ORM\Column(name="price", type="decimal", precision=10, scale=0)
     */
    private $price;
      
    /**
     * @ORM\ManyToOne(targetEntity="ServiceCategory", inversedBy="services")
     * @ORM\JoinColumn(name="service_category_id", referencedColumnName="id", nullable=false, unique=true)
     */
    protected $serviceCategory;
    
    /**
     * @ORM\OneToMany(targetEntity="BillDetail", mappedBy="service")
     */
    protected $billDetails;
    
    /**
     * @ORM\OneToMany(targetEntity="ServiceCategoryState", mappedBy="service")
     */
    protected $serviceDescriptors;

    /**
     * @ORM\OneToMany(targetEntity="StateObjectRecevidService", mappedBy="service")
     */
    protected $stateObjectRecevidServices;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billDetails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviceDescriptors = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \LavasecoBundle\Entity\ServiceCategoryState $serviceAttribute
     *
     * @return Service
     */
    public function addServiceDescriptors(\LavasecoBundle\Entity\ServiceCategoryState $serviceAttribute)
    {
        $this->serviceDescriptors[] = $serviceAttribute;

        return $this;
    }

    /**
     * Remove serviceAttribute
     *
     * @param \LavasecoBundle\Entity\ServiceCategoryState $serviceAttribute
     */
    public function removeServiceDescriptors(\LavasecoBundle\Entity\ServiceCategoryState $serviceAttribute)
    {
        $this->serviceDescriptors->removeElement($serviceAttribute);
    }

    /**
     * Get serviceAttributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceDescriptors()
    {
        return $this->serviceDescriptors;
    }

    /**
     * Add stateObjectRecevidService
     *
     * @param \LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService
     *
     * @return Service
     */
    public function addStateObjectRecevidService(\LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService)
    {
        $this->stateObjectRecevidServices[] = $stateObjectRecevidService;

        return $this;
    }

    /**
     * Remove stateObjectRecevidService
     *
     * @param \LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService
     */
    public function removeStateObjectRecevidService(\LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService)
    {
        $this->stateObjectRecevidServices->removeElement($stateObjectRecevidService);
    }

    /**
     * Get stateObjectRecevidServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStateObjectRecevidServices()
    {
        return $this->stateObjectRecevidServices;
    }

    /**
     * Add serviceDescriptor
     *
     * @param \LavasecoBundle\Entity\ServiceCategoryState $serviceDescriptor
     *
     * @return Service
     */
    public function addServiceDescriptor(\LavasecoBundle\Entity\ServiceCategoryState $serviceDescriptor)
    {
        $this->serviceDescriptors[] = $serviceDescriptor;

        return $this;
    }

    /**
     * Remove serviceDescriptor
     *
     * @param \LavasecoBundle\Entity\ServiceCategoryState $serviceDescriptor
     */
    public function removeServiceDescriptor(\LavasecoBundle\Entity\ServiceCategoryState $serviceDescriptor)
    {
        $this->serviceDescriptors->removeElement($serviceDescriptor);
    }
}
