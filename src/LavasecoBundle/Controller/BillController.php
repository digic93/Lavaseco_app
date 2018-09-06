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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BillController extends Controller {

    public function indexAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        $salePoint = $this->get('session')->get('salePoint');
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");
        $branchOfficeId = $salePoint->getBranchOffice()->getId();
 
        return $this->render($configuration->getViewTheme() . ':Bill/index.html.twig', [
                    "billsMovileDelivered" => $billRepository->findDelivered($branchOfficeId, 200, true),
                    "billsMovileUndelivered" => $billRepository->findUndelivered($branchOfficeId, true),
                    "billsDelivered" => $billRepository->findDelivered($branchOfficeId, 200),
                    "billsUndelivered" => $billRepository->findUndelivered($branchOfficeId),
                    "salePointIsOpen" => ($salePoint) ? $salePoint->getIsOpen() : false
        ]);
    }

    public function settingAction(Request $request) {
        $data = array();
        $configuration = $this->get('lavaseco.app_configuration');

        $billContent = $this->getBillContentById(1);

        $form = $this->createForm(BillContentType::class, $billContent);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->get('doctrine')->getManager();
                $billContent = $form->getData();
                $em->persist($billContent);

                $em->flush();
                $data["messagePad"] = "Se Guardo la configuración con éxito.";
            } catch (\Doctrine\DBAL\DBALException $e) {
                $data["messagePad"] = "No se pudo Guardar la configuración.";
                $data["errorPad"] = true;
            }
        }

        return $this->render($configuration->getViewTheme() . ':Settings/Bill/index.html.twig', $data + [
                    'form' => $form->createView(),
                    'billContent' => $billContent
        ]);
    }

    public function saveBillingAction(Request $request) {
        $payment = $request->request->get("total");
        $cashReceived = $request->request->get("cashReceived");
        $payMethodId = 1; //$request->request->get("paymenthod");
        $observation = $request->request->get("observations");
        $customerId = $request->request->get("customerId");
        $redeemedPointBill = $request->request->get("redeemedPointBill");

        $notity = [
            "printBill" => $request->request->get("printBill") == "true" ? true : false,
            "sendBill" => $request->request->get("sendBill") == "true" ? true : false,
            "notifyRedyDelivery" => $request->request->get("notifyRedyDelivery") == "true" ? true : false,
            "notifyDelivered" => $request->request->get("notifyDelivered") == "true" ? true : false,
        ];


        $customer = $this->getCustomerById($customerId);
        $paymentAgreement = $this->getPaymentAgreementById($request->request->get("paymentAgreementId"));

        $bill = $this->saveBilling($customer, $observation, $paymentAgreement, $notity, $redeemedPointBill);
        $total = $this->saveBillDetail($bill, $request->request->get("services"));
        
        if ($paymentAgreement->getId() != 2) {
            $payment = ($paymentAgreement->getId() == 1) ? $total : $cashReceived;
            $this->savePayDetail($payMethodId, $payment, $bill);
        }

        $bonification = ($redeemedPointBill == 0) ? $this->getBonificationByPaymentAgreement($paymentAgreement) : 0;
        if ($customer && $paymentAgreement->getId() == 1) {
            $this->updateCustomer($total, $customer, $bonification, $redeemedPointBill);
        } else if ($redeemedPointBill != 0) {
            $em = $this->get('doctrine')->getManager();
            $customer->setPoints($customer->getPoints() - $redeemedPointBill);
            $em->persist($customer);
        }

        $this->saveBillHistory($bill);

        //Enviar via correo electronico la factura al cliente
        if ($customer && $notity ["sendBill"]) {
            if ($customer->getEmail()) {
                $salePoint = $this->getSalePoint();
                $configuration = $this->get('lavaseco.app_configuration');
                $facturaId = $salePoint->getId() . "-" . $bill->getId();

                $message = (new \Swift_Message('Factura de servicio ' . $facturaId))
                        ->setFrom(['noreply@lavasecomodelo.com' => 'Lavaseco Modelo'])
                        ->setTo($customer->getEmail())
                        ->setBody(
                        $this->renderView(
                                $configuration->getViewTheme() . ':Emails/billEmail.html.twig', [
                            'facturaId' => $facturaId,
                            'customer' => $customer,
                            'bill' => $bill,
                            'billContent' => $this->getBillContentById(1),
                            'payment' => ($paymentAgreement->getId() != 2) ? $payment : 0,
                            'points' => ($total * $bonification / 100 ),
                            'redeemedPointBill' => $redeemedPointBill,
                                ]
                        ), 'text/html'
                );
                $this->get('mailer')->send($message);
            }
        }

        return $this->json(["billId" => $bill->getId()]);
    }

    public function getTiketByBillIdAction($billId) {
        $bill = $this->getBillById($billId);
        return $this->json($this->getTiket($bill));
    }
    
    public function getTiketMobileByBillIdAction($billId, Request $request) {
        $billIdFilter = array($billId);
        $customer = MobileAutenticationController::validateToken($request, $this);
        
        $bill = $customer->getBills()->filter(
            function($entry) use ($billIdFilter) {
               return in_array($entry->getId(), $billIdFilter);
            }
        );
        
        
        if($bill->count() > 0){
            $response = new Response(json_encode($this->getTiket($bill->first())));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }else{
            throw new HttpException(404, "Orden no encontrada");
        }
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
    
    public function paymentAction(Request $request){
        $payMethodId = 1;
        $billId = $request->request->get("bill");
        $cashReceived = $request->request->get("cashReceived");
        $paymentAgreementId = $request->request->get("paymentAgreement");
        
        $bill = $this->getBillById($billId);
        
        if($bill === null){
            return $this->json(['error' => "Factura " . $billId . " No encontrada" ]);
        }
        $customer = $bill->getCustomer();
        
        $paymentAgreement = $this->getPaymentAgreementById($paymentAgreementId);
        $bill->setPaymentAgreement($paymentAgreement);

        $total = $bill->getTotal() - $bill->getPayed();

        $payment = ($paymentAgreement->getId() == 1) ? $total : $cashReceived;
        $this->savePayDetail($payMethodId, $payment, $bill);
        
        $bonification = ($bill->getDiscount() == 0) ? $this->getBonificationByPaymentAgreement($paymentAgreement) : 0;
        if ($customer && $paymentAgreement->getId() == 1) {
            $this->updateCustomer($total, $customer, $bonification);
        }
        
        if($paymentAgreement->getId() == 1){
            $bill->setBillState($this->getBillStateById(2)); // cambia el estado de la factura a canceladdo 
        }
        
        
        $em = $this->get('doctrine')->getManager();

        $em->persist($bill);
        $em->flush();
        
        return $this->json(['error' => false ]);
        
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

        $bill->setBillState($this->getBillStateById(2)); //estado cancelado
        $bill->setProcessState($this->getProcessStateById(7)); //processState entregado

        $this->savePayDetail($payMethodId, $payment, $bill);

        //asigno puntos al usuario si el pago es diferente al anicipado
        if ($bill->getCustomer() && $bill->getPaymentAgreement()->getId() != 1) {
            $bonification = ($bill->getDiscount() == 0) ? $this->getBonificationByPaymentAgreement($bill->getPaymentAgreement()) : 0;
            $this->updateCustomer($total, $customer, $bonification);
        }
        $this->saveBillHistory($bill);
        if($bill->getCustomer()){
            $this->notifyDelivery($bill, $bill->getCustomer());
        }

        return $this->json([true]);
    }

    public function refundAction(Request $request) {
        $bill = $this->getBillById($request->request->get('bill'));

        if (!$bill) {
            throw new NotFoundHttpException('Factura no encontrada!');
        }

        $bill->setBillState($this->getBillStateById(3)); //estdo anulado
        $this->saveCashTransaction($bill->getPayed(), $bill, true);

        $em = $this->get('doctrine')->getManager();
        $em->persist($bill);
        $em->flush();

        return $this->json([true]);
    }
    
    public function getpaymentAgreementAction(Request $request){
        $paymentAgreementsResult = array();
        MobileAutenticationController::validateToken($request, $this);
        $doctrineManager = $this->get('doctrine')->getManager();
        $paymentAgreementRepository = $doctrineManager->getRepository("LavasecoBundle:PaymentAgreement");
        
        $paymentAgreements = $paymentAgreementRepository->getAvailables();
        foreach ($paymentAgreements as $paymentAgreement){
            $paymentAgreementsResult[] = [
                "id" => $paymentAgreement->getId(),
                "name" => $paymentAgreement->getName(),
                ];
        }
        
        return $this->json($paymentAgreementsResult);
    }
    
    public function saveBillingMovileAction(Request $request){
        $em = $this->get('doctrine')->getManager();
        $customer = MobileAutenticationController::validateToken($request, $this);
        
        if (empty($request->getContent()))
        {
            
        }
        
        $requestContent =  json_decode($request->getContent(), true);
        
        $bill = $this->getBillMovileByRequest($customer, $requestContent);
        $total = $this->saveBillDetailMobile($bill, $requestContent);
        
         
        $bill->setDeliveryAt(new \DateTime(date("Y/m/d H:i:s", strtotime($requestContent["dateOfDelivery"]))));
        $bill->setCollectAt(new \DateTime(date("Y/m/d H:i:s", strtotime($requestContent["pickUpOfDelivery"]))));

        $bill->setAddressDelivery($this->getAddressApp($requestContent, "delivery"));
        $bill->setAddressCollect($this->getAddressApp($requestContent, "pickUp"));
        
        $em->persist($bill);
        $em->flush();
        
        $redeemedPointBill = $bill->getDiscount();
        $paymentAgreement = $bill->getPaymentAgreement();
        
        $bonification = ($redeemedPointBill == 0) ? $this->getBonificationByPaymentAgreement($bill->getPaymentAgreement()) : 0;
        if ($customer && $paymentAgreement->getId() == 1) {
            $this->updateCustomer($total, $customer, $bonification, $redeemedPointBill);
        } else if ($redeemedPointBill != 0) {
            $em = $this->get('doctrine')->getManager();
            $customer->setPoints($customer->getPoints() - $redeemedPointBill);
            $em->persist($customer);
        }

        $this->saveBillHistory($bill, $bill->getSellerUser());
        
        if ($customer->getEmail()) {
                $salePoint = $bill->getSalePoint();
                $configuration = $this->get('lavaseco.app_configuration');
                $facturaId = $salePoint->getId() . "-" . $bill->getId();

                $message = (new \Swift_Message('Factura de servicio ' . $facturaId))
                        ->setFrom(['noreply@lavasecomodelo.com' => 'Lavaseco Modelo'])
                        ->setTo($customer->getEmail())
                        ->setBody(
                        $this->renderView(
                                $configuration->getViewTheme() . ':Emails/billEmail.html.twig', [
                            'facturaId' => $facturaId,
                            'customer' => $customer,
                            'bill' => $bill,
                            'billContent' => $this->getBillContentById(1),
                            'payment' => 0,
                            'points' => ($total * $bonification / 100 ),
                            'redeemedPointBill' => $bill->getDiscount(),
                                ]
                        ), 'text/html'
                );
                $this->get('mailer')->send($message);
            }
        
        
        return $this->json(["id" => $bill->getId()]);
    }
    
    
    public function mobileInfoAction($billId) {
        $bill = $this->getBillById($billId);

        $billResult = [
            "customer" => 
                [
                    "name" => $bill->getCustomer()->getName(),
                    "email" => $bill->getCustomer()->getEmail(),
                    "phone" => $bill->getCustomer()->getPhoneNumber(),
                ],
            "delivery" => 
                [
                    "Nickname" => $bill->getAddressDelivery()->getNickname(),
                    "Latitude" => $bill->getAddressDelivery()->getLatitude(),
                    "Longitude" => $bill->getAddressDelivery()->getLongitude(),
                    "Observation" => $bill->getAddressDelivery()->getObservation(),
                ],
            "pickUp" =>
                [
                    "Nickname" => $bill->getAddressCollect()->getNickname(),
                    "Latitude" => $bill->getAddressCollect()->getLatitude(),
                    "Longitude" => $bill->getAddressCollect()->getLongitude(),
                    "Observation" => $bill->getAddressCollect()->getObservation(),
                ]
        ];

        return $this->json($billResult);
    }
    
    private function getBillMovileByRequest($customer, $billRequest){
        $em = $this->get('doctrine')->getManager();
        $salePointRepository = $em->getRepository("LavasecoBundle:SalePoint");
        
        $paymentAgreement = $this->getPaymentAgreementById($billRequest["paymentAgreementId"]);

        $bill = new Bill();
        $userLavaseco = $this->getLavasecoUser()[0];
        $bill->setSellerUser($userLavaseco);
        $bill->setCustomer($customer);
        $bill->setObservation($billRequest["observations"]);
        $bill->setPaymentAgreement($paymentAgreement);
        $bill->setBillState($this->getBillStateById(1)); //siempre queda pendiete por ser una peticion de la aplicacion
        $bill->setProcessState($this->getProcessStateById(8));//pendiente por recojer
        $bill->setConsecutive($this->getBillConsecutive());
        $bill->setSalePoint($salePointRepository->find(1));//punto de venta principal Aplicación Movile
        $bill->setDiscount($billRequest["redeemedPointBill"]);

        $bill->setPrintedTiket(false);
        $bill->setPrintBill(false);
        $bill->setSendBill(true);
        $bill->setNotifyDelivered(true);
        $bill->setNotifyRedyDelivery(true);

        $em->persist($bill);
        
        return $bill;
    }
    
    private function saveBillDetailMobile(&$bill, $request){
        $total = 0;
        $em = $this->get('doctrine')->getManager();
        
        foreach ($request["services"] as $serviceRequest) {
            $service = $this->getServiceByServiceCategoryId($serviceRequest["idService"]);
            
            $total += $service->getPrice() * $serviceRequest["quantity"];
            $billDetail = new BillDetail();
            $billDetail->setBill($bill);
            $billDetail->setService($service);
            $billDetail->setPrice($service->getPrice());
            $billDetail->setQuantity($serviceRequest["quantity"]);
            $billDetail->setObservation($serviceRequest["observations"]);

            $em->persist($billDetail);

            $bill->addBillDetail($billDetail);
            if (isset($serviceRequest["descriptors"])) {
                $this->saveObjectStateReceivedService($service, $billDetail, $serviceRequest["descriptors"]);
            }
        }
        
        
//-----------Adicion costo de envio corregir en front       
        $service = $this->getServiceByServiceCategoryId(17);
        $total += $service->getPrice();
        $billDetail = new BillDetail();
        $billDetail->setBill($bill);
        $billDetail->setService($service);
        $billDetail->setPrice($service->getPrice());
        $billDetail->setQuantity(1);
        $billDetail->setObservation("");
        
        $em->persist($billDetail);
        $bill->addBillDetail($billDetail);
//----------------------------------------------     
        return $total;
    }
    
    private function getLavasecoUser(){
        $em = $this->get('doctrine')->getManager();
        $userRepository = $em->getRepository("LavasecoBundle:User");
        
        return $userRepository->getUserByNameOrEmail('lavasecomodelo@gmail.com');
        
    }
    
    private function getAddressApp($request, $index){
        $em = $this->get('doctrine')->getManager();
        $AddressRepository = $em->getRepository("LavasecoBundle:Address");
        
        if(isset($request[$index]["id"])){
            return $AddressRepository->findOneBy(array('id'=>$request[$index]["id"]));
        }else{
            $address = $AddressRepository->findAnonimusAddress($request[$index]["nickname"]);
            if(isset($address)){
                return $address;
            }else{
                $address = new \LavasecoBundle\Entity\Address();    
                $address->setLatitude($request[$index]["position"]["lat"]);
                $address->setLongitude($request[$index]["position"]["lng"]);
                $address->setNickname($request[$index]["nickname"]);
                $address->setObservation($request[$index]["observations"]);
                $address->setPlaceName($request[$index]["placeName"]);
                $em->persist($address);
                
                return $address;
            }
        }
    }
    
    private function getBillContentById($billContentId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        return $billContentRepository->find($billContentId);
    }

    private function saveBilling($customer, $observation, $paymentAgreement, $notify, $redeemedPointBill) {
        $bill = new Bill();
        $em = $this->get('doctrine')->getManager();

        $bill->setSellerUser($this->getUser());
        $bill->setCustomer($customer);
        $bill->setObservation($observation);
        $bill->setPaymentAgreement($paymentAgreement);
        $bill->setBillState($this->getBillStateById(($paymentAgreement->getId() == 1) ? 2 : 1)); //si el acuerdo de pago es antisipado estado cancelado
        $bill->setProcessState($this->getProcessState());
        $bill->setConsecutive($this->getBillConsecutive());
        $bill->setSalePoint($this->getSalePoint());
        $bill->setDiscount($redeemedPointBill);

        $bill->setPrintedTiket(true);
        $bill->setPrintBill($notify["printBill"]);
        $bill->setSendBill($notify["sendBill"]);
        $bill->setNotifyDelivered($notify["notifyDelivered"]);
        $bill->setNotifyRedyDelivery($notify["notifyRedyDelivery"]);

        $em->persist($bill);

        return $bill;
    }

    private function saveBillDetail(&$bill, $services) {
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

            $bill->addBillDetail($billDetail);
            if (isset($service["descriptors"])) {
                $this->saveObjectStateReceivedService($serviceObj, $billDetail, $service["descriptors"]);
            }
        }
        $em->flush();
        return $total;
    }

    private function saveObjectStateReceivedService($service, &$billDetail, $descriptors) {
        $em = $this->get('doctrine')->getManager();
        foreach ($descriptors as $descriptor) {
            $objectStateReceivedService = new ObjectStateReceivedService();
            $objectStateReceivedService->setService($service);
            $objectStateReceivedService->setBillDetail($billDetail);
            $objectStateReceivedService->
                    setStateObjectDescription($this->getStateObjectDescriptionById($descriptor["id"]));

            $billDetail->addObjectStateReceivedService($objectStateReceivedService);
            $em->persist($objectStateReceivedService);
        }
    }

    private function saveBillHistory($bill, $user = null) {
        $em = $this->get('doctrine')->getManager();

        if($user == null){
           $user = $this->getUser();
        }
        
        $billHistory = new BillHistory();
        $billHistory->setBill($bill);
        $billHistory->setUser($user);
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

    private function saveCashTransaction($payment, Bill $bill, $abono = 0) {
        $em = $this->get('doctrine')->getManager();
        $salePoint = $this->getSalePoint();

        $cashTransaction = new CashTransaction();
        $cashTransaction->setPayment($payment);
        $cashTransaction->setUser($this->getUser());
        $cashTransaction->setSalePoint($salePoint);
        $cashTransaction->setTurn($salePoint->getTurn());
        $cashTransaction->setTypeTransaction($this->getTypeTransactionById(($abono == -1) ? 4 : 3)); //3 es el id de tipo de transaccion ingreso 4 egreso
        $cashTransaction->setDescription((($abono == -1) ? "Reembolso" : (($bill->getPaymentAgreement()->getId() == 3) ? "Abono" : "Pago")) . " Factura " . $bill->getId());

        $em->persist($cashTransaction);
    }

    private function getBillStateById($billStateId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billStateRepository = $doctrineManager->getRepository("LavasecoBundle:BillState");

        return $billStateRepository->find($billStateId);
    }

    private function getProcessState() {
        ////determinar el estado del proceso segun el punto de venta o usuario que realiza la factura
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

    private function getTiket(Bill $bill) {
        //$bill = new Bill();
        $tiket = [
            "bill" => $bill->getId(),
            "consecutive" => $bill->getConsecutive(),
            "seller" => $bill->getSellerUser()->getName(),
            "customer" => ($bill->getCustomer()) ? $bill->getCustomer()->getName() : "No se Registro Cliente",
            "phoneNumber" => ($bill->getCustomer()) ? $bill->getCustomer()->getPhoneNumber() : "No se Registro Telefono",
            "observation" => $bill->getObservation(),
            "salePoint" => $bill->getSalePoint()->getId(),
            "createdAt" => $bill->getCreatedAtString(),
            "billDetail" => $this->getItemsBill($bill),
            "addressCollect"=> ($bill->getAddressCollect() == null)?"No Aplica":$bill->getAddressCollect()->getFullAddress(),
            "addressDelivery"=>($bill->getAddressDelivery() == null)?"No Aplica":$bill->getAddressDelivery()->getFullAddress(),
            "deliveryAt"=>($bill->getAddressDelivery() == null)?"No Aplica":$bill->getDeliveryAtString(),
            "collectAt"=>($bill->getAddressCollect() == null)?"No Aplica":$bill->getCollectAtString(),
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
                "price" =>$billDetail->getPrice(),
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

    private function updateCustomer($total, &$customer, $bonification, $redeemedPointBill = 0) {
        $em = $this->get('doctrine')->getManager();

        $currentPoints = $customer->getPoints();
        $totalSpent = $customer->getTotalSpent();
        $customer->setLastVisit(new \DateTime(date('Y-m-d H:i:s')));
        $customer->setTotalSpent($totalSpent + $total);
        $customer->setTotalVisits($customer->getTotalVisits() + 1);

        if ($redeemedPointBill == 0) {
            $points = $currentPoints + ($total * $bonification / 100);
        } else {
            $points = $currentPoints - $redeemedPointBill;
        }

        $customer->setPoints($points);

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

    private function notifyDelivery($bill, $customer) {
        if ($bill->getNotifyDelivered() && $bill->getProcessState()->getId() == 7) {
            $configuration = $this->get('lavaseco.app_configuration');
            $billId = $bill->getSalePoint()->getId() . "-" . $bill->getId();

            $message = (new \Swift_Message('Factura ' . $billId . ' se ha entregado'))
                    ->setFrom(['noreply@lavasecomodelo.com' => 'Lavaseco Modelo'])
                    ->setTo($customer->getEmail())
                    ->setBody(
                    $this->renderView(
                            $configuration->getViewTheme() . ':Emails/deliveriedEmail.html.twig', [
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
