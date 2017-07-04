<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DescriptionController extends Controller
{
    public function settingAction()
    {       
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':Settings/Descriptions/index.html.twig');
    }
}
