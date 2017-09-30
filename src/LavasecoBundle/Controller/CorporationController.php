<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CorporationController extends Controller {

    public function indexAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');
        
        $corporation = $this->getCorporationById(1);
        $form = $this->createForm(\LavasecoBundle\Form\CorporationType::class, $corporation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            
            $corporation = $form->getData();
            
            $em->persist($corporation);
            $em->flush();
        }

        return $this->render($configuration->getViewTheme() . ':Settings/Corporation/index.html.twig', [
                    "form" => $form->createView()
        ]);
    }
    
    public function getCorporationById($corporationId){
        $doctrineManager = $this->get('doctrine')->getManager();
        
        $corporationRepository = $doctrineManager->getRepository("LavasecoBundle:Corporation");
        $corporation = $corporationRepository->find($corporationId);

        return ($corporation != null)?$corporation:new \LavasecoBundle\Entity\Corporation();
    }
}
