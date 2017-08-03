<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\SalePoint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    public function indexAction() {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $salePointSession = $this->get('session')->get('salePoint');

            if (!$salePointSession instanceof SalePoint && $salePointSession != "-1") {
                //falta validar si es administrador 
                //si exite algun ponto de venta por registrar
                $salePoint = $this->getSalePointByActive(false);

                $this->get('session')->set('salePoint', ($salePoint) ? "0" : "1");
            }
        }

        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':index.html.twig');
    }

    private function getSalePointByActive($active) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findOneBy(array('isActive' => $active));
    }

}
