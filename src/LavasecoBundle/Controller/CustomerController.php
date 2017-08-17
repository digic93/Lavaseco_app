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
        $em = $this->get('doctrine')->getManager();

        $customer = new Customer();
        $customer->setName($request->request->get('name'));
        $customer->setEmail($request->request->get('email'));
        $customer->setAddress($request->request->get('address'));
        $customer->setPoints($this->getStartPoints());
        $customer->setPhoneNumber($request->request->get('phoneNumber'));

        $em->persist($customer);
        $em->flush();

        if ($customer->getEmail()) {
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
