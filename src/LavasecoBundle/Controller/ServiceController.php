<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\Service;
use LavasecoBundle\Entity\ServiceCategory;
use LavasecoBundle\Entity\ServiceCategoryState;
use Symfony\Component\HttpFoundation\Request;
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

        $serviceCategory = $this->getServiceCategoryById($serviceCategoryId);

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

    public function getDescriptorsAction() {
        $descriptorsResponse = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $categoryStateObjectRepository = $doctrineManager->getRepository("LavasecoBundle:CategoryStateObject");
        $categoryStateObjects = $categoryStateObjectRepository->findAll();

        foreach ($categoryStateObjects as $categoryStateObject) {
            $descriptorsResponse[] = [
                "id" => $categoryStateObject->getId(),
                "name" => $categoryStateObject->getName(),
            ];
        }
        return $this->json($descriptorsResponse);
    }

    public function addServiceCategoryAction(Request $request) {
        $type = $request->request->get('type');
        $serviceCategory = new ServiceCategory();

        $em = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $em->getRepository("LavasecoBundle:ServiceCategory");

        $serviceCategory->setName($request->request->get('name'));
        $serviceCategory->setDescription($request->request->get('description'));

        if ($type != 1) {
            $superServiceCategory = $serviceCategoryRepository->find($request->request->get('superCategory'));
            $serviceCategory->setServiceCategory($superServiceCategory);
        }

        $em->persist($serviceCategory);
        $em->flush();

        //cuando es service
        if ($type == 3) {
            $this->addService($request->request->get('price'), $request->request->get('descriptors'), $serviceCategory);
        }

        return $this->json(["categoryId" => $serviceCategory->getId()]);
    }

//
//    "service":
//                        {
//                            "id": 3,
//                            "name": "Camisa",
//                            "type": "Servicio",
//                            "description": "Lavado de todo tipo de camisas",
//                            "supraCategory": "Lavado",
//                            "superCategory": "Prenda Sencilla",
//                            "price": 2000,
//                            "descriptors": [
//                                {"name": "Color"},
//                                {"name": "Estado"},
//                                {"name": "Tamaño"}
//                            ]
//                        }
//            };

    public function getServiceCategoryIdAction($serviceCategoryId) {
        $serviceCategory = $this->getServiceCategoryById($serviceCategoryId);

        $services = $serviceCategory->getServices();

        $type = (count($services)) ? "Servicio" : (($serviceCategory->getServiceCategory()) ? "Subcategoria" : "Categoria");

        $serviceCategoryResult = [
            "id" => $serviceCategory->getId(),
            "name" => $serviceCategory->getFullName(),
            "type" => $type,
            "description" => $serviceCategory->getDescription(),
        ];

        if (count($services)) {
            $serviceCategoryResult ["price"] = $services[0]->getPrice();
            $StateObjectDescriptions = $services[0]->getServiceDescriptors();

            $serviceCategoryResult ["descriptors"] = array();
            foreach ($StateObjectDescriptions as $StateObjectDescription) {
                $serviceCategoryResult ["descriptors"] [] = ["name" => $StateObjectDescription->getCategoryStateObject()->getName()];
            }
        }

        return $this->json($serviceCategoryResult);
    }

    private function addService($price, $descriptors, $serviceCategory) {
        $service = new Service();
        $em = $this->get('doctrine')->getManager();

        $service->setPrice($price);
        $service->setServiceCategory($serviceCategory);
        $em->persist($service);
        $em->flush();

        foreach ($descriptors as $descriptorId) {
            $categoryStateObjectRepository = $em->getRepository("LavasecoBundle:CategoryStateObject");
            $categoryStateObject = $categoryStateObjectRepository->find($descriptorId);

            if (isset($categoryStateObject)) {
                $serviceCategoryState = new ServiceCategoryState();
                $serviceCategoryState->setService($service);
                $serviceCategoryState->setCategoryStateObject($categoryStateObject);
                $em->persist($serviceCategoryState);
                $em->flush();
            }
        }
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

    private function getServiceCategoryById($serviceCategoryId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");

        return $serviceCategoryRepository->find($serviceCategoryId);
    }
}
