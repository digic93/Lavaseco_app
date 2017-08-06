<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\SalePoint;
use LavasecoBundle\Form\SalePointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalePointController extends Controller {

    public function indexAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        $salePoint = new SalePoint();

        $form = $this->createForm(SalePointType::class, $salePoint);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            
            $salePoint = $form->getData();
            
            $em->persist($salePoint);
            $em->flush();
            return $this->redirectToRoute('lavaseco_settign_sale_point');
        }

        return $this->render($configuration->getViewTheme() . ':Settings/SalePoint/index.html.twig', [
                    "form" => $form->createView(),
                    "salePoints" => $this->getSalePoints()
        ]);
    }

    public function getSalePointAction(Request $request) {
        $salePoint = $this->getSalePointByDeviceId($request->request->get('device'));

        $this->get('session')->set('salePoint', (isset($salePoint)) ? $salePoint : "-1");
        return $this->json(true);
    }

    public function registerAction(Request $request) {
        $resutl = array();
        $deviceId = $request->request->get('device');
        $salePoint = $this->getSalePointByDeviceId($deviceId);

        if ($salePoint) {
            $this->get('session')->set('salePoint', $salePoint);

            $resutl ["succes"] = false;
        } else {
            $salePoint = $this->getSalePointByActive(false);

            if ($salePoint) {
                $this->updateRegisterSalePoint($salePoint, $deviceId);
                $resutl ["succes"] = true;
                $resutl ["name"] = $salePoint->getName();
                $resutl ["divace"] = $deviceId;

                $this->get('session')->set('salePoint', $salePoint);
            } else {
                $resutl ["succes"] = false;
                $this->get('session')->set('salePoint', "-1");
            }
        }
        return $this->json($resutl);
    }

    private function getSalePointByActive($active) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findOneBy(array('isActive' => $active));
    }

    private function getSalePointByDeviceId($deviceId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findOneBy(array('deviceId' => $deviceId));
    }

    private function updateRegisterSalePoint($salePoint, $deviceId) {
        $doctrineManager = $this->get('doctrine')->getManager();

        $salePoint->setIsActive(true);
        $salePoint->setDeviceId($deviceId);

        $doctrineManager->persist($salePoint);
        $doctrineManager->flush();
    }

    public function getSalePoints() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findAll();
    }

}
