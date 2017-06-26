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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="CategoryStateObject", inversedBy="stateObjectDescriptions")
     * @ORM\JoinColumn(name="category_state_object_id", referencedColumnName="id")
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
}

