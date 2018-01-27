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
             
            //si esta cargado el punto de venta y es administrador 
            if (!$salePointSession instanceof SalePoint && $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                
                //si exite algun ponto de venta por registrar
                $salePoint = $this->getSalePointByActive(false);
                
                if($salePoint){
                    $this->get('session')->set('newSalePoint', true);
                }
            }
            
            return $this->render($configuration->getViewTheme() . ':Home/index.html.twig');

        }else{
                $this->get('session')->set('getSalePoint', true);
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
