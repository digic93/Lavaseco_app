<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {

    public function searchAction(Request $request) {
        $usersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");

        $users = $userRepository->getUserByNameOrEmail($request->request->get('nameOrEmail'));

        foreach ($users as $user) {
            $usersResutl = [
                "id" => $user->getId(),
                "name" => $user->getFirstName() . " " . $user->getLastName(),
                "email" => $user->getEmail()
            ];
        }

        return $this->json($usersResutl);
    }

    public function getByUserIdAction($userId) {
        $userResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");

        $user = $userRepository->find($userId);
        
        if ( isset($user) ) {
            $userResutl ["idName" ] = $user->getId();
            $userResutl ["fisrtName" ] = $user->getFirstName();
            $userResutl ["lastName" ] = $user->getLastName();
        }

        return $this->json($userResutl);
    }

}
