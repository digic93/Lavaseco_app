<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use LavasecoBundle\Form\ResetCustomerPasswordType;

class MobileAutenticationController extends Controller {

    public function loginAction(Request $request) {
        $params = $this->getUserParams($request);
        
        $email = $params["email"];
        $password = $params["password"];
        
        $customerLoggedIn = $this->validateUser($email, $password);
        
        if (!$customerLoggedIn) {
            throw new HttpException(401, "Acceso no autorizado.");
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
                ->encode(['customerId' => $customerLoggedIn->getId()]);

        $customerResult = [
            "token" => $token,
            "name" => $customerLoggedIn->getName(),
            "email" => $customerLoggedIn->getEmail(),
            "address" => $customerLoggedIn->getAddress(),
            "phoneNumber" => $customerLoggedIn->getPhoneNumber(),
            "points" => $customerLoggedIn->getPoints(),
        ];

        $response = new Response(json_encode($customerResult));
        $response->headers->set('Content-Type', 'application/json');
      
        return $response;
    }
    
    public function getResetpasswordAction(Request $request){
        if (!empty($request->getContent()))
        {   
            $correo = json_decode($request->getContent(), true);
            $this->sendEmailResetPassword($correo["email"]);
        }
        
        $response = new Response(json_encode(TRUE));
        $response->headers->set('Content-Type', 'application/json');
      
        return $response;
    }
   
    
    public function restartPasswordAction($uuidUser, Request $request){
        $configuration = $this->get('lavaseco.app_configuration');
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
       
        $customer = $customerRepository->findOneBy(['uuid' => $uuidUser]);
        
        if(!isset($customer)){
            $response = $this->redirectToRoute('lavaseco_homepage');
            return $response;
        }
        
        $form = $this->createForm(ResetCustomerPasswordType::class, $customer);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $customer = $form->getData();
            $customer->setUuid("");
            $em->persist($customer);
            $em->flush();
            return $this->render($configuration->getViewTheme() . ':Home/resetpassword.html.twig',['customer' => $customer, 'message' => "Se a restaurado la contrasña correctamente" ]);

        }
        return $this->render($configuration->getViewTheme() . ':Home/resetpassword.html.twig',['customer' => $customer, 'form' =>$form->createView() ]);
        
    }
    
    private function getUserParams($request){
        $user = $request->getContent();
        if (!empty($user))
        {
            $user = json_decode($user, true);
            
            if(!isset($user["email"]) || !isset($user["password"])){
                throw new HttpException(401, "Acceso no autorizado, email y password requeridos.");
            }
            
            return $user;
        }else{
            throw new HttpException(401, "Acceso no autorizado.");
        }
    }
    
    private function validateUser($email, $password){
        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        return $customerRepository->login($email, $password);
    }
    
    private function sendEmailResetPassword($email){
        $doctrineManager = $this->getDoctrine()->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        $customer = $customerRepository->findBy(["email" => $email]);
        if(count($customer) > 0){
            $this->sendMail($customer[0]);
        }
    }
    
    private function sendMail($customer){
        if ($customer->getEmail()) {    
            $configuration = $this->get('lavaseco.app_configuration');
                      
            $customer->setUuId(uniqid());
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            
            $message = (new \Swift_Message('Restauración de Contraseña'))
                    ->setFrom(['lavasecomodelo@gmail.com' => 'Lavaseco Modelo'])
                    ->setTo($customer->getEmail())
                    ->setBody(
                    $this->renderView(
                            $configuration->getViewTheme() . ':Emails/resetCustomerPassword.html.twig', [
                                'customer' => $customer, 
                                'uuid' => $customer->getUuId()
                            ]
                    ), 'text/html'
            );
            $this->get('mailer')->send($message);
        }
    }
    
    public static function validateToken($request, $controller){
        $doctrineManager = $controller->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");

        try
        {
            $headers = $request->headers->all();
            
            if(!isset($headers['token'])){
                throw new HttpException(401, "Acceso no autorizado");
            }
            
            $token = $headers['token'][0];
            $data = $controller->get('lexik_jwt_authentication.encoder')->decode($token);
            
        }catch(\Exception $e){
            throw new HttpException(401, "la sesión a expirado.");
        };
        
        return $userRepository->find($data['customerId']);        
    }
    
}
