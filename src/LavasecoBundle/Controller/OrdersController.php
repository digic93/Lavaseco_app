<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\BillHistory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdersController extends Controller {

    public function indexAction($processId) {
        $data = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $configuration = $this->get('lavaseco.app_configuration');

        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");
        $processStateRepository = $doctrineManager->getRepository("LavasecoBundle:ProcessState");

        $data["processStates"] = $processStateRepository->getProces();

        if ($processId == 0 || $processId >= 7) {
            $bills = $billRepository->findUndelivered();
        } else {

            $bills = $billRepository->getBillsByProcessId($processId);
            $processState = $processStateRepository->find($processId);
            $data["processState"] = $processState;
        }
        $data["bills"] = $bills;

        return $this->render($configuration->getViewTheme() . ':Orders/index.html.twig', $data);
    }

    public function changeProcessStateAction(Request $request) {
        $billIds = $request->request->get('bills');
        $observation = $request->request->get('observation');
        $processStateId = $request->request->get('processState');

        $processState = $this->getProcessStateById($processStateId);

        $this->changeProcessState($billIds, $processState, $observation);

        return $this->json(["result" => true]);
    }

    private function getProcessStateById($processStateId) {
        $doctrineManager = $this->get('doctrine')->getManager();

        $processStateRepository = $doctrineManager->getRepository("LavasecoBundle:ProcessState");

        return $processStateRepository->find($processStateId);
    }

    private function changeProcessState($billIds, $processState, $observation) {
        $doctrineManager = $this->get('doctrine')->getManager();

        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        foreach ($billIds as $billId) {
            $bill = $billRepository->find($billId);
            $bill->setProcessState($processState);
            
            $doctrineManager->persist($bill);
            
            $this->notifications($bill);

            $this->saveBillHistory($bill, $processState, $observation);
        }
    }

    private function saveBillHistory($bill, $processState, $observation) {
        $doctrineManager = $this->get('doctrine')->getManager();

        $billHistory = new BillHistory();
        $billHistory->setBill($bill);
        $billHistory->setProcessState($processState);
        $billHistory->setUser($this->getUser());
        if ($observation != "") {
            $billHistory->setObservation($observation);
        }

        $doctrineManager->persist($billHistory);
        $doctrineManager->flush();
    }

    private function notifications($bill) {
        $customer = $bill->getCustomer();
        $processState = $bill->getProcessState()->getId();
        if ($customer && ( $processState == 4)) {
            if ($customer->getEmail()) {
                $this->notifyReadyDelivery($bill, $customer);
            }
        }
    }

    private function notifyReadyDelivery(\LavasecoBundle\Entity\Bill $bill, $customer) {
        if ($bill->getNotifyRedyDelivery()) {
            $configuration = $this->get('lavaseco.app_configuration');
            $billId = $bill->getSalePoint()->getId() . "-" . $bill->getId();

            $message = (new \Swift_Message('Factura ' . $billId . ' lista para ser entregada'))
                    ->setFrom(['noreply@lavasecomodelo.com' => 'Lavaseco Modelo'])
                    ->setTo($customer->getEmail())
                    ->setBody(
                    $this->renderView(
                            $configuration->getViewTheme() . ':Emails/readySendEmail.html.twig', [
                        'billId' => $billId,
                        'customer' => $customer,
                        'bill' => $bill,
                            ]
                    ), 'text/html'
            );
            $this->get('mailer')->send($message);
        }
    }

}
