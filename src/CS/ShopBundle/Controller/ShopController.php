<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\CSShopBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/shop/")
     */
    public function shopAction(Request $request)
    {

        
        $em = $this->getDoctrine()->getManager();

        $listWeapons = $em->getRepository('CSShopBundle:Weapon')->findAllPagineEtTrie($request->query->getInt('page', 1), 5);




        return $this->render('CSShopBundle:Shop:index.html.twig',array('listWeapons' => $listWeapons));
    }
}
