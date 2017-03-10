<?php

namespace AppBundle\Controller\Rest;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModelsController extends Controller
{
    public function getModelsAction(){
        $em = $this->getDoctrine()->getManager();
        $models = $em->getRepository('AppBundle:Model')->findAll();
        if (empty($models)){
            return "No items";
        }
        return $models;
    }

    public function getModelAction($id){
        $em = $this->getDoctrine()->getManager();
        $model = $em->getRepository('AppBundle:Model')->find($id);
        if (empty($model)){
            return "No items";
        }
        return $model;
    }
}
