<?php

namespace LavasecoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StateObjectRecevidService
 *
 * @ORM\Table(name="state_object_recevid_service")
 * @ORM\Entity(repositoryClass="LavasecoBundle\Repository\StateObjectRecevidServiceRepository")
 */
class StateObjectRecevidService
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
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="stateObjectRecevidServices")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    protected $service;    

    /**
     * @ORM\ManyToOne(targetEntity="BillDetail", inversedBy="stateObjectRecevidServices")
     * @ORM\JoinColumn(name="bill_detail_id", referencedColumnName="id")
     */
    protected $billDetail;    

    /**
     * @ORM\ManyToOne(targetEntity="StateObjectDescription", inversedBy="stateObjectRecevidServices")
     * @ORM\JoinColumn(name="state_object_description_id", referencedColumnName="id")
     */
    protected $stateObjectDescription;    

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
     * Set service
     *
     * @param \LavasecoBundle\Entity\Service $service
     *
     * @return StateObjectRecevidService
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
     * Set billDetail
     *
     * @param \LavasecoBundle\Entity\BillDetail $billDetail
     *
     * @return StateObjectRecevidService
     */
    public function setBillDetail(\LavasecoBundle\Entity\BillDetail $billDetail = null)
    {
        $this->billDetail = $billDetail;

        return $this;
    }

    /**
     * Get billDetail
     *
     * @return \LavasecoBundle\Entity\BillDetail
     */
    public function getBillDetail()
    {
        return $this->billDetail;
    }

    /**
     * Set stateObjectDescription
     *
     * @param \LavasecoBundle\Entity\StateObjectDescription $stateObjectDescription
     *
     * @return StateObjectRecevidService
     */
    public function setStateObjectDescription(\LavasecoBundle\Entity\StateObjectDescription $stateObjectDescription = null)
    {
        $this->stateObjectDescription = $stateObjectDescription;

        return $this;
    }

    /**
     * Get stateObjectDescription
     *
     * @return \LavasecoBundle\Entity\StateObjectDescription
     */
    public function getStateObjectDescription()
    {
        return $this->stateObjectDescription;
    }
}
