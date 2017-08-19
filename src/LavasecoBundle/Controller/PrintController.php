<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PrintController extends Controller {

    public function unprintedAction(){
        $billsResult = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");
        
        $bills = $billRepository->getUnprinted();
        
        $billsResult["BillInfo"] = $this->getBillContent();
        
        foreach ($bills as $bill){
//            $bill = new \LavasecoBundle\Entity\Bill();
            $customer = $bill->getCustomer();
            
            $billsResult ["bills"][] = [
                    "id" =>$bill->getSalePoint()->getId() . "-" . $bill->getId(),
                    "customerName" => $customer->getName(),
                    "currentPoints" => $customer->getPoints(),
                    "billState" => $bill->getBillState()->getName(),
                    "paymentAgreement" => $bill->getPaymentAgreement()->getName(),
                    "pointsBySale" => $this->getPointsBySale($bill),
                    "discount" => $bill->getDiscount(),
                    "subTotal" => $bill->getTotal(),
                    "total" => $bill->getTotal(),
                    "observation" => $bill->getObservation(),
                    "billDetails" => $this->getBillDetails($bill),
                ];
        }
        
        return $this->json($billsResult);
    }
    
    
    private function getBillContent(){
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
    
    private function getPointsBySale($bill){
        return 500;
    }
    
    private function getBillDetails(\LavasecoBundle\Entity\Bill $bill){
        $billDetailsResult = array();
        
        $billDetails = $bill->getBillDetails();
        foreach ($billDetails as $billDetail){
            
            $billDetailsResult[] = [
                "serviceName" => $billDetail->getService()->getServiceCategory()->getFullName(),
                "quantity" => $billDetail->getQuantity(),
                "price" => $billDetail->getPrice(),
                "observations" => $billDetail->getObservation(),
                "Descriptors" => $this->getDescriptors($billDetail),
            ];
        }
        
        return $billDetailsResult;
    }
    
    private function getDescriptors(\LavasecoBundle\Entity\BillDetail $billDetail){
        $detalist = "";
        $objectStateReceivedServices = $billDetail->getObjectStateReceivedService();
    
        foreach ($objectStateReceivedServices as $objectStateReceivedService){
            $detalist .= $objectStateReceivedService->getStateObjectDescription()->getName() . " ";    
        }
        
        return $detalist;
    }
            
}
