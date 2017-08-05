<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\CashTransaction;
use LavasecoBundle\Form\CashTransactionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CashTransactionController extends Controller {

    public function indexAction(Request $request) {
        $salePoint = $this->getSalePoint();
        $configuration = $this->get('lavaseco.app_configuration');
        $description = (!$salePoint->getIsOpen()) ? "Apertura de caja" : "Cierre de caja";

        $cashTransaction = new CashTransaction();
        $cashTransaction->setPayment($this->calculateBalance($salePoint));
        $cashTransaction->setDescription($description);

        $form = $this->createForm(CashTransactionType::class, $cashTransaction);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cashTransaction = $form->getData();
            // se envia negado por que aun no se actualiza el punto de venta, y determnia el tipo de transaccion realizada
            $typeTransaction = $this->getTypeTransaction(!$salePoint->getIsOpen());
            $this->saveCashTransaction($cashTransaction, $salePoint, $description, $typeTransaction);
            $this->updateSalePoint($salePoint, $cashTransaction->getPayment());

            $this->get('session')->set('salePoint', $salePoint);

            return $this->redirectToRoute(($salePoint->getIsOpen()) ? 'lavaseco_sales' : 'lavaseco_homepage');
        }

        return $this->render($configuration->getViewTheme() . ':Sale/cash.html.twig', [
                    "form" => $form->createView(),
                    "editObservations" => true,
                    "title" => ($salePoint->getIsOpen()) ? "Cierre" : "Apertura",
                    "cashTransactions" => $this->getCashTransactions()   
        ]);
    }

    private function saveCashTransaction(&$cashTransaction, $salePoint, $description, $typeTransaction) {
        $em = $this->get('doctrine')->getManager();

        $cashTransaction->setTurn($salePoint->getTurn());
        $cashTransaction->setSalePoint($salePoint);
        $cashTransaction->setUser($this->getUser());
        $cashTransaction->setDescription($description);
        $cashTransaction->setTypeTransaction($typeTransaction);

        $em->persist($cashTransaction);
    }

    private function updateSalePoint(&$salePoint, $balance) {
        $em = $this->get('doctrine')->getManager();
        $salePoint->setIsOpen(!$salePoint->getIsOpen());
        $salePoint->setUpdatedAt();

        //si esta cerrada la caja
        if (!$salePoint->getIsOpen()) {
            $salePoint->setNextTurn();
            $salePoint->setBalance($balance);
        }

        $em->persist($salePoint);
        $em->flush();

        return $salePoint;
    }

    private function calculateBalance($salePoint) {
        if ($salePoint->getIsOpen()) {
            $balance = 200;
        } else {
            $balance = $salePoint->getBalance();
        }
        return $balance;
    }

    private function getSalePoint() {
        $salePoint = $this->get('session')->get('salePoint');
        $em = $this->get('doctrine')->getManager();

        $salePointRepository = $em->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->find($salePoint->getId());
    }

    private function getTypeTransaction($salePointIsOpen = null) {
        $typeTransactionId = 4; //si no hay  $salePointIsOpen es un egreso ( id = 4)
        $em = $this->get('doctrine')->getManager();
        $typeTransactionRepository = $em->getRepository("LavasecoBundle:TypeTransaction");

        if ($salePointIsOpen != null) {
            $typeTransactionId = ($salePointIsOpen) ? 1 : 2;
        }

        return $typeTransactionRepository->find($typeTransactionId);
    }
    
    private function getCashTransactions(){
        $salePoint = $this->get('session')->get('salePoint');
        $em = $this->get('doctrine')->getManager();

        $cashTransactionRepository = $em->getRepository("LavasecoBundle:CashTransaction");

        return $cashTransactionRepository->findByTurn($salePoint->getTurn());
    }

}
