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
     * @ORM\OneToMany(targetEntity="ObjectStateReceivedService", mappedBy="stateObjectDescription")
     */
    protected $objectStateReceivedService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->objectStateReceivedService = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set categoryStateObject
     *
     * @param \LavasecoBundle\Entity\CategoryStateObject $categoryStateObject
     *
     * @return StateObjectDescription
     */
    public function setCategoryStateObject(\LavasecoBundle\Entity\CategoryStateObject $categoryStateObject)
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
     * Add objectStateReceivedService
     *
     * @param \LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService
     *
     * @return StateObjectDescription
     */
    public function addObjectStateReceivedService(\LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService)
    {
        $this->objectStateReceivedService[] = $objectStateReceivedService;

        return $this;
    }

    /**
     * Remove objectStateReceivedService
     *
     * @param \LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService
     */
    public function removeObjectStateReceivedService(\LavasecoBundle\Entity\ObjectStateReceivedService $objectStateReceivedService)
    {
        $this->objectStateReceivedService->removeElement($objectStateReceivedService);
    }

    /**
     * Get objectStateReceivedService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObjectStateReceivedService()
    {
        return $this->objectStateReceivedService;
    }
}
