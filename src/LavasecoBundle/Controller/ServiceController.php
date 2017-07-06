<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServiceController extends Controller {

    public function settingAction() {
        $configuration = $this->get('lavaseco.app_configuration');

        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->getFirstLevel();

        return $this->render($configuration->getViewTheme() . ':Settings/Services/index.html.twig', ['serviceCagories' => $serviceCagories]
        );
    }

    public function getServiceByServiceCategoryIdAction($serviceCategoryId) {
        $serviceCagoriesResponse = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagories = $serviceCategoryRepository->getSubserviceCategoriesByServiceCategoryId($serviceCategoryId);

        foreach ($serviceCagories as $serviceCagory) {
            $serviceCagoriesResponse [] = [
                "id" => $serviceCagory->getId(),
                "name" => $serviceCagory->getName()
            ];
        }

        return $this->json($serviceCagoriesResponse);
    }

    public function getServiceAndDescriptionsByServiceCategoryIdAction($serviceCategoryId) {
        $servicesResponse = array();
        $servicesResponse ["decriptions"] = array();

        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCategory = $serviceCategoryRepository->find($serviceCategoryId);
  
        $service = $serviceCategory->getServices()[0];
        
        $servicesResponse ["service"] = [
            "id" => $service->getId(),
            "name" => $serviceCategory->getName(),
            "price" => $service->getPrice(),
        ];

        $descriptors = $service->getServiceDescriptors();

        foreach ($descriptors as $descriptor) {
            $servicesResponse ["decriptions"] [] = $this->getDescriptors($descriptor);
        }
        
        return $this->json($servicesResponse);
    }

    private function getDescriptors($descriptor) {
        $descriptorResutl = array();
        $descriptorResutl ["options"] = array();

        $categoryStateObject = $descriptor->getCategoryStateObject();
        $stateObjectDescriptions = $categoryStateObject->getStateObjectDescriptions();

        if (!$stateObjectDescriptions->count()) {
            return null;
        }

        $descriptorResutl ["name"] = $categoryStateObject->getName();

        foreach ($stateObjectDescriptions as $stateObjectDescription) {
            $descriptorResutl ["options"][] = [
                "id" => $stateObjectDescription->getId(),
                "name" => $stateObjectDescription->getName(),
            ];
        }

        return $descriptorResutl;
    }

}
