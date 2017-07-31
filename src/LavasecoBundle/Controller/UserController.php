<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller {

    public function searchAction(Request $request) {
        $usersByName = $this->getUsersByName($request->request->get('nameOrEmail'));
        $usersByEmail = $this->getUsersByEmail($request->request->get('nameOrEmail'));
        $usersByPhoneNumber = $this->getUsersByPhoneNumber($request->request->get('nameOrEmail'));

        $usersResutl = $usersByName + $usersByPhoneNumber + $usersByEmail;
        
        return $this->json($usersResutl);
    }

    public function getByUserIdAction($userId) {
        $userResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");

        $user = $userRepository->find($userId);

        if (isset($user)) {
            $userResutl ["id"] = $user->getId();
            $userResutl ["firstName"] = $user->getFirstName();
            $userResutl ["lastName"] = $user->getLastName();
            $userResutl ["phoneNumber"] = $user->getPhoneNumber();
            $userResutl ["email"] = $user->getEmail();
        }

        return $this->json($userResutl);
    }

    public function validateEmailAction($email) {
        return $this->json(["valid" => $this->validateEmail($email)]);
    }

    public function createAction() {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Users/index.html.twig');
    }

    public function registerAction(Request $request) {

        if (!$this->validateUser($request)) {
            return $this->json(["error" => "Datos no validos"]);
        }

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $user->setUsername($request->request->get('email'));
        $user->setFirstName($request->request->get('firstName'));
        $user->setLastName($request->request->get('lastName'));
        $user->setPhoneNumber($request->request->get('phoneNumber'));
        $user->setEmail($request->request->get('email'));
        $user->setEmailCanonical($request->request->get('email'));
        $user->setPlainPassword($this->generaPass());
        $user->setEnabled(0);
        $userManager->updateUser($user);

        return $this->json(["id" => $user->getId(),
                    "firstName" => $user->getFirstName(),
                    "lastName" => $user->getLastName(),
                    "phoneNumber" => $user->getPhoneNumber(),
                    "email" => $user->getEmail()]);
    }

    private function validateUser(Request $request) {
        if (!$this->validateEmail($request->request->get('email'))) {
            return false;
        }
        return true;
    }

    private function validateEmail($email) {
        $userManager = $this->get('fos_user.user_manager');
        $email_exist = $userManager->findUserByEmail($email);

        if (!$email_exist) {
            return true;
        }

        return false;
    }

    private function generaPass() {
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena = strlen($cadena);

        $pass = "";
        $longitudPass = 6;

        for ($i = 1; $i <= $longitudPass; $i++) {
            $pos = rand(0, $longitudCadena - 1);

            $pass .= substr($cadena, $pos, 1);
        }
        return $pass;
    }

    private function getUsersByName($name) {
        $usersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");
        $users = $userRepository->getUsersByName($name);

        foreach ($users as $user) {
            $usersResutl [] = [
                "id" => $user->getId(),
                "data" => $user->getFirstName() . " " . $user->getLastName()
            ];
        }
        
        return $usersResutl;
    }
    
    private function getUsersByEmail($email) {
        $usersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");
        $users = $userRepository->getUsersByEmail($email);

        foreach ($users as $user) {
            $usersResutl [] = [
                "id" => $user->getId(),
                "data" => $user->getEmail()
            ];
        }

        return $usersResutl;
    }

    private function getUsersByPhoneNumber($phoneNumber) {
        $usersResutl = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $userRepository = $doctrineManager->getRepository("LavasecoBundle:User");
        $users = $userRepository->getUsersByPhoneNumber($phoneNumber);


        foreach ($users as $user) {
            $usersResutl [] = [
                "id" => $user->getId(),
                "data" => $user->getPhoneNumber()
            ];
        }

        return $usersResutl;
    }

}
