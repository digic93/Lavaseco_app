<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attribute
 *
 * @ORM\Table(name="category_state_object")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\CategoryStateObjectRepository")
 */
class CategoryStateObject
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
     * @ORM\OneToMany(targetEntity="ServiceCategoryState", mappedBy="categoryStateObject")
     */
    protected $serviceCategoryStates;

    /**
     * @ORM\OneToMany(targetEntity="StateObjectDescription", mappedBy="categoryStateObject")
     */
    protected $stateObjectDescriptions;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviceCategoryStates = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoryStateObject
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
     * Add serviceCategoryState
     *
     * @param \LavasecoBundle\Entity\ServiceCategoryState $serviceCategoryState
     *
     * @return CategoryStateObject
     */
    public function addServiceCategoryState(\LavasecoBundle\Entity\ServiceCategoryState $serviceCategoryState)
    {
        $this->serviceCategoryStates[] = $serviceCategoryState;

        return $this;
    }

    /**
     * Remove serviceCategoryState
     *
     * @param \LavasecoBundle\Entity\ServiceCategoryState $serviceCategoryState
     */
    public function removeServiceCategoryState(\LavasecoBundle\Entity\ServiceCategoryState $serviceCategoryState)
    {
        $this->serviceCategoryStates->removeElement($serviceCategoryState);
    }

    /**
     * Get serviceCategoryStates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceCategoryStates()
    {
        return $this->serviceCategoryStates;
    }
}
