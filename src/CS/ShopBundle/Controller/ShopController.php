<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\ShopBundle;
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
        return $this->render('ShopBundle:Home:index.html.twig');
    }

    /**
     * @Route("/shop/")
     */
    public function shopAction(Request $request)
    {

        
        $em = $this->getDoctrine()->getManager();

        $listWeapons = $em->getRepository('ShopBundle:Weapon')->findAllPagineEtTrie($request->query->getInt('page', 1), 5);




        return $this->render('ShopBundle:Shop:index.html.twig',array('listWeapons' => $listWeapons));
    }

    /**
     * @Route("/contact/")
     */
    public function contactAction()
    {
        return $this->render('ShopBundle:Contact:index.html.twig');
    }
}
