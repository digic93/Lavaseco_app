<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller {

    public function indexAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        return $this->render($configuration->getViewTheme() . ':Client/Home/index.html.twig' );
    }
    
    public function loginAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        return $this->render($configuration->getViewTheme() . ':Client/index.html.twig' );
    }
    
    public function ordersAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        return $this->render($configuration->getViewTheme() . ':Client/Orders/index.html.twig' );
    }
    
    public function invoiceAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        return $this->render($configuration->getViewTheme() . ':Client/Invoice/index.html.twig' );
    }
    
}
