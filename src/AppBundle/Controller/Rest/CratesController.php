<?php

namespace AppBundle\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CratesController extends Controller
{
    public function getCratesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $crateRepository = $em->getRepository('AppBundle:Crate');
        $crates = $crateRepository->findAll();
        return $crates;
    }

    public function getCrateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $crateRepository = $em->getRepository('AppBundle:Crate');
        $crate = $crateRepository->findOneById($id);

        if (empty($crate)) {
            return new JsonResponse(['message' => 'Crate not found'], Response::HTTP_NOT_FOUND);
        }

        return $crate;
    }
}

