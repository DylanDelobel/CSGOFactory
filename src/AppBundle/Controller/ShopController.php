<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ShopController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Get Collection Repository
        $repositoryCollection = $this->getDoctrine()->getRepository('AppBundle:Collection');

        // Retrieve 3 collection based on date
        $listCollection = $repositoryCollection->findBy(
                array(),
                array( 'date' => 'desc'),
                3,
                1
            );

        // Retrieve 4 weapons for each collection
        foreach ($listCollection as $uneCollection){
            $id = $repositoryCollection->findOneByName($uneCollection->getName())->getId();
            $listWeapons[] = $em->getRepository('AppBundle:Weapon')->findByCollectionId($id);
        }

        return $this->render(
            'AppBundle:Home:index.html.twig',
            array(
                'listCollection' => $listCollection,
                'listWeapons' => $listWeapons,
            ));
    }

    /**
     * @Route("/shop/", name="shop")
     */
    public function shopAction(Request $request)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Weapon')->createQueryBuilder('weapons');

        if (($request->query->getAlnum('filter'))) {
            $queryBuilder->where('weapons.name LIKE :name')
                ->setParameter('name', '%' . $request->query->getAlnum('filter') . '%');
        }
        if (($request->query->getAlnum('price'))) {
            $queryBuilder->where('weapons.price LIKE :price')
                ->setParameter('price', '%' . $request->query->getAlnum('price') . '%');
        }

        $query = $queryBuilder;

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('LIMIT', 10)
        );

        return $this->render('AppBundle:Shop:index.html.twig', [
            'listWeapons' => $result,
        ]);
    }
    /**
     * @Route("/shop/{family}/{model}", name="catalog")
     */
    public function catalogAction(Request $request, $family, $model)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        $modelRepository = $em->getRepository("AppBundle:Model");
        $listModels = $modelRepository->findByName($model);

        $queryBuilder = $em->getRepository('AppBundle:Weapon')->createQueryBuilder('weapons');

        if ($model) {
            $queryBuilder->where('weapons.model = :model')
                ->setParameter('model', $listModels);
        }

        if ($request->query->getAlnum('filter')) {
            $queryBuilder->andWhere('weapons.name LIKE :name')
                ->setParameter('name', '%' . $request->query->getAlnum('filter') . '%');
        }
        if ($request->query->getAlnum('price')) {
            $queryBuilder->andWhere('weapons.price LIKE :price')
                ->setParameter('price', '%' . $request->query->getAlnum('price') . '%');
        }

        $query = $queryBuilder;

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('LIMIT', 10)
        );

        return $this->render('AppBundle:Shop:index.html.twig', [
            'listWeapons' => $result,
            'parameters' => array('model' => $model, 'family' => $family)
        ]);
    }

}

