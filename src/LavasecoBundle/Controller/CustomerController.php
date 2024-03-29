<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomerController extends Controller {

    public function searchAction(Request $request) {
        $customersByName = $this->getCustomersByName($request->request->get('nameOrEmail'));
        $customersByEmail = $this->getCustomersByEmail($request->request->get('nameOrEmail'));
        $customersByPhoneNumber = $this->getCustomersByPhoneNumber($request->request->get('nameOrEmail'));

        $usersResutl = $customersByName + $customersByPhoneNumber + $customersByEmail;

        return $this->json($usersResutl);
    }

    public function getCustomerAction($customerId) {
        $customerResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");

        $customer = $customerRepository->find($customerId);

        if (isset($customer)) {
            $customerResutl ["id"] = $customer->getId();
            $customerResutl ["name"] = $customer->getName();
            $customerResutl ["phoneNumber"] = $customer->getPhoneNumber();
            $customerResutl ["email"] = $customer->getEmail();
            $customerResutl ["points"] = $customer->getPoints();
        }

        return $this->json($customerResutl);
    }

    public function validateEmailAction($email) {
        return $this->json(["valid" => $this->validateEmail($email)]);
    }

    public function createAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        $customers = $customerRepository->findAll();
        return $this->render($configuration->getViewTheme() . ':Settings/Customer/index.html.twig', ["customers" => $customers]);
    }

    public function registerAction(Request $request) {
        $id = $request->request->get('id');
        $em = $this->get('doctrine')->getManager();
        $customerRepository = $em->getRepository("LavasecoBundle:Customer");

        $customer = $customerRepository->find($id);

        $customer = (isset($customer))?$customer:new Customer();


        $customer->setName($request->request->get('name'));
        $customer->setEmail($request->request->get('email'));
        $customer->setAddress($request->request->get('address'));
        if($id  == "0")$customer->setPoints($this->getStartPoints());
        $customer->setPhoneNumber($request->request->get('phoneNumber'));

        $em->persist($customer);
        $em->flush();

        if ($customer->getEmail() && $id == "0") {
            $configuration = $this->get('lavaseco.app_configuration');
            $message = (new \Swift_Message('Bienvenido al Lavaseco Modelo'))
                    ->setFrom(['noreply@lavasecomodelo.com' => 'Lavaseco Modelo'])
                    ->setTo($customer->getEmail())
                    ->setBody(
                    $this->renderView(
                            $configuration->getViewTheme() . ':Emails/welcomeEmail.html.twig', [
                        'customer' => $customer,
                            ]
                    ), 'text/html'
            );
            $this->get('mailer')->send($message);
        }

        return $this->json(["id" => $customer->getId(),
                    "name" => $customer->getName(),
                    "email" => $customer->getEmail(),
                    "address" => $customer->getAddress(),
                    "phoneNumber" => $customer->getPhoneNumber(),
                    "createdAt" => $customer->getCreatedAt(),
                    "lastVisit" => $customer->getLastVisit(),
        ]);
    }

    public function addAddressAction (Request $request){
        $customer = MobileAutenticationController::validateToken($request, $this);

        if (empty($request->getContent()))
        {
            throw new HttpException(500, "Informacion de dirección no valida");
        }
        $em = $this->get('doctrine')->getManager();

        $addressRequest = json_decode($request->getContent(), true);

        $address = new \LavasecoBundle\Entity\Address();
        $address->setLatitude($addressRequest["position"]["lat"]);
        $address->setLongitude($addressRequest["position"]["lng"]);
        $address->setNickname($addressRequest["nickname"]);
        $address->setObservation($addressRequest["observations"]);
        $address->setPlaceName($addressRequest["placeName"]);
        $address->setCustomer($customer);

        $em->persist($address);
        $em->flush();

        $addressRequest["id"] = $address->getId();

        return $this->json($addressRequest);
    }

    public function getAddressAction(Request $request){
        $addressesResult = array();
        $customer = MobileAutenticationController::validateToken($request, $this);

        $addresses = $customer->getAddressApp();
        foreach ($addresses as $address){
           $addressesResult [] = $this->getAddressToArry($address);
        }

        return $this->json($addressesResult);
    }

    public function updateAddressAction(Request $request){
        $customer = MobileAutenticationController::validateToken($request, $this);

        if (empty($request->getContent()))
        {
            throw new HttpException(500, "Informacion de dirección no valida");
        }
        $em = $this->get('doctrine')->getManager();
        $addressRequest = json_decode($request->getContent(), true);

        $addresses = $customer->getAddressApp();
        foreach ($addresses as $address){
            if($address->getId() == $addressRequest["id"]){

                $address->setLatitude($addressRequest["position"]["lat"]);
                $address->setLongitude($addressRequest["position"]["lng"]);
                $address->setNickname($addressRequest["nickname"]);
                $address->setObservation($addressRequest["observations"]);
                $address->setPlaceName($addressRequest["placeName"]);

                $em->persist($address);
                $em->flush();

                return $this->json($addressRequest);
            }
        }
        return $this->json(false);
    }

    public function getAddressSalepointAction(Request $request){
        $addressesResult = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        MobileAutenticationController::validateToken($request, $this);

        $branchOfficeRepository = $doctrineManager->getRepository("LavasecoBundle:BranchOffice");
        $branchOffices = $branchOfficeRepository->findAll();

        foreach ($branchOffices as $branchOffice){
            foreach ($branchOffice->getAddressApp() as $address){
               $addressesResult [] = $this->getAddressToArry($address);
            }
        }

        return $this->json($addressesResult);
    }

    public function updateAction(Request $request){
        $customer = MobileAutenticationController::validateToken($request, $this);
        $em = $this->get('doctrine')->getManager();

        $customerData = json_decode($request->getContent(), true);

        $customer->setName($customerData['name']);
        $customer->setPhoneNumber($customerData['phone']);
        $customer->setAddress($customerData['address']);

        $em->persist($customer);
        $em->flush();

        return $this->json($customerData);
    }

    public function updatePasswordAction(Request $request){
        $em = $this->get('doctrine')->getManager();
        $customer = MobileAutenticationController::validateToken($request, $this);

        $passwords = json_decode($request->getContent(), true);

        if(hash('sha256',$passwords["current"]) == $customer->getPassword() ){
            $customer->setPassword($passwords["new"]);
            $em->persist($customer);
            $em->flush();
            return $this->json(true);
        }else{
            return $this->json(false);
        }

    }

    public function getPointAction(Request $request){
      $customer = MobileAutenticationController::validateToken($request, $this);
      $em = $this->get('doctrine')->getManager();

      return $this->json($customer->getPoints());
    }

    private function getAddressToArry($address){
        return [
                "id" => $address->getId(),
                "nickname" => $address->getNickname(),
                "observations" => $address->getObservation(),
                "placeName" => $address->getPlaceName(),
                "position" => [
                    "lat" => $address->getLatitude(),
                    "lng" => $address->getLongitude()
                ]
            ];
    }

    private function validateEmail($email) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");

        $email_exist = $customerRepository->findByEmail($email);

        if (!$email_exist) {
            return true;
        }

        return false;
    }

    private function getCustomersByName($name) {
        $customersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        $customers = $customerRepository->getCustomersByName($name);

        foreach ($customers as $customer) {
            $customersResutl [] = [
                "id" => $customer->getId(),
                "data" => $customer->getName()
            ];
        }

        return $customersResutl;
    }

    private function getCustomersByEmail($email) {
        $customersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        $customers = $customerRepository->getCustomersByEmail($email);

        foreach ($customers as $customer) {
            $customersResutl [] = [
                "id" => $customer->getId(),
                "data" => $customer->getEmail()
            ];
        }

        return $customersResutl;
    }

    private function getCustomersByPhoneNumber($phoneNumber) {
        $customersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        $customers = $customerRepository->getCustomersByPhoneNumber($phoneNumber);

        foreach ($customers as $customer) {
            $customersResutl [] = [
                "id" => $customer->getId(),
                "data" => $customer->getPhoneNumber()
            ];
        }

        return $customersResutl;
    }

    public function getStartPoints() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerLoyalty = $doctrineManager->getRepository("LavasecoBundle:Loyalty");

        $loyalty = $customerLoyalty->find(1);

        return $loyalty->getStartPoint();
    }

}
