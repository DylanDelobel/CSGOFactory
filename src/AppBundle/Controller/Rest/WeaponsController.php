<?php

namespace AppBundle\Controller\Rest;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WeaponsController extends Controller
{
    public function getWeaponsAction(){
        $em = $this->getDoctrine()->getManager();
        $weapons = $em->getRepository('AppBundle:Weapon')->findAll();
        if (empty($weapons)){
            return "No items";
        }
        return $weapons;
    }

    public function getWeaponAction($id){
        $em = $this->getDoctrine()->getManager();
        $weapon = $em->getRepository('AppBundle:Weapon')->find($id);
        if (empty($weapon)){
            return "No items";
        }
        return $weapon;
    }
}
