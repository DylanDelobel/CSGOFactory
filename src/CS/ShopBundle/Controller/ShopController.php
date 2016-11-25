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
        $em = $this->getDoctrine()->getManager();

        $collection2 = $em->getRepository('ShopBundle:Collection')->findOneById(66);

        $repository = $this->getDoctrine()->getRepository('ShopBundle:Collection');

        $collection = $repository->findOneById(66);

        return $this->render('ShopBundle:Home:index.html.twig', array('Collection' => $collection));
    }

    /**
     * @Route("/shop/")
     */
    public function shopAction(Request $request)
    {

        
        $em = $this->getDoctrine()->getManager();

        




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
