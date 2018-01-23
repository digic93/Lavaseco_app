<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BillContent
 *
 * @ORM\Table(name="bill_content")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BillContentRepository")
 */
class BillContent
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
     * @ORM\Column(name="companyName", type="string", length=80)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="fiscalId", type="string", length=80)
     */
    private $fiscalId;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=80)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="head", type="text")
     */
    private $head;

    /**
     * @var string
     *
     * @ORM\Column(name="foot", type="text")
     */
    private $foot;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return BillContent
     */
    public function setCompanyName($companyName) {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName() {
        return $this->companyName;
    }

    /**
     * Set fiscalId
     *
     * @param string $fiscalId
     *
     * @return BillContent
     */
    public function setFiscalId($fiscalId) {
        $this->fiscalId = $fiscalId;

        return $this;
    }

    /**
     * Get fiscalId
     *
     * @return string
     */
    public function getFiscalId() {
        return $this->fiscalId;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return BillContent
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
     * Set head
     *
     * @param string $head
     *
     * @return BillContent
     */
    public function setHead($head) {
        $this->head = $head;

        return $this;
    }

    /**
     * Get head
     *
     * @return string
     */
    public function getHead() {
        return $this->head;
    }

    /**
     * Set foot
     *
     * @param string $foot
     *
     * @return BillContent
     */
    public function setFoot($foot) {
        $this->foot = $foot;

        return $this;
    }

    /**
     * Get foot
     *
     * @return string
     */
    public function getFoot() {
        return $this->foot;
    }

}
