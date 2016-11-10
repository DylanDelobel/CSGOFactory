<?php

namespace CS\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ShopController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('CSShopBundle:Home:index.html.twig');
    }

    /**
     * @Route("/shop")
     */
    public function shopAction()
    {
        return $this->render('CSShopBundle:Shop:index.html.twig');
    }
}
