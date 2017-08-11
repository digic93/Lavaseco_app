<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Bill;
use LavasecoBundle\Entity\PayDetail;
use LavasecoBundle\Entity\BillDetail;
use LavasecoBundle\Entity\BillHistory;
use LavasecoBundle\Form\BillContentType;
use LavasecoBundle\Entity\CashTransaction;
use LavasecoBundle\Entity\ObjectStateReceivedService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BillController extends Controller {

    public function indexAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        $billsDelivered = $billRepository->findDelivered();
        $billsUndelivered = $billRepository->findUndelivered();

        return $this->render($configuration->getViewTheme() . ':Bill/index.html.twig', [
                    "billsDelivered" => $billsDelivered,
                    "billsUndelivered" => $billsUndelivered,
        ]);
    }

    public function settingAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        $billContent = $this->getBillContentById(1);

        $form = $this->createForm(BillContentType::class, $billContent);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $billContent = $form->getData();

            $em->persist($billContent);

            $em->flush();
        }

        return $this->render($configuration->getViewTheme() . ':Settings/Bill/index.html.twig', [
                    'form' => $form->createView(),
                    'billContent' => $billContent,
        ]);
    }

    public function saveBillingAction(Request $request) {
        $payment = $request->request->get("total");
        $payMethodId = 1; //$request->request->get("paymenthod");
        $observation = $request->request->get("observations");
        $customerId = $request->request->get("customerId");

        $customer = $this->getCustomerById($customerId);
        $paymentAgreement = $this->getPaymentAgreementById($request->request->get("paymentAgreementId"));

        $bill = $this->saveBilling($customer, $observation, $paymentAgreement);
        $total = $this->saveBillDetail($bill, $request->request->get("services"));

        if ($payMethodId != 2) {
            $this->savePayDetail($payMethodId, $payment, $bill);
        }

        //asignacion de puntos al cliente
        if ($customer && $paymentAgreement->getId() == 1) {
            $bonification = $this->getBonificationByPaymentAgreement($paymentAgreement);
            $this->updateCustomer($total, $customer, $bonification);
        }

        $this->saveBillHistory($bill);

        //Enviar via correo electronico la factura al cliente

        return $this->json(["billId" => $bill->getId()]);
    }

    public function getTiketByBillIdAction($billId) {
        $bill = $this->getBillById($billId);
        return $this->json($this->getTiket($bill));
    }

    public function getHistoryByBillIdAction($billId) {
        $resutl = array();
        $resutl["histories"] = array();

        $bill = $this->getBillById($billId);
        $billHistories = $bill->getBillHistories();

        $resutl["bill"] = $bill->getId();
        $resutl["order"] = $bill->getConsecutive();

        foreach ($billHistories as $billHistory) {
            $resutl["histories"][] = [
                "processState" => $billHistory->getProcessState()->getName(),
                "createdAt" => $billHistory->getCreatedAtString(),
                "user" => $billHistory->getUser()->getName(),
                "observation" => $billHistory->getObservation()
            ];
        }

        return $this->json($resutl);
    }

    public function getPayMethodAction() {
        $resutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $payMethodRepository = $doctrineManager->getRepository("LavasecoBundle:PayMethod");

        $payMethods = $payMethodRepository->findAll();

        foreach ($payMethods as $payMethod) {
            $resutl [] = [
                "id" => $payMethod->getId(),
                "name" => $payMethod->getName()
            ];
        }

        return $this->json($resutl);
    }

    public function getBillByIdAction($billId) {
        $bill = $this->getBillById($billId);

        $billResult = [
            "id" => $bill->getId(),
            "payed" => $bill->getPayed(),
            "total" => $bill->getTotal(),
            "billState" => $bill->getBillState()->getId(),
            "payAgreement" => $bill->getPaymentAgreement()->getId(),
        ];

        return $this->json($billResult);
    }

    public function deliverAction(Request $request) {
        $payMethodId = 1; //por ahora solo metodo de pago en efectivo
        $bill = $this->getBillById($request->request->get('id'));

        if (!$bill) {
            throw new NotFoundHttpException('Factura no encontrada!');
        }
        //si  el pago es contra entrega (1)

        $total = $bill->getTotal();
        if ($bill->getCustomer()) {
            $customer = $this->getCustomerById($bill->getCustomer()->getId());
        }
        $payment = ($bill->getPaymentAgreement()->getId() == 2) ? $total : ($total - $bill->getPayed());

        $bill->setBillState($this->getBillState(2)); //estado cancelado
        $bill->setProcessState($this->getProcessStateById(7)); //processState entregado

        $this->savePayDetail($payMethodId, $payment, $bill);

        //asigno puntos al usuario
        if ($bill->getCustomer()) {
            $bonification = $this->getBonificationByPaymentAgreement($bill->getPaymentAgreement());
            $this->updateCustomer($total, $customer, $bonification);
        }
        $this->saveBillHistory($bill);

        return $this->json([true]);
    }

    private function getBillContentById($billContentId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        return $billContentRepository->find($billContentId);
    }

    private function saveBilling($customer, $observation, $paymentAgreement) {
        $bill = new Bill();
        $em = $this->get('doctrine')->getManager();

        $bill->setSellerUser($this->getUser());
        $bill->setCustomer($customer);
        $bill->setObservation($observation);
        $bill->setPaymentAgreement($paymentAgreement);
        $bill->setBillState($this->getBillState($paymentAgreement->getId()));
        $bill->setProcessState($this->getProcessState());
        $bill->setConsecutive($this->getBillConsecutive());
        $bill->setSalePoint($this->getSalePoint());

        $em->persist($bill);

        return $bill;
    }

    private function saveBillDetail($bill, $services) {
        $total = 0;
        $em = $this->get('doctrine')->getManager();

        foreach ($services as $service) {
            $total += $service["price"] * $service["quantity"];
            $billDetail = new BillDetail();
            $serviceObj = $this->getServiceByServiceCategoryId($service["id"]);

            $billDetail->setBill($bill);
            $billDetail->setService($serviceObj);
            $billDetail->setPrice($service["price"]);
            $billDetail->setQuantity($service["quantity"]);
            $billDetail->setObservation($service["observations"]);

            $em->persist($billDetail);
            if (isset($service["descriptors"])) {
                $this->saveObjectStateReceivedService($serviceObj, $billDetail, $service["descriptors"]);
            }
        }
        return $total;
    }

    private function saveObjectStateReceivedService($service, $billDetail, $descriptors) {
        $em = $this->get('doctrine')->getManager();
        foreach ($descriptors as $descriptor) {
            $objectStateReceivedService = new ObjectStateReceivedService();
            $objectStateReceivedService->setService($service);
            $objectStateReceivedService->setBillDetail($billDetail);
            $objectStateReceivedService->
                    setStateObjectDescription($this->getStateObjectDescriptionById($descriptor["id"]));

            $em->persist($objectStateReceivedService);
        }
    }

    private function saveBillHistory($bill) {
        $em = $this->get('doctrine')->getManager();

        $billHistory = new BillHistory();
        $billHistory->setBill($bill);
        $billHistory->setUser($this->getUser());
        $billHistory->setProcessState($bill->getProcessState());

        $em->persist($billHistory);
        $em->flush();
    }

    private function savePayDetail($payMethodId, $payment, $bill) {
        $em = $this->get('doctrine')->getManager();

        $payMethod = $this->getPayMethodById($payMethodId);

        $payDeatil = new PayDetail();
        $payDeatil->setBill($bill);
        $payDeatil->setPayment($payment);
        $payDeatil->setPayMethod($payMethod);

        if ($payMethod->getId() == 1) { //si el pago es en efectivo registra movieento en la caja
            $this->saveCashTransaction($payment, $bill);
        }

        $em->persist($payDeatil);
    }

    private function saveCashTransaction($payment, $bill, $abono = false) {
        $em = $this->get('doctrine')->getManager();
        $salePoint = $this->getSalePoint();

        $cashTransaction = new CashTransaction();
        $cashTransaction->setPayment($payment);
        $cashTransaction->setUser($this->getUser());
        $cashTransaction->setSalePoint($salePoint);
        $cashTransaction->setTurn($salePoint->getTurn());
        $cashTransaction->setTypeTransaction($this->getTypeTransactionById(3)); //3 es el id de tipo de transaccion ingreso
        $cashTransaction->setDescription(($abono) ? "Abono" : "Pago" . " Facura " . $bill->getId());

        $em->persist($cashTransaction);
    }

    private function getBillState($paymentAgreementId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billStateRepository = $doctrineManager->getRepository("LavasecoBundle:BillState");

        return $billStateRepository->find($paymentAgreementId);
    }

    private function getProcessState() {
        ////determinar el estado delproceso segun el punto de venta o usuario que realiza la factura
        return $this->getProcessStateById(1);
    }

    private function getProcessStateById($processStateId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $processStateRepository = $doctrineManager->getRepository("LavasecoBundle:ProcessState");

        return $processStateRepository->find($processStateId);
    }

    private function getBillConsecutive() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->getConsecutive();
    }

    private function getCustomerById($customerId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");

        return $userRepository->find($customerId);
    }

    private function getPayMethodById($payMethodById) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $payMethodRepository = $doctrineManager->getRepository("LavasecoBundle:PayMethod");

        return $payMethodRepository->find($payMethodById);
    }

    private function getPaymentAgreementById($paymentAgreementId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $paymentAgreementRepository = $doctrineManager->getRepository("LavasecoBundle:PaymentAgreement");

        return $paymentAgreementRepository->find($paymentAgreementId);
    }

    private function getServiceByServiceCategoryId($serviceCategoryId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceRepository = $doctrineManager->getRepository("LavasecoBundle:Service");

        return $serviceRepository->getServiceByServiceCategoryId($serviceCategoryId);
    }

    private function getStateObjectDescriptionById($stateObjectDescriptionId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $StateObjectDescriptionRepository = $doctrineManager->getRepository("LavasecoBundle:StateObjectDescription");

        return $StateObjectDescriptionRepository->find($stateObjectDescriptionId);
    }

    private function getBillById($billId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->find($billId);
    }

    private function getTiket($bill) {
        $tiket = [
            "bill" => $bill->getId(),
            "consecutive" => $bill->getConsecutive(),
            "seller" => $bill->getSellerUser()->getName(),
            "customer" => ($bill->getCustomer()) ? $bill->getCustomer()->getName() : "No se Registro Cliente",
            "phoneNumber" => ($bill->getCustomer()) ? $bill->getCustomer()->getPhoneNumber() : "No se Registro Telefono",
            "observation" => $bill->getObservation(),
            "createdAt" => $bill->getCreatedAtString(),
            "billDetail" => $this->getItemsBill($bill)
        ];

        return $tiket;
    }

    private function getItemsBill($bill) {
        $billDetailsArray = array();
        $billDetailsArray ["total"] = 0;
        $billDetailsArray ["quantity"] = 0;
        $billDetailsArray ["details"] = array();

        $billDetails = $bill->getBillDetails();
        foreach ($billDetails as $billDetail) {
            $billDetailsArray ["details"] [] = [
                "quantity" => $billDetail->getQuantity(),
                "observation" => $billDetail->getObservation(),
                "serviceName" => $billDetail->getServiceName(),
                "descriptors" => $this->getDescriptors($billDetail->getObjectStateReceivedService())
            ];
            $billDetailsArray ["total"] += $billDetail->getPrice() * $billDetail->getQuantity();
            $billDetailsArray ["quantity"] += $billDetail->getQuantity();
        }
        return $billDetailsArray;
    }

    private function getDescriptors($objectStateReceivedServices) {
        $descriptors = array();
        foreach ($objectStateReceivedServices as $objectStateReceivedService) {
            $descriptors [] = $objectStateReceivedService->getStateObjectDescription()->getName();
        }
        return $descriptors;
    }

    private function getSalePoint() {
        $salePoint = $this->get('session')->get('salePoint');
        $em = $this->get('doctrine')->getManager();

        $salePointRepository = $em->getRepository("LavasecoBundle:SalePoint");

        return $salePointRepository->find($salePoint->getId());
    }

    private function getTypeTransactionById($typeTransactionId) {
        $em = $this->get('doctrine')->getManager();
        $typeTransactionRepository = $em->getRepository("LavasecoBundle:TypeTransaction");


        return $typeTransactionRepository->find($typeTransactionId);
    }

    private function updateCustomer($total, &$customer, $bonification) {
        $em = $this->get('doctrine')->getManager();

        $currentPoints = $customer->getPoints();
        $totalSpent = $customer->getTotalSpent();
        $customer->setLastVisit(new \DateTime(date('Y-m-d H:i:s')));
        $customer->setTotalSpent($totalSpent + $total);
        $customer->setTotalVisits($customer->getTotalVisits() + 1);
        $customer->setPoints($currentPoints + ($total * $bonification / 100 ));

        $em->persist($customer);
    }

    private function getBonificationByPaymentAgreement($paymentAgreement) {
        $bonification = 0;
        $doctrineManager = $this->get('doctrine')->getManager();
        $loyaltyRepository = $doctrineManager->getRepository("LavasecoBundle:Loyalty");

        $loyalty = $loyaltyRepository->find(1);

        if ($paymentAgreement->getId() == 1) {
            $bonification = $loyalty->getAdvancePercent();
        }

        if ($paymentAgreement->getId() == 2) {
            $bonification = $loyalty->getUponDeliveryPercent();
        }

        if ($paymentAgreement->getId() == 3) {
            $bonification = $loyalty->getDepositPercent();
        }

        return $bonification;
    }

}
