<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Bill;
use LavasecoBundle\Entity\BillDetail;
use LavasecoBundle\Entity\BillHistory;
use LavasecoBundle\Entity\ObjectStateReceivedService;
use LavasecoBundle\Form\BillContentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BillController extends Controller {

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
        $customerUserId = $request->request->get("customerId");
        $observation = $request->request->get("observations");

        $paymentAgreement = $this->getPaymentAgreementById($request->request->get("paymentAgreementId"));

        $bill = $this->saveBilling($customerUserId, $observation, $paymentAgreement);

        $this->saveBillDetail($bill, $request->request->get("services"));

        $this->saveBillHistory($bill);

        return $this->json(["billId" => $bill->getId()]);
    }

    public function getTiketByBillIdAction($billId) {
        $bill = $this->getBillById($billId);
        return $this->json($this->getTiket($bill));
    }

    private function getBillContentById($billContentId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        return $billContentRepository->find($billContentId);
    }

    private function saveBilling($customerUserId, $observation, $paymentAgreement) {
        $bill = new Bill();
        $em = $this->get('doctrine')->getManager();

        $bill->setSellerUser($this->getUser());
        $bill->setCustomerUser($this->getUserbyId($customerUserId));
        $bill->setObservation($observation);
        $bill->setPaymentAgreement($paymentAgreement);
        $bill->setBillState($this->getBillState());
        $bill->setProcessState($this->getProcessState());
        $bill->setConsecutive($this->getBillConsecutive());

        $em->persist($bill);

        return $bill;
    }

    private function saveBillDetail($bill, $services) {
        $em = $this->get('doctrine')->getManager();

        foreach ($services as $service) {
            $billDetail = new BillDetail();
            $serviceObj = $this->getServiceByServiceCategoryId($service["id"]);

            $billDetail->setBill($bill);
            $billDetail->setService($serviceObj);
            $billDetail->setPrice($service["price"]);
            $billDetail->setQuantity($service["quantity"]);
            $billDetail->setObservation($service["observations"]);

            $em->persist($billDetail);

            $this->saveObjectStateReceivedService($serviceObj, $billDetail, $service["descriptors"]);
        }
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

    private function getBillState() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billStateRepository = $doctrineManager->getRepository("LavasecoBundle:BillState");

        return $billStateRepository->find(1);
    }

    private function getProcessState() {
        ////determinar el estado delproceso segun el suario que realiza la factura
        $doctrineManager = $this->get('doctrine')->getManager();
        $processStateRepository = $doctrineManager->getRepository("LavasecoBundle:ProcessState");

        return $processStateRepository->find(1);
    }

    private function getBillConsecutive() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billRepository = $doctrineManager->getRepository("LavasecoBundle:Bill");

        return $billRepository->getConsecutive();
    }

    private function getUserbyId($userId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");

        return $userRepository->find($userId);
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
            "customer" => $bill->getCustomerUser()->getName(),
            "phoneNumber" => $bill->getCustomerUser()->getPhoneNumber(),
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

}
