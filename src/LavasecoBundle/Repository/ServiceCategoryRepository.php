<?php

namespace LavasecoBundle\Repository;

/**
 * ServiceCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServiceCategoryRepository extends \Doctrine\ORM\EntityRepository {

    public function getFirstLevel() {
        $query = $this->createQueryBuilder('sc')
                ->where('sc.serviceCategory IS NULL')
                ->getQuery();

        return $query->getResult();
    }

    public function getSubserviceCategoriesByServiceCategoryId($serviceCategoryId) {
        $query = $this->createQueryBuilder('sc')
                ->where('sc.serviceCategory = :serviceCategoryId')
                ->setParameter('serviceCategoryId', $serviceCategoryId)
                ->getQuery();

        return $query->getResult();
    }

    public function getInventory() {
        $query = " SELECT 
                        b.id factura,
                        sc.name servicio,
                        bd.quantity cantidad,
                        bd.observation observacion,
                        p.name proceso,
                        bs.name estado,
                        c.name cliente,
                        b.created_at fecha
                    FROM 
                        bill b,
                        bill_detail bd,
                        service s,
                        service_category sc,
                        customer c,
                        process_state p,
                        bill_state bs
                    where 
                        b.id = bd.bill_id
                    and s.id = bd.service_id
                    and s.service_category_id = sc.id
                    and b.process_state_id = p.id
                    and b.bill_state_id = bs.id
                    and b.customer_id =  c.id
                    and bs.id = 1  
                    and p.id != 7 
                    union
                    SELECT 
                        b.id factura,
                        sc.name servicio,
                        bd.quantity cantidad,
                        bd.observation observacion,
                        p.name proceso,
                        bs.name estado,
                        '' cliente,
                        b.created_at fecha
                    FROM 
                        bill b,
                        bill_detail bd,
                        service s,
                        service_category sc,
                        process_state p,
                        bill_state bs
                    where 
                            b.id = bd.bill_id
                    and s.id = bd.service_id
                    and s.service_category_id = sc.id
                    and b.process_state_id = p.id
                    and b.bill_state_id = bs.id
                    and b.customer_id is null
                    and bs.id = 1
                    and p.id != 7";

        return $this->getEntityManager()->getConnection()->executeQuery($query)->fetchAll();
    }

}
