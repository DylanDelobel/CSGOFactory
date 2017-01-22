<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\Entity\Crate;
use CS\ShopBundle\Entity\Image;
use CS\ShopBundle\Form\CrateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 * @package CS\ShopBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/panel" , name="admin.panel")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $crateRepo = $em->getRepository('ShopBundle:Crate');
        $crateList = $crateRepo->findAll();

        return $this->render('ShopBundle:Admin:adminpanel.html.twig', ['crateList' => $crateList]);
    }

    /**
     * @Route("/admin/crate/add" , name="admin.crate.add")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $crate = new Crate();
        $form = $this->createForm(CrateType::class, $crate);
        $form->add('Create', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $crate->setDate(new \DateTime());
            $img = new Image();
            $img->setPath('http://placehold.it/200x200');
            $crate->setImage($img);
            $em->persist($crate);
            $em->flush();
            return $this->redirectToRoute('admin.panel');
        }

        return $this->render('ShopBundle:Admin:crate.add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/crate/edit/{id}" , name="admin.crate.edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Crate $crate
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Crate $crate)
    {
        $form = $this->createForm(CrateType::class, $crate);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin.panel');
        }

        return $this->render('ShopBundle:Admin:crate.edit.html.twig', [
            'form' => $form->createView(),
            'crate' => $crate
        ]);
    }


}
