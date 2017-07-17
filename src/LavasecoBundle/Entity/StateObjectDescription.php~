<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StateObjectDescription
 *
 * @ORM\Table(name="state_object_description")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\StateObjectDescriptionRepository")
 */
class StateObjectDescription
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
     * @ORM\ManyToOne(targetEntity="CategoryStateObject", inversedBy="stateObjectDescriptions")
     * @ORM\JoinColumn(name="category_state_object_id", referencedColumnName="id", nullable=false)
     */
    protected $categoryStateObject;
    
    
    /**
     * @ORM\OneToMany(targetEntity="StateObjectRecevidService", mappedBy="stateObjectDescription")
     */
    protected $stateObjectRecevidServices;

    
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
     * @return StateObjectDescription
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
        $this->stateObjectRecevidServices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set categoryStateObject
     *
     * @param \LavasecoBundle\Entity\CategoryStateObject $categoryStateObject
     *
     * @return StateObjectDescription
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

    /**
     * Add stateObjectRecevidService
     *
     * @param \LavasecoBundle\Entity\StateObjectRecevidService $stateObjectRecevidService
     *
     * @return StateObjectDescription
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
}
