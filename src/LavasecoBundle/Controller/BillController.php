<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Bill;
use LavasecoBundle\Entity\BillDetail;
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

    public function saveBillingAction(Request $request){
        $customerUserId = $request->request->get("customerId");
        $observation = $request->request->get("observation");
        $paymentAgreement = $this->getPaymentAgreementById($request->request->get("paymentAgreementId"));
        $bill = $this->saveBilling($customerUserId, $observation, $paymentAgreement);
        $billDetails = $this->saveBillDetail($bill, $request->request->get("services"));
        
    }
    
    private function getBillContentById($billContentId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        return $billContentRepository->find($billContentId);
    }
    
    private function saveBilling($customerUserId, $observation, $paymentAgreement){
        $bill = new Bill();
        $em = $this->get('doctrine')->getManager();
        
        $bill->setSellerUser($this->container->get('security.context')->getToken()->getUser());
        $bill->setCustomerUser($this->getUserbyId($customerUserId));
        $bill->setObservation($observation);
        $bill->setPaymentAgreement($paymentAgreement);
        $bill->setBillState($this->billState());
        
        $em->persist($bill);
        $em->flush();
        
        return $bill;
    }
    
    private function saveBillDetail($bill, $services){
        $em = $this->get('doctrine')->getManager();
        
        foreach ($services as $service){
            $billDetail = new BillDetail();
            
            $billDetail->setBill($bill);
            $billDetail->setPrice($service["price"]);
            $billDetail->setQuantity($service["quantity"]);
            $billDetail->setObservation($service["observations"]);
            
            $em->persist($billDetail);
            $em->flush();
        }
//          {  
//                  "id":8,
//                  "service":"Chaquetas",
//                  "price":7500,
//                  "quantity":3,
//                  "total":"22,500",
//                  "descriptors":[  
//                     {  
//                        "id":5,
//                        "name":"Verde"
//                     },
//                     {  
//                        "id":12,
//                        "name":"Mediano"
//                     }
//                  ],
//                  "observations":"bueno ",
//                  "idSummaryService":0
//               }
    }
    
    private function saveStateObjectReceived(){
        
    }
    
    private function getUserbyId($userId){
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");

        return $userRepository->find($userId);
    }
    
    private function billState(){
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:BillState");

        return $userRepository->find(1);
    }
    
    private function getPaymentAgreementById($paymentAgreementId){
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:PaymentAgreement");

        return $userRepository->find($paymentAgreementId);
    }
    
}
