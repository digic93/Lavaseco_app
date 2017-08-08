<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Loyalty;
use LavasecoBundle\Form\LoyaltyType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoyaltyController extends Controller {

    public function indexAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');
        
        $loyalty = $this->getLoyaltyById(1);

        $form = $this->createForm(LoyaltyType::class, $loyalty);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            
            $loyalty = $form->getData();
            
            $em->persist($loyalty);
            $em->flush();
        }

        return $this->render($configuration->getViewTheme() . ':Settings/Loyalty/index.html.twig', [
                    "form" => $form->createView()
        ]);
    }
    
    public function getLoyaltyById($loyaltyId){
        $doctrineManager = $this->get('doctrine')->getManager();
        
        $loyaltyRepository = $doctrineManager->getRepository("LavasecoBundle:Loyalty");
        $loyalty = $loyaltyRepository->find($loyaltyId);

        return ($loyalty != null)?$loyalty:new Loyalty();
    }
}
