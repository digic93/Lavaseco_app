<?php

namespace LavasecoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $configuration = $this->get('lavaseco.app_configuration');
        return $this->render($configuration->getViewTheme() . ':index.html.twig');
    }
}
