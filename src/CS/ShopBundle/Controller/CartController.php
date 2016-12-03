<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\Entity\Weapon;
use CS\ShopBundle\ShopBundle;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class CartController extends Controller
{

    /**
     * @Route("/cart/" , name="cart")
     */
    public function cartAction()
    {
        $session = new Session();

        if (!$session->has('cart')){
            $session->set('cart', array());
        }

        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        $listWeapons = $em->getRepository('ShopBundle:Weapon')->findArray(array_keys($session->get('cart')));


        return $this->render('ShopBundle:Cart:index.html.twig', array('listWeapons' => $listWeapons, 'cart' => $session->get('cart')));
    }
    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function addAction($id)
    {

        $session = new Session();



        if (!$session->has('cart')){
            $session->set('cart', array());
        }

        $cart = $session->get('cart');


        if(array_key_exists($id,$cart)){
            if($cart[$id] >= 1 ){
                $cart[$id] ++;
            }
        }else{
                $cart[$id] = 1;
        }

        $session->set('cart',$cart);
        $session->getFlashBag()->add('success','Your weapon has been adde successfully');



        return $this->redirect($this->generateUrl('cart'));
    }
    /**
     * @Route("/delete/{id}", name="cart_delete")
     */
    public function deleteAction($id)
    {
        $session = new Session();
        $cart = $session->get('cart');

        if(array_key_exists($id,$cart)){
            unset($cart[$id]);
            $session->set('cart',$cart);
            $session->getFlashBag()->add('success','Your weapon has been removed successfully');
        }
        return $this->redirect($this->generateUrl('cart'));
    }

}