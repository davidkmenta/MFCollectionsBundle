<?php

namespace MFCollectionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CollectionsBundle:Default:index.html.twig', array('name' => $name));
    }
}
