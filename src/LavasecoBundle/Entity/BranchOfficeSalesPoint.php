<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BranchOfficeSalesPoint
 *
 * @ORM\Table(name="branch_office_sales_point")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\BranchOfficeSalesPointRepository")
 */
class BranchOfficeSalesPoint
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
     * @ORM\ManyToOne(targetEntity="BranchOffice", inversedBy="branchOffices")
     * @ORM\JoinColumn(name="branch_office_id", referencedColumnName="id", nullable=false)
     */
    protected $branchOffice;
    
    /**
     * @ORM\ManyToOne(targetEntity="SalePoint", inversedBy="salePoints")
     * @ORM\JoinColumn(name="sale_point_id", referencedColumnName="id", nullable=false)
     */
    protected $salePoint;

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
     * Set branchOffice
     *
     * @param \LavasecoBundle\Entity\BranchOffice $branchOffice
     *
     * @return BranchOffice
     */
    public function setBranchOffice(\LavasecoBundle\Entity\BranchOffice $branchOffice = null)
    {
        $this->branchOffice = $branchOffice;

        return $this;
    }

    /**
     * Get branchOffice
     *
     * @return \LavasecoBundle\Entity\BranchOffice
     */
    public function getBranchOffice()
    {
        return $this->branchOffice;
    }

    /**
     * Set salePoint
     *
     * @param \LavasecoBundle\Entity\SalePoint $salePoint
     *
     * @return SalePoint
     */
    public function setSalePoint(\LavasecoBundle\Entity\SalePoint $salePoint = null)
    {
        $this->salePoint = $salePoint;

        return $this;
    }

    /**
     * Get salePoint
     *
     * @return \LavasecoBundle\Entity\SalePoint
     */
    public function getSalePoint()
    {
        return $this->salePoint;
    }
}
