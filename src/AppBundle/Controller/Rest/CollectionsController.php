<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Collection;
use AppBundle\Entity\Image;
use AppBundle\Form\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class CollectionsController extends Controller
{
    public function getCollectionsAction(){
        $em = $this->getDoctrine()->getManager();
        $collections = $em->getRepository('AppBundle:Collection')->findAll();
        if (empty($collections)) {
            return array("code" => "404", "message" => "Not Found");
        }
        return $collections;
    }
    public function getCollectionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository('AppBundle:Collection')->find($id);
        if (empty($collection)) {
            return array("code" => "404", "message" => "Not Found");
        }
        return $collection;
    }

    public function postCollectionAction(Request $request) {
        $image = new Image();
        $image->setPath('http://placehold.it/256x198');

        $collection = new Collection();
        $collection->setName($request->get('name'));

        $form = $this->createForm(CollectionType::class, $collection);
        $form->submit($request->request->all());

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();
            return $collection;
        } else {
            return array("code" => "400", "message" => "Form is Invalid");
        }
    }

    public function putCollectionAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository('AppBundle:Collection')->find($id);

        if (empty($collection)) {
            return array("code" => "404", "message" => "Not Found");
        }

        $form = $this->createForm(CollectionType::class, $collection);
        $form->submit($request->request->all());

        if ($form->isValid()){
            $em->merge($collection);
            $em->flush();
            return array("code" => "200", "message" => "Ok");
        }
        return array("code" => "400", "message" => "Form is Invalid");
    }

    public function deleteCollectionAction($id){
        $em = $this->getDoctrine()->getManager();
        $collection = $em->getRepository('AppBundle:Collection')->find($id);

        if (empty($collection)) {
            return array("code" => "404", "message" => "Not Found");
        }

        $em->remove($collection);
        $em->flush();
        return array("code" => "200", "message" => "Ok");
    }
}

