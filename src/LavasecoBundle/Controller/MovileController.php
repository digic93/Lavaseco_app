<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovileController extends Controller {

    public function indexAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        $billsUndelivered = $billRepository->getBillDelivery(); 
        $billPickUps = $billRepository->getBillPickUp(); 
        
        return $this->render($configuration->getViewTheme() . ':Movile/index.html.twig', 
                [
                    "billsUndelivered" => $billsUndelivered,
                    "billsPickUps" => $billPickUps
                ]);
    }
    
    public function chargerAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        $billsDelivered = $billRepository->getBillDelivered();         
        $billsCollected = $billRepository->getBillCollected(); 

        return $this->render($configuration->getViewTheme() . ':Movile/charger/index.html.twig', 
                [
                    "billsDelivered" => $billsDelivered,
                    "billsCollected" => $billsCollected
                ]);
    }
    
}
