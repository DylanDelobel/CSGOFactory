<?php

namespace AppBundle\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionsController extends Controller
{
    public function getCollectionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $crateRepository = $em->getRepository('AppBundle:Collection');
        $crates = $crateRepository->findAll();
        return $crates;
    }

    public function getCollectionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crateRepository = $em->getRepository('AppBundle:Collection');
        $crate = $crateRepository->findOneById($id);

        if (empty($crate)) {
            return new JsonResponse(['message' => 'Collection not found'], Response::HTTP_NOT_FOUND);
        }

        return $crate;
    }
}

