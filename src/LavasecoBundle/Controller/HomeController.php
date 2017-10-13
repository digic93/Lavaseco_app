<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\SalePoint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    public function indexAction() {
        $securityContext = $this->container->get('security.authorization_checker');
        $configuration = $this->get('lavaseco.app_configuration');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $salePointSession = $this->get('session')->get('salePoint');

            if (!$salePointSession instanceof SalePoint && $salePointSession != "-1") {
                //falta validar si es administrador 
                //si exite algun ponto de venta por registrar
                $salePoint = $this->getSalePointByActive(false);

                $this->get('session')->set('salePoint', ($salePoint) ? "0" : "1");
            }
            return $this->render($configuration->getViewTheme() . ':Home/index.html.twig');

        }
        $corporation = $this->getCorporative();
        return $this->render($configuration->getViewTheme() . ':WebPage/index.html.twig', ['corporation' => $corporation]);
    }

    public function termsAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':WebPage/termOfUse.html.twig');
    }
    
    private function getSalePointByActive($active) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findOneBy(array('isActive' => $active));
    }
    
    private function getCorporative(){
        $doctrineManager = $this->get('doctrine')->getManager();
        $corporationRepository = $doctrineManager->getRepository("LavasecoBundle:Corporation");

        $corporation = $corporationRepository->find(1);
        
        return (isset($corporation))?$corporation:new \LavasecoBundle\Entity\Corporation();
    }
}
