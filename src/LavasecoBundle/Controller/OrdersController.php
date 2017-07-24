<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersController extends Controller {

    public function indexAction() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $configuration = $this->get('lavaseco.app_configuration');

        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        $bills = $billRepository->getBillsInFirstState();
        
        return $this->render($configuration->getViewTheme() . ':Orders/index.html.twig'
                        , ["bills" => $bills]);
    }

}
