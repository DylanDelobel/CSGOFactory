<?php

namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Family;
use AppBundle\Entity\Model;
use AppBundle\Form\Type\ModelType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class ModelsController extends Controller
{

    public function getModelsAction(){
        $em = $this->getDoctrine()->getManager();
        $models = $em->getRepository('AppBundle:Model')->findAll();
        if (empty($models)){
            return array("code" => "404", "message" => "Not Found");
        }

        return $models;
    }

    public function getModelAction($id){
        $em = $this->getDoctrine()->getManager();
        $model = $em->getRepository('AppBundle:Model')->find($id);
        if (empty($model)){
            return array("code" => "404", "message" => "Not Found");
        }
        return $model;
    }

    public function postModelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $search = $request->get('family');

        if(is_numeric($search)){
            $family = $em->getRepository('AppBundle:Family')->find($search);
        }

        if(!empty($family)){
            $model = new Model();
            $model->setName($request->get('name'));
            $model->setFamily($family);

            $form = $this->createForm(ModelType::class,$model);
            $form->submit($request->request->all());

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();
                return $model;
            }else{
                return array("code" => "400", "message" => "Form is Invalid");
            }
        }
        return array("code" => "404", "message" => "Not Found");
    }

    // FAIRE LE "PUT" ET LE "DELETE"
}
