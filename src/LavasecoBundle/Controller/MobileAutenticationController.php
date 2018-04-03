<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MobileAutenticationController extends Controller {

    public function loginAction($email, $password) {

        $doctrineManager = $this->get('doctrine')->getManager();
        $customerRepository = $doctrineManager->getRepository("LavasecoBundle:Customer");
        $customerLoggedIn = $customerRepository->findOneBy([
            'email' => $email,
            'password' => $password,
        ]);

        if ($customerLoggedIn != NULL){  
            
            $customerResult = [
                "id" => $customerLoggedIn->getId(),
                "name" => $customerLoggedIn->getName(),
                "email" => $customerLoggedIn->getEmail(),
                "address" => $customerLoggedIn->getAddress(),
                "phoneNumber" => $customerLoggedIn->getPhoneNumber(),
                "points" => $customerLoggedIn->getPoints(),
            ];
            
            echo $customerResult; 
            io ();
            
            return $this->json($customerResult);
            
        }else{
            throw new AccessDeniedException('No token given or token is wrong.');;
        }
    }


}
