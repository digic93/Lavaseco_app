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
}

