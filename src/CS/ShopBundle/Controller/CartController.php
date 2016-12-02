<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\Entity\Weapon;
use CS\ShopBundle\ShopBundle;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;



class CartController extends Controller
{

    /**
     * @Route("/cart/" , name="cart")
     */
    public function cartAction()
    {
        return $this->render('ShopBundle:Cart:index.html.twig');
    }
    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function addAction()
    {
        return $this->render('ShopBundle:Cart:index.html.twig');
    }
    /**
     * @Route("/delete/{id}", name="cart_delete")
     */
    public function deleteAction()
    {
        return $this->render('ShopBundle:Cart:index.html.twig');
    }

}