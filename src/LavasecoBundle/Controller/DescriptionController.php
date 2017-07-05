<?php

namespace LavasecoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use LavasecoBundle\Entity\CategoryStateObject;
use \LavasecoBundle\Form\CategoryStateObjectType;
use \LavasecoBundle\Entity\StateObjectDescription;
use LavasecoBundle\Form\StateObjectDescriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DescriptionController extends Controller {

    public function settingAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        $formObjectDescriptionView = $this->formStateObjectDescription($request);
        $formCategoryStateObjectView = $this->formCategoryStateObject($request);
        
        if(!$formCategoryStateObjectView || !$formObjectDescriptionView){
            return $this->redirectToRoute('lavaseco_settign_descriptions');
        }
            
        
        $ObjectDescriptions = $this->getAllStateObjectDescriptionions();

        return $this->render($configuration->getViewTheme() . ':Settings/Descriptions/index.html.twig', [
                    "ObjectDescriptions" => $ObjectDescriptions,
                    "formObjectDescription" => $formObjectDescriptionView,
                    "formCategoryStateObject" => $formCategoryStateObjectView,
        ]);
    }

    //type description
    private function formCategoryStateObject(Request $request) {
        $categoryStateObject = new CategoryStateObject();

        $form = $this->createForm(CategoryStateObjectType::class, $categoryStateObject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryStateObject = $form->getData();
            $this->saveEntity($categoryStateObject);
            return false;
        }

        return $form->createView();
    }

    //description
    private function formStateObjectDescription(Request $request) {
        $stateObjectDescription = new StateObjectDescription();
        
        $form = $this->createForm(StateObjectDescriptionType::class, $stateObjectDescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stateObjectDescription = $form->getData();
            $this->saveEntity($stateObjectDescription);
            return false;
        }
        return $form->createView();
    }

    private function getAllStateObjectDescriptionions() {
        $doctrineManager = $this->get('doctrine')->getManager();
        $stateObjectDescriptions = $doctrineManager->getRepository("LavasecoBundle:StateObjectDescription");

        return $stateObjectDescriptions->findAll();
    }
    
    private function saveEntity($entity){
        $em = $this->get('doctrine')->getManager();
        $em->persist($entity);
        $em->flush();
    }
}
