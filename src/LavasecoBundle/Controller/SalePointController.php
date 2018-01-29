<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\SalePoint;
use LavasecoBundle\Form\SalePointType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalePointController extends Controller {

    public function indexAction($salePointId, Request $request) {
        $data = array();
        $configuration = $this->get('lavaseco.app_configuration');

        $salePoint = new SalePoint();

        if($salePointId != 0) {
            $salePoint = $this->getSalePointById($salePointId);
        }

        $branchOffices = $this->getBranchOffices();
        $form = $this->createForm(SalePointType::class, $salePoint, ['branchOffice' => $branchOffices]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->get('doctrine')->getManager();

                $salePoint = $form->getData();

                $em->persist($salePoint);
                $em->flush();

                $form = $this->createForm(SalePointType::class, new SalePoint(), ['branchOffice' => $branchOffices]);
                $data["messagePad"] = "Punto de venta " . $salePoint->getName() . " creado correctamente";
            } catch (\Doctrine\DBAL\DBALException $e) {
                $data["messagePad"] = "Punto de venta " . $salePoint->getName() . " no pudo ser creado, verifique que no exista un punto de venta con el mismo nombre";
                $data["errorPad"] = true;
            }
        }

        return $this->render($configuration->getViewTheme() . ':Settings/SalePoint/index.html.twig'
                        , ["form" => $form->createView(), "salePoints" => $this->getSalePoints()] + $data);
    }

    public function getSalePointAction(Request $request) {
        $salePoint = $this->getSalePointByDeviceId($request->request->get('device'));
        if (isset($salePoint)) {
            $this->get('session')->set('salePoint', $salePoint);
            $this->get('session')->set('brnachOfficeName', $salePoint->getBranchOfficeName());
        } else {
            $this->get('session')->remove('salePoint');
            $this->get('session')->remove('brnachOfficeName');
        }

        $this->get('session')->remove('getSalePoint');

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
            }
        }

        $this->get('session')->remove('newSalePoint');

        return $this->json($resutl);
    }

    private function getSalePointByActive($active) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findOneBy(array('isActive' => $active));
    }

    private function getSalePointById($salePointId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findOneById($salePointId);
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
        $data = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");
        $salePoints = $salePointRepository->findAll();

        foreach ($salePoints as $salePoint) {
            $data[] = [
                "id" => $salePoint->getId(),
                "name" => $salePoint->getName(),
                "branchOffice" => $salePoint->getBranchOfficeName(),
                "turn" => $salePoint->getTurn(),
                "isOpen" => $salePoint->getIsOpen() ? "Abierta" : "Cerrada",
                "isActive" => $salePoint->getIsActive() ? "Activa" : "Inactiva",
                "updateAt" => $salePoint->getUpdateAtString(),
                "createdAt" => $salePoint->getCreatedAtString(),
            ];
        }

        return $data;
    }

    private function getBranchOffices() {
        $result = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $branchOfficeRepository = $doctrineManager->getRepository("LavasecoBundle:BranchOffice");

        $branchsOffices = $branchOfficeRepository->findAll();

        foreach ($branchsOffices as $branchsOffice) {
            $result[$branchsOffice->getName()] = $branchsOffice;
        }

        return $result;
    }

}
