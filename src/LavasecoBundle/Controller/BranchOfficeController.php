<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Entity\BranchOffice;
use LavasecoBundle\Form\BranchOfficeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BranchOfficeController extends Controller {

    public function indexAction($branchOfficeId, Request $request) {
        $data = array();
        $configuration = $this->get('lavaseco.app_configuration');

        $branchOffice = new BranchOffice();
      
        if($branchOfficeId != 0){
            $branchOffice  = $this->getBranchOfficeById($branchOfficeId);
        }
        
        $form = $this->createForm(BranchOfficeType::class, $branchOffice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->get('doctrine')->getManager();

                $branchOffice = $form->getData();
                $branchOffice->setBillContent($this->getBillContent());
                $em->persist($branchOffice);
                $em->flush();
        
                $form = $this->createForm(BranchOfficeType::class, $branchOffice);
                $data["messagePad"] = "Sucusal " . $branchOffice->getName() . " creada correctamente";
            } catch (\Doctrine\DBAL\DBALException $e) {
                $data["messagePad"] = "Sucusal " . $branchOffice->getName() . " no pudo ser creado, verifique que no exista una Sucusal con el mismo nombre";
                $data["errorPad"] = true;
            }
        }

        return $this->render($configuration->getViewTheme() . ':Settings/BranchOffice/index.html.twig'
                , ["form" => $form->createView(),"branchOffices" => $this->getBranchOffice()] + $data);
    }
    
    protected function getBranchOffice(){
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:BranchOffice");
        return $salePointRepository->findAll();
    }
    
    protected function getBranchOfficeById($branchOfficeId){
        $doctrineManager = $this->get('doctrine')->getManager();
        $salePointRepository = $doctrineManager->getRepository("LavasecoBundle:BranchOffice");
        return $salePointRepository->findOneById($branchOfficeId);
    }
    
    protected function getBillContent(){
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        return $billContentRepository->find(1);
    }
}
