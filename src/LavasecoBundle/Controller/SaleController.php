<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SaleController extends Controller
{
    public function indexAction()
    {
        $doctrineManager = $this->get('doctrine')->getManager();
        $configuration = $this->get('lavaseco.app_configuration');
        
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->findAll();
        
        
        return $this->render($configuration->getViewTheme() . ':Sale/index.html.twig', ["serviceCagories" => $serviceCagories]);
    }
}
