<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersController extends Controller {

    public function indexAction($processId) {
        $data = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $configuration = $this->get('lavaseco.app_configuration');

        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");
        $processStateRepository = $doctrineManager->getRepository("LavasecoBundle:ProcessState");

        $data["processStates"] = $processStateRepository->findAll();
        
        if ($processId == 0) {
            $bills = $billRepository->findAll();
        } else {

            $bills = $billRepository->getBillsByProcessId($processId);
            $processState = $processStateRepository->find($processId);
            $data["processState"] = $processState;
        }
        $data["bills"] = $bills;

        return $this->render($configuration->getViewTheme() . ':Orders/index.html.twig', $data);
    }

}
