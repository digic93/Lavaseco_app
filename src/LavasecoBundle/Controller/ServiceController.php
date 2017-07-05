<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServiceController extends Controller {

    public function settingAction(){
        $configuration = $this->get('lavaseco.app_configuration');
        
        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->getFirstLevel();
        
        return $this->render($configuration->getViewTheme() . ':Settings/Services/index.html.twig',
                ['serviceCagories' => $serviceCagories]
            );
    }
    
    public function getServiceByServiceCategoryIdAction($serviceCategoryId){
        $serviceCagoriesResponse = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->getSubserviceCategoriesByServiceCategoryId($serviceCategoryId);
        
        foreach ($serviceCagories as $serviceCagory){
            $serviceCagoriesResponse [] = [
                        "id" => $serviceCagory->getId(),
                        "name" => $serviceCagory->getName()
                    ];
        }
        
        return $this->json($serviceCagoriesResponse);
    }
}
