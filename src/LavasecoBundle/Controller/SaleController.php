<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\SalePoint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SaleController extends Controller {

    public function indexAction() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $configuration = $this->get('lavaseco.app_configuration');

        $salePoint = $this->getSalePoint();
        
        if($salePoint){
            if(!$salePoint->getIsOpen()){
                return $this->redirectToRoute('lavaseco_cash');
            } 
        }
        
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");
        $billContent = $billContentRepository->find(1);

        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->findBy(["id"=>2]);

        $paymentAgreementRepository = $doctrineManager->getRepository("LavasecoBundle:PaymentAgreement");
        $paymentAgreements = $paymentAgreementRepository->getAvailables();

        return $this->render($configuration->getViewTheme() . ':Sale/index.html.twig', [
                    "billContent" => $billContent,
                    "serviceCategories" => $serviceCagories,
                    "paymentAgreements" => $paymentAgreements,
                    "salePoint" => $salePoint
        ]);
    }

    private function getSalePoint() {
        $salePoint = $this->get('session')->get('salePoint');
        
        if($salePoint instanceof SalePoint){
            return ($salePoint->getIsActive())?$salePoint:null;
        }else{
            return null;
        }
    }
}
