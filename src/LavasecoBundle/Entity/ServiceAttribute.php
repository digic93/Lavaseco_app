<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceAttribute
 *
 * @ORM\Table(name="service_attribute")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\ServiceAttributeRepository")
 */
class ServiceAttribute
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
     * @ORM\Column(name="price", type="decimal", precision=0, scale=0)
     */
    private $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="serviceAttributes")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    protected $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="Attribute", inversedBy="serviceAttributes")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    protected $attribute;
    
    
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
     * @return ServiceAttribute
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
     * @return ServiceAttribute
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
     * Set service
     *
     * @param \LavasecoBundle\Entity\Service $service
     *
     * @return ServiceAttribute
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
     * Set attribute
     *
     * @param \LavasecoBundle\Entity\Attribute $attribute
     *
     * @return ServiceAttribute
     */
    public function setAttribute(\LavasecoBundle\Entity\Attribute $attribute = null)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute
     *
     * @return \LavasecoBundle\Entity\Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
}
