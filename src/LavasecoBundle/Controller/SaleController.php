<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SaleController extends Controller
{
    public function indexAction()
    {
        $doctrineManager = $this->get('doctrine')->getManager();
        $configuration = $this->get('lavaseco.app_configuration');
        
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        $billContent = $billContentRepository->find(1);
        
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->getFirstLevel();
        
        $paymentAgreementRepository = $doctrineManager->getRepository("LavasecoBundle:PaymentAgreement");
        $paymentAgreements = $paymentAgreementRepository->getAvailables();
        
        return $this->render($configuration->getViewTheme() . ':Sale/index.html.twig', 
                [
                    "billContent" => $billContent,
                    "serviceCategories" => $serviceCagories,
                    "paymentAgreements" => $paymentAgreements
                ]);
    }
}
