<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\CSShopBundle;
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
        $em = $this->getDoctrine()->getManager();

        $listWeapons = $em->getRepository('CSShopBundle:Weapon')->findAll();



        return $this->render('CSShopBundle:Shop:index.html.twig',array('listWeapons' => $listWeapons));
    }
}
