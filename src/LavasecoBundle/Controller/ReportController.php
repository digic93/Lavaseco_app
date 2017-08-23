<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller {
    
    public function dashboardAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Reports/index.html.twig');
    }

    public function dailySaleAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Reports/dailySale.html.twig');
    }

    public function serviceSaleAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Reports/serviceSale.html.twig');
    }

    public function userSaleAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Reports/userSale.html.twig');
    }

    public function salePointAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Reports/salePoint.html.twig');
    }

}
