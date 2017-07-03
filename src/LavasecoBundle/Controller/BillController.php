<?php

namespace LavasecoBundle\Controller;

use LavasecoBundle\Form\BillContentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BillController extends Controller {

    public function settingAction(Request $request) {
        $configuration = $this->get('lavaseco.app_configuration');

        $billContent = $this->getBillContentById(1);

        $form = $this->createForm(BillContentType::class, $billContent);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $billContent = $form->getData();

            $em->persist($billContent);

            $em->flush();
        }

        return $this->render($configuration->getViewTheme() . ':Settings/Bill/index.html.twig', [
                    'form' => $form->createView(),
                    'billContent' => $billContent,
        ]);
    }

    private function getBillContentById($billContentId) {
        $doctrineManager = $this->get('doctrine')->getManager();
        $billContentRepository = $doctrineManager->getRepository("LavasecoBundle:BillContent");

        return $billContentRepository->find($billContentId);
    }

}
