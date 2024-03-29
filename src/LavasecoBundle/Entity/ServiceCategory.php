<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceCategory
 *
 * @ORM\Table(name="service_category")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\ServiceCategoryRepository")
 */
class ServiceCategory {

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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=80, nullable=true)
     */
    private $img;

    /**
     * @ORM\OneToMany(targetEntity="Service", mappedBy="serviceCategory")
     */
    protected $services;

    /**
     * @ORM\OneToMany(targetEntity="ServiceCategory", mappedBy="serviceCategory")
     */
    protected $subServiceCategories;

    /**
     * @ORM\ManyToOne(targetEntity="ServiceCategory", inversedBy="subServiceCategories")
     * @ORM\JoinColumn(name="service_category_id", referencedColumnName="id")
     */
    protected $serviceCategory;

    /**
     * Constructor
     */
    public function __construct() {
        $this->img = "/CategoryIcons/default.png";
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subServiceCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ServiceCategory
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ServiceCategory
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return ServiceCategory
     */
    public function setImg($img) {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg() {
        return $this->img;
    }

    /**
     * Add service
     *
     * @param \LavasecoBundle\Entity\Service $service
     *
     * @return ServiceCategory
     */
    public function addService(\LavasecoBundle\Entity\Service $service) {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \LavasecoBundle\Entity\Service $service
     */
    public function removeService(\LavasecoBundle\Entity\Service $service) {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices() {
        return $this->services;
    }

    /**
     * Add subServiceCategory
     *
     * @param \LavasecoBundle\Entity\ServiceCategory $subServiceCategory
     *
     * @return ServiceCategory
     */
    public function addSubServiceCategory(\LavasecoBundle\Entity\ServiceCategory $subServiceCategory) {
        $this->subServiceCategories[] = $subServiceCategory;

        return $this;
    }

    /**
     * Remove subServiceCategory
     *
     * @param \LavasecoBundle\Entity\ServiceCategory $subServiceCategory
     */
    public function removeSubServiceCategory(\LavasecoBundle\Entity\ServiceCategory $subServiceCategory) {
        $this->subServiceCategories->removeElement($subServiceCategory);
    }

    /**
     * Get subServiceCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubServiceCategories() {
        return $this->subServiceCategories;
    }

    /**
     * Set serviceCategory
     *
     * @param \LavasecoBundle\Entity\ServiceCategory $serviceCategory
     *
     * @return ServiceCategory
     */
    public function setServiceCategory(\LavasecoBundle\Entity\ServiceCategory $serviceCategory = null) {
        $this->serviceCategory = $serviceCategory;

        return $this;
    }

    /**
     * Get serviceCategory
     *
     * @return \LavasecoBundle\Entity\ServiceCategory
     */
    public function getServiceCategory() {
        return $this->serviceCategory;
    }

    public function getFullName() {
        if ($this->getServiceCategory()) {
            $superCategory = $this->getServiceCategory();
            if ($superCategory->getServiceCategory()) {
                return $superCategory->getServiceCategory()->getName() . " " .
                        $superCategory->getName() . ", " . $this->getName();
            } else {
                return $superCategory->getName() . " " . $this->getName();
            }
        } else {
            return $this->getName();
        }
    }

    public function getNameToBill() {
        if ($this->getServiceCategory()) {
            $superCategory = $this->getServiceCategory();
            return $superCategory->getName() . ", " . $this->getName();
        } else {
            return $this->getName();
        }
    }

}
