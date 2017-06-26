<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServiceController extends Controller {

    public function getByCategoryIdAction($categoryId) {
        return $this->json($this->getServicesByCategoryId($categoryId));
    }

    private function getServicesByCategoryId($categoryId) {
        $servicesArray = array();
        $doctrineManager = $this->get('doctrine')->getManager();
        $serviceCategoryRepository = $doctrineManager->getRepository("LavasecoBundle:ServiceCategory");
        $serviceCagory = $serviceCategoryRepository->find($categoryId);

        $services = $serviceCagory->getServices();

        if (!$services->isEmpty()) {
            $service = $services->first();
            do {
                $servicesArray [] = [
                    "id" => $service->getId(),
                    "name" => $service->getName(),
                    "price" => $service->getprice(),
                    "img" => $service->getImg(),
                ];
            } while ($service = $services->next());
        }
        return $servicesArray;
    }

}
