<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller {

    public function dashboardAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Reports/index.html.twig');
    }

    public function dailySaleAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Reports/dailySale.html.twig', [
                    "salePoints" => $this->getAllSalePoints(),
        ]);
    }

    public function serviceSaleAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Reports/serviceSale.html.twig');
    }

    public function userSaleAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Reports/userSale.html.twig');
    }

    public function salePointAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Reports/salePoint.html.twig', [
                    "salePoints" => $this->getAllSalePoints(),
        ]);
    }

    public function getDailySaleAction(Request $request) {
        $salePoint = $request->request->get('salePoint');
        $startDate = $request->request->get('startDate');
        $finalDate = $request->request->get('finalDate');

        if ($startDate != "" && $finalDate != "") {
            $date = $dateTo = $this->getLastMonday($startDate);
            $dateFrom = $this->getNextSunday($finalDate);
        } else {
            $date = $dateTo = $this->getLastMonday(date('y-m-d'));
            $dateFrom = $this->getNextSunday(date('y-m-d'));
        }

        $report = $reportData = $this->getDailySale($dateTo, $dateFrom, $salePoint);

        $categories = array();
        $dataCancelado = array();
        $dataPendiente = array();
        do {
            $flag = true;
            $categories [] = $date->format('d/m/Y');
            foreach ($reportData as $key => $data) {
                if ($data["fecha"] == $date->format('d/m/Y')) {
                    $flag = false;
                    $dataCancelado[] = intval($data["cancelado"]);
                    $dataPendiente[] = intval($data["pendiente"]);
                    unset($reportData[$key]);
                }
            }

            if ($flag) {
                $dataCancelado[] = 0;
                $dataPendiente[] = 0;
            }

            $date = $dateTo->modify("+1 day");
        } while ($date != $dateFrom);

        $series = [
            [
                "name" => "Cancelado",
                "data" => $dataCancelado,
            ],
            [
                "name" => "Pendiente",
                "data" => $dataPendiente,
            ],
        ];

        $result = [
            "report" => $report,
            "chart" => ["categories" => $categories, "series" => $series]
        ];
        return $this->json($result);
    }

    public function getSalePointAction(Request $request) {
        $salePoint = $request->request->get('salePoint');
        $startDate = $request->request->get('startDate');
        $finalDate = $request->request->get('finalDate');

        if ($startDate != "" && $finalDate != "") {
            $dateTo = new \DateTime(date('Y-m-d', strtotime($startDate)));
            $dateFrom = new \DateTime(date('Y-m-d', strtotime($finalDate)));
        } else {
            $dateTo = new \DateTime(date('1-m-Y', strtotime('this month')));
            $dateFrom = new \DateTime(date('d-m-Y', strtotime('last day of this month')));
        }

        $report = $reportData = $this->getSalePointReport($dateTo, $dateFrom, $salePoint);
       
        $categories = array();
        $dataCancelado = array();
        $dataPendiente = array();
        $cancelado = $pendiente = 0;

        foreach ($report as $data) {
            $salePoint = $data["puntoVenta"];
            if (!in_array($salePoint, $categories)) {
                if(count($categories)){
                    $dataCancelado[] = $cancelado;  
                    $dataPendiente[] = $pendiente;  
                }
                $categories[] = $salePoint;
                $cancelado = $pendiente = 0;
            }
            $cancelado += $data["cancelado"];
            $pendiente += $data["pendiente"];
        }
        
        if(count($categories)){
            $dataCancelado[] = $cancelado;  
            $dataPendiente[] = $pendiente;  
        }
        
         $series = [
            [
                "name" => "Cancelado",
                "data" => $dataCancelado,
            ],
            [
                "name" => "Pendiente",
                "data" => $dataPendiente,
            ],
        ];

        
        $result = [
            "report" => $report,
            "chart" => ["categories" => $categories, "series" => $series]
        ];
        return $this->json($result);
    }

    private function getAllSalePoints() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findAll();
    }

    private function getDailySale($initialDate, $endDate, $salePoint) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->saleDailyReport($initialDate, $endDate, $salePoint);
    }

    private function getSalePointReport($initialDate, $endDate, $salePoint) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->SalePointReport($initialDate, $endDate, $salePoint);
    }

    private function getLastMonday($date) {
        $date = ($date instanceof \DateTime) ? $date->format('Y-m-d') : $date;
        if (jddayofweek(strtotime($date))) {
            return new \DateTime(date('Y-m-d', strtotime($date . " last Monday")));
        } else {
            return new \DateTime(date('Y-m-d', strtotime($date)));
        }
    }

    private function getNextSunday($date) {
        $date = ($date instanceof \DateTime) ? $date->format('Y-m-d') : $date;
        if (jddayofweek(strtotime($date)) != 1) {
            return new \DateTime(date('Y-m-d', strtotime($date . " next sunday")));
        } else {
            return new \DateTime(date('Y-m-d', strtotime($date)));
        }
    }

}
