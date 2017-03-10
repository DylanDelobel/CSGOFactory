<?php

namespace AppBundle\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FamilliesController extends Controller
{
    public function getFamilliesAction(){
        $em = $this->getDoctrine()->getManager();
        $famillies = $em->getRepository('AppBundle:Family')->findAll();
        if (empty($famillies)){
            return "No items";
        }
        return $famillies;
    }

    public function getFamillieAction($id){
        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('AppBundle:Family')->find($id);
        if (empty($family)){
            return "No items";
        }
        return $family;
    }
}
