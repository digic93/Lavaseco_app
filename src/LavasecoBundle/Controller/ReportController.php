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
        $date = new \DateTime(date('Y-m-d'));
        $configuration = $this->get('lavaseco.app_configuration');
        $bills = $this->getDailySale($date, $date, 0);

        return $this->render($configuration->getViewTheme() . ':Reports/dailySale.html.twig', [
                    "bills" => $bills,
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
        return $this->render($configuration->getViewTheme() . ':Reports/salePoint.html.twig');
    }

    public function getDailySaleAction(Request $request) {
        $salePoint = $request->request->get('salePoint');
        $startDate = $request->request->get('startDate');
        $finalDate = $request->request->get('finalDate');

        if ($startDate != "" && $finalDate != "") {
            $dateTo = $this->getLastMonday($startDate);
            $dateFrom = $this->getNextFriday($finalDate);
        } else {
            $dateTo = $this->getLastMonday(date('y-m-d'));
            $dateFrom = $this->getNextFriday(date('y-m-d'));
        }
        
        $bills = $this->getDailySale($dateTo, $dateFrom, $salePoint);
        
    }

    public function _____getDailySaleAction(Resquest $resquest) {
        $n = 0;
        $salePoints = array();
        $dataCancelado = array(0);
        $dataPendiente = array(0);
        $date = new \DateTime(date('Y-m-d'));

        $bills = $this->getDailySale($date, $date, 0);
        if ($bills) {
            $salePoint = $bills[0]->getSalePoint()->getId();
            $salePoints [] = $bills[0]->getSalePoint()->getName();
        }

        foreach ($bills as $bill) {
            if ($salePoint != $bill->getSalePoint()->getId()) {
                $salePoints [] = $bill->getSalePoint()->getName();
                $salePoint = $bill->getSalePoint()->getId();
                $dataCancelado [] = 0;
                $dataPendiente [] = 0;
                $n ++;
            }

            if ($bill->getPaymentAgreement()->getId() == 1) {//Anticipado
                $dataCancelado [$n] += $bill->getTotal();
            } else if ($bill->getPaymentAgreement()->getId() == 2) {//Contra entrega
                $dataPendiente [$n] += $bill->getTotal();
            } else if ($bill->getPaymentAgreement()->getId() == 3) {//abono
                $dataCancelado [$n] += $bill->getPayed();
                $dataPendiente [$n] += $bill->getTotal() - $bill->getPayed();
            }
        }

        return $this->json([
                    "categories" => $salePoints,
                    "series" => [
                        [
                            "name" => "Cancelado",
                            "data" => $dataCancelado,
                        ],
                        [
                            "name" => "Pendiente",
                            "data" => $dataPendiente,
                        ],
                    ]
        ]);
    }

    private function getAllSalePoints() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->findAll();
    }

    private function getDailySale($initialDate, $endDate, $salePoint) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->dailySale($initialDate, $endDate, $salePoint);
    }

    private function getLastMonday($date) {
        if (jddayofweek(strtotime($date))) {
            return new \DateTime(date('Y-m-d', strtotime($date . " last Monday")));
        } else {
            return new \DateTime(date('Y-m-d', strtotime("2017-2-6")));
        }
    }

    private function getNextFriday($date) {
        if (jddayofweek(strtotime($date)) != 3) {
            return new \DateTime(date('Y-m-d', strtotime("2017-2-6 next Friday")));
        } else {
            return new \DateTime(date('Y-m-d', strtotime("2017-2-6")));
        }        
    }

}
