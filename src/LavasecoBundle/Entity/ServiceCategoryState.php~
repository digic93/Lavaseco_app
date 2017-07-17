<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceAttribute
 *
 * @ORM\Table(name="service_category_state")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\ServiceCategoryStateRepository")
 */
class ServiceCategoryState
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
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="serviceDescriptors")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false)
     */
    protected $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="CategoryStateObject", inversedBy="serviceCategoryStates")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id", nullable=false)
     */
    protected $categoryStateObject;

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
     * Set service
     *
     * @param \LavasecoBundle\Entity\Service $service
     *
     * @return ServiceCategoryState
     */
    public function setService(\LavasecoBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \LavasecoBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set categoryStateObject
     *
     * @param \LavasecoBundle\Entity\CategoryStateObject $categoryStateObject
     *
     * @return ServiceCategoryState
     */
    public function setCategoryStateObject(\LavasecoBundle\Entity\CategoryStateObject $categoryStateObject = null)
    {
        $this->categoryStateObject = $categoryStateObject;

        return $this;
    }

    /**
     * Get categoryStateObject
     *
     * @return \LavasecoBundle\Entity\CategoryStateObject
     */
    public function getCategoryStateObject()
    {
        return $this->categoryStateObject;
    }
}
