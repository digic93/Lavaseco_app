<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BranchOffice
 *
 * @ORM\Table(name="branch_office")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BranchOfficeRepository")
 */
class BranchOffice {

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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=50, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_Number", type="string", length=50, nullable=true)
     */
    private $phoneNumber;
    
     /**
     * @ORM\ManyToOne(targetEntity="BillContent", inversedBy="branchOffices")
     * @ORM\JoinColumn(name="bill_content_id", referencedColumnName="id",  nullable = false)
     */
    protected $billContent;
        

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Customer
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
     * Set address
     *
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Customer
     */
    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }
    
    /**
     * Set billContent
     *
     * @param \LavasecoBundle\Entity\BillContent $billContent
     *
     * @return BranchOffice
     */
    public function setBillContent(\LavasecoBundle\Entity\BillContent $billContent) {
        $this->billContent = $billContent;

        return $this;
    }

    /**
     * Get billContent
     *
     * @return \LavasecoBundle\Entity\BillContent
     */
    public function getBillContent() {
        return $this->billContent;
    }

}
