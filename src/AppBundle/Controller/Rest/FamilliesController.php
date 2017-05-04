<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Family;
use AppBundle\Form\Type\FamilyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class FamilliesController extends Controller
{
    public function getFamilliesAction(){
        $em = $this->getDoctrine()->getManager();
        $famillies = $em->getRepository('AppBundle:Family')->findAll();
        if (empty($famillies)){
            return array("code" => "404", "message" => "Not Found");
        }
        return $famillies;
    }

    public function getFamillieAction($id){
        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('AppBundle:Family')->find($id);
        if (empty($family)){
            return array("code" => "404", "message" => "Not Found");
        }
        return $family;
    }

    public function postFamillieAction(Request $request)
    {
        $family = new Family();
        $family->setName($request->get('name'));

        $form = $this->createForm(FamilyType::class, $family);
        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($family);
            $em->flush();
            return $family;
        }
        return array("code" => "400", "message" => "Form is Invalid");
    }

    public function putFamillieAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('AppBundle:Family')->find($id);

        if (empty($family)) {
            return array("code" => "404", "message" => "Not Found");
        }

        $form = $this->createForm(FamilyType::class, $family);
        $form->submit($request->request->all());

        if ($form->isValid()){
            $em->merge($family);
            $em->flush();
            return array("code" => "200", "message" => "Ok");
        }
        return array("code" => "400", "message" => "Form is Invalid");
    }

    public function deleteFamillieAction($id){
        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('AppBundle:Family')->find($id);

        if (empty($family)) {
            return array("code" => "404", "message" => "Not Found");
        }

        $em->remove($family);
        $em->flush();
        return array("code" => "200", "message" => "Ok");
    }

}

