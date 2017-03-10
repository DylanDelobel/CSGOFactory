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
        }else{
            return $form;
        }

    }

    public function deleteFamillieAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('AppBundle:Family')->find($id);


        $em->remove($family);
        $em->flush();
    }

    public function updateFamillieAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $family = $em->getRepository('AppBundle:Family')->find($id);

        if (empty($family)) {
            return new JsonResponse(['message' => 'Family not update'], Response::HTTP_NOT_MODIFIED);
        }

        $form = $this->createForm(FamilyType::class, $family);
        $form->submit($request->request->all());

        if ($form->isValid()){
            $em->merge($family);
            $em->flush();
        }else{
            return $form;
        }
    }



}

