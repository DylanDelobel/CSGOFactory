<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Crate;
use AppBundle\Entity\Image;
use AppBundle\Form\Type\CrateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class CratesController extends Controller
{
    public function getCratesAction(){
        $em = $this->getDoctrine()->getManager();
        $crates = $em->getRepository('AppBundle:Crate')->findAll();
        if (empty($crates)) {
            return array("code" => "404", "message" => "Not Found");
        }
        return $crates;
    }
    public function getCrateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crate = $em->getRepository('AppBundle:Crate')->find($id);
        if (empty($crate)) {
            return array("code" => "404", "message" => "Not Found");
        }
        return $crate;
    }

    public function postCrateAction(Request $request) {
        $image = new Image();
        $image->setPath('http://placehold.it/256x198');

        $crate = new Crate();
        $crate->setName($request->get('name'));

        $form = $this->createForm(CrateType::class, $crate);
        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($crate);
            $em->flush();
            return $crate;
        } else {
            return array("code" => "400", "message" => "Form is Invalid");
        }
    }

    public function putCrateAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $crate = $em->getRepository('AppBundle:Crate')->find($id);

        if (empty($crate)) {
            return array("code" => "404", "message" => "Not Found");
        }

        $form = $this->createForm(CrateType::class, $crate);
        $form->submit($request->request->all());

        if ($form->isValid()){
            $em->merge($crate);
            $em->flush();
            return array("code" => "200", "message" => "Ok");
        }
        return array("code" => "400", "message" => "Form is Invalid");
    }

    public function deleteCrateAction($id){
        $em = $this->getDoctrine()->getManager();
        $crate = $em->getRepository('AppBundle:Crate')->find($id);

        if (empty($crate)) {
            return array("code" => "404", "message" => "Not Found");
        }

        $em->remove($crate);
        $em->flush();
        return array("code" => "200", "message" => "Ok");
    }
}

