<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrintController extends Controller {

    public function pirntBillAction(Request $request){
        $bill = $this->getBillById($request->request->get('bill'));

        if (!$bill) {
            throw new NotFoundHttpException('Factura no encontrada!');
        }

        $bill->setPrintedTiket(true);

        $em = $this->get('doctrine')->getManager();
        $em->persist($bill);
        $em->flush();

        return $this->json([true]);
    }
    
    public function unprintedAction() {
        $billsResult = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        $billsTikets = $billRepository->getUnprintedTikets();
        $bills = $billRepository->getUnprintedBillAndTikets();

        $billsResult["billInfo"] = $this->getBillContent();
        
        foreach ($bills as $bill) {
            $billsResult ["bills"][] = $this->getBillArray($bill);

            $bill->setPrintBill(false);
            $bill->setPrintedTiket(false);

            $doctrineManager->persist($bill);
            $doctrineManager->flush();
        }
        
        foreach ($billsTikets as $bill) {
            $billsResult ["billsTikes"][] = $this->getBillArray($bill);

            $bill->setPrintedTiket(false);

            $doctrineManager->persist($bill);
            $doctrineManager->flush();
        }

        return $this->json($billsResult);
    }

    private function getBillContent() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        $billContent = $billContentRepository->find(1);

        return [
            "companyName" => $billContent->getCompanyName(),
            "fiscalId" => $billContent->getFiscalId(),
            "address" => $billContent->getAddress(),
            "head" => $billContent->getHead(),
            "foot" => $billContent->getFoot(),
        ];
    }

    private function getPointsBySale(\LavasecoBundle\Entity\Bill $bill) {
        $bonification = 0;
        if ($bill->getDiscount() == 0) {
            $doctrineManager = $this->get('doctrine')->getManager();
            $loyaltyRepository = $doctrineManager->getRepository("LavasecoBundle:Loyalty");

            $loyalty = $loyaltyRepository->find(1);

            if ($bill->getPaymentAgreement()->getId() == 1) {
                $bonification = $loyalty->getAdvancePercent();
            }

            if ($bill->getPaymentAgreement()->getId() == 2) {
                $bonification = $loyalty->getUponDeliveryPercent();
            }

            if ($bill->getPaymentAgreement()->getId() == 3) {
                $bonification = $loyalty->getDepositPercent();
            }
        } 
        return $bonification * $bill->getTotal() / 100 ;
    }

    private function getBillDetails(\LavasecoBundle\Entity\Bill $bill) {
        $billDetailsResult = array();

        $billDetails = $bill->getBillDetails();
        foreach ($billDetails as $billDetail) {

            $billDetailsResult[] = [
                "serviceName" => $billDetail->getService()->getServiceCategory()->getNameToBill(),
                "quantity" => $billDetail->getQuantity(),
                "price" => $billDetail->getPrice(),
                "observations" => $billDetail->getObservation(),
                "Descriptors" => $this->getDescriptors($billDetail),
            ];
        }

        return $billDetailsResult;
    }

    private function getDescriptors(\LavasecoBundle\Entity\BillDetail $billDetail) {
        $detalist = "";
        $objectStateReceivedServices = $billDetail->getObjectStateReceivedService();

        foreach ($objectStateReceivedServices as $objectStateReceivedService) {
            $detalist .= $objectStateReceivedService->getStateObjectDescription()->getName() . " ";
        }

        return $detalist;
    }

    private function getTiketByBill($bill) {
        return [
            "bill" => $bill->getId(),
            "consecutive" => $bill->getConsecutive(),
            "seller" => $bill->getSellerUser()->getName(),
            "customer" => ($bill->getCustomer()) ? $bill->getCustomer()->getName() : "No se Registro Cliente",
            "phoneNumber" => ($bill->getCustomer()) ? $bill->getCustomer()->getPhoneNumber() : "No se Registro Telefono",
            "observation" => $bill->getObservation(),
            "createdAt" => $bill->getCreatedAtString(),
            "billDetail" => $this->getBillDetails($bill)
        ];
    }

    private function getBillArray(\LavasecoBundle\Entity\Bill $bill) {
        $customer = $bill->getCustomer();

        return [
            "id" => $bill->getSalePoint()->getId() . "-" . $bill->getId(),
            "customerName" => ($customer == null)?"":$customer->getName(),
            "customerPhone" => ($customer == null)?"":(($customer->getPhoneNumber() == null)?"No Registra":$customer->getPhoneNumber()),
            "currentPoints" => ($customer == null)?0:$customer->getPoints(),
            "billState" => $bill->getBillState()->getName(),
            "paymentAgreement" => $bill->getPaymentAgreement()->getName(),
            "pointsBySale" => $this->getPointsBySale($bill),
            "discount" => $bill->getDiscount(),
            "subTotal" => $bill->getTotalServices(),
            "total" => $bill->getTotal(),
            "observation" => $bill->getObservation(),
            "billDetails" => $this->getBillDetails($bill),
            "seller" => $bill->getSellerUser()->getName(),
            "date" => $bill->getCreatedAtString(),
            "salePoint" => $bill->getSalePoint()->getName(),
            "tikets" => $this->getTiketByBill($bill)
        ];
    }
    
    private function getBillById($billId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->find($billId);
    }

}
