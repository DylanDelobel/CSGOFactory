<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CartController extends Controller
{

    /**
     * @Route("/cart/" , name="cart")
     */
    public function cartAction()
    {
        //Init la session (use Session/Session)
        $session = new Session();

        //Si dans session le cart existe pas
        if (!$session->has('cart')){
            //alors crée un tableau cart
            $session->set('cart', array());
        }

        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        //Recupere une liste des weapons grace au id des weapons dans session['cart'] pour pouvoir afficher les info de l'objet dans la vue
        $listWeapons = $em->getRepository('ShopBundle:Weapon')->findArray(array_keys($session->get('cart')));

        //Retourne sur la vue du panier avec un array contenant deux variable $listWeapons et Le panier 'cart'
        return $this->render('ShopBundle:Cart:index.html.twig', array('listWeapons' => $listWeapons, 'cart' => $session->get('cart')));
    }
    /**
     * @Route("/add/{id}", name="cart_add")
     */
    //On recuper l'id de l'objet a ajouter au panier
    public function addAction($id)
    {
        //Init la session (use Session/Session)
        $session = new Session();


        //Si dans session le cart existe pas
        if (!$session->has('cart')){
            //alors crée un tableau cart
            $session->set('cart', array());
        }
        //On mais ce qui se trouve dans le panier dans la variable $cart
        $cart = $session->get('cart');

        //On verifie si l'objet est deja dans le panier
        if(array_key_exists($id,$cart)){
            //Si l'objet est deja dans le panier est que sa quantiter est superieur a 1 alors
            if($cart[$id] >= 1 ){
                //On ajoute plus 1
                $cart[$id] ++;
            }
        }else{
            //Sinon on ajoute juste l'objet avec une quantiter de 1
                $cart[$id] = 1;
        }

        //On le rajoute dans le panier de la session 'cart'
        $session->set('cart',$cart);
        //On ajoute dans le flash bag un message de réussite de l'ajoute de l'objet
        $session->getFlashBag()->add('success','Your weapon has been adde successfully');


        //On retourne dans le panier en génèrent l'url panier (cart)
        return $this->redirect($this->generateUrl('cart'));
    }
    /**
     * @Route("/delete/{id}", name="cart_delete")
     */
    //On recupere l'id de l'objet a supprimer
    public function deleteAction($id)
    {
        //Init la session (use Session/Session)
        $session = new Session();
        //On mais ce qui se trouve dans le panier dans la variable $cart
        $cart = $session->get('cart');

        //On verifie si l'objet est deja dans le panier
        if(array_key_exists($id,$cart)){
            //On le retire de la variable session['cart']
            unset($cart[$id]);
            //On mais a jour le panier (la variable)
            $session->set('cart',$cart);
            //On ajoute dans le flash bag un message de réussite de la suppression de l'objet
            $session->getFlashBag()->add('success','Your weapon has been removed successfully');
        }
        //On retourne dans le panier en génèrent l'url panier (cart)
        return $this->redirect($this->generateUrl('cart'));
    }


}