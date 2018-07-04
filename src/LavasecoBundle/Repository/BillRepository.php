<?php

namespace LavasecoBundle\Repository;

/**
 * BillRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BillRepository extends \Doctrine\ORM\EntityRepository {

    public function getConsecutive() {
        $consecutive = 0;
        $bill = $this->createQueryBuilder('b')
                ->orderBy("b.consecutive", "DESC")
                ->getQuery()
                ->getResult();

        if ($bill) {
            $consecutive = $bill[0]->getConsecutive() + 1;
        } else {
            $consecutive = 1;
        }

        return $consecutive;
    }

    public function getBillsByProcessId($processId) {
        $bills = $this->createQueryBuilder('b')
                ->where('b.processState = :processStateId')
                ->setParameter('processStateId', $processId)
                ->getQuery();

        return $bills->getResult();
    }

    public function findDelivered($branchOfficeId = null, $limit = null, $movlie = false) {
        $bills = $this->createQueryBuilder('b');

        if ($branchOfficeId != null) {
            $bills = $bills->innerJoin('b.salePoint', 's')
                    ->innerJoin('s.branchOffice', 'bo')
                    ->where('b.processState = 7')
                    ->orWhere('b.billState = 3')
                    ->andWhere('bo.id = :branchOfficeId')
                    ->orderBy('b.id', 'DESC')
                    ->setParameter('branchOfficeId', $branchOfficeId);
            if ($movlie) {
                $bills = $bills->innerJoin('b.addressDelivery', 'ad')
                        ->innerJoin('b.addressCollect', 'ac')
                        ->andWhere('ad.id IS NOT NULL')
                        ->andWhere('ac.id IS NOT NULL');
            } else {
                $bills = $bills->leftJoin('b.addressDelivery', 'ad')
                        ->leftJoin('b.addressCollect', 'ac')
                        ->andWhere('ad.id IS NULL')
                        ->andWhere('ac.id IS NULL');
            }
        } else {
            $bills = $bills->where('b.processState = 7')
                    ->orWhere('b.billState = 3')
                    ->orderBy('b.id', 'DESC');
        }

        if ($limit != null) {
            $bills = $bills->setMaxResults($limit);
        }

        return $bills->getQuery()->getResult();
    }

    public function findUndelivered($branchOfficeId = null, $movlie = false) {
        $bills = $this->createQueryBuilder('b');

        if ($branchOfficeId != null) {
            $bills = $bills->innerJoin('b.salePoint', 's')
                    ->innerJoin('s.branchOffice', 'bo')
                    ->where('b.processState != 7')
                    ->andWhere('b.billState != 3')
                    ->andWhere('bo.id = :branchOfficeId')
                    ->orderBy('b.id', 'DESC')
                    ->setParameter('branchOfficeId', $branchOfficeId);
            if ($movlie) {
                $bills = $bills->innerJoin('b.addressDelivery', 'ad')
                        ->innerJoin('b.addressCollect', 'ac')
                        ->andWhere('ad.id IS NOT NULL')
                        ->andWhere('ac.id IS NOT NULL');
            } else {
                $bills = $bills->leftJoin('b.addressDelivery', 'ad')
                        ->leftJoin('b.addressCollect', 'ac')
                        ->andWhere('ad.id IS NULL')
                        ->andWhere('ac.id IS NULL');
            }
        } else {
            $bills = $bills->where('b.processState != 7')
                    ->andWhere('b.billState != 3')
                    ->orderBy('b.id', 'DESC');
        }
        return $bills->getQuery()->getResult();
    }

    public function getUnprintedBillAndTikets($salePoints) {
        $bills = $this->createQueryBuilder('b')
                ->where('b.printBill = true')
                ->andWhere('b.printedTiket = true')
                ->andWhere('b.salePoint in (:salePoints)')
                ->setParameter('salePoints', array_values($salePoints))
                ->getQuery();

        return $bills->getResult();
    }

    public function getUnprintedTikets($salePoints) {
        $bills = $this->createQueryBuilder('b')
                ->where('b.printBill = false')
                ->andWhere('b.printedTiket = true')
                ->andWhere('b.salePoint in (:salePoints)')
                ->setParameter('salePoints', array_values($salePoints))
                ->getQuery();

        return $bills->getResult();
    }

    public function saleDailyReport($from, $to, $salePoint = 0) {
        $procedure = "CALL saleDailyReport('" . $from->format('Y-m-d 00:00:00') . "', '" . $to->format('Y-m-d 23:59:59') . "'," . $salePoint . ")";
        $em = $this->getEntityManager()->getConnection();
        $sth = $em->prepare($procedure);
        $sth->execute();

        return $sth->fetchAll();
    }

    public function SalePointReport($from, $to, $salePoint = 0) {
        $procedure = "CALL salePointReport('" . $from->format('Y-m-d 00:00:00') . "', '" . $to->format('Y-m-d 23:59:59') . "'," . $salePoint . ")";
        $em = $this->getEntityManager()->getConnection();
        $sth = $em->prepare($procedure);
        $sth->execute();

        return $sth->fetchAll();
    }

}
