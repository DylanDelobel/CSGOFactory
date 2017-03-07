<?php

namespace AppBundle\Controller;

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

        // On crée le FormBuilder
        $formBuilder = $this->createFormBuilder();

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('search', SearchType::class, array(
                'required'    => false,
                'empty_data'  => null
            ))
            ->add('wear', ChoiceType::class, array(
                'choices' => array(
                    'Factory-New' => 'Factory New',
                    'Minimal-Wear'=> 'Minimal Wear',
                    'Field-Tested'=> 'Field-Tested',
                    'Well-Worn'=> 'Well-Worn',
                    'Battle-Scarred'=> 'Battle-Scarred'
                ),
                'required'    => false,
                'empty_data'  => null
            ))
            ->add('priceMin', IntegerType::class, array(
                'required'    => false,
                'empty_data'  => ""
            ))

            ->add('priceMax', IntegerType::class, array(
                'required'    => false,
                'empty_data'  => ""
            ))
            ->add('searchBtn', SubmitType::class, array('label' => 'Search'));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        $search = $wear = $priceMin = $priceMax = null;
        // Si la requête est en POST
        if($request->isMethod('POST')){
            $form->handleRequest($request);

            $search = $form['search']->getData();
            $wear = $form['wear']->getData();
            $priceMin = $form['priceMin']->getData();
            $priceMax = $form['priceMax']->getData();
        }

        if(($search != null) | ($wear != null) | ($priceMin != null) | ($priceMax != null)){
            $listWeapons = $em->getRepository('AppBundle:Weapon')->findSearchPagine($priceMin, $priceMax, $request->query->getInt('page', 1), 12);
        }else{
            $listWeapons = $em->getRepository('AppBundle:Weapon')->findAllPagine($request->query->getInt('page', 1), 12);
        }

        return $this->render('AppBundle:Shop:index.html.twig',
            array(
                'listWeapons' => $listWeapons,
                'form' => $form->createView(),
                'search' => $search,
                'wear' => $wear,
                'priceMin' => $priceMin,
                'priceMax' => $priceMax
            ));
    }
    /**
     * @Route("/shop/{family}/{model}", name="catalog")
     */
    public function catalogAction(Request $request, $family, $model)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        $modelRepository = $em->getRepository('AppBundle:Model');

        $id = $modelRepository->findOneByName($model)->getId();

        // On crée le FormBuilder
        $formBuilder = $this->createFormBuilder();

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('search', SearchType::class, array(
                'required'    => false,
                'empty_data'  => null
            ))
            ->add('wear', ChoiceType::class, array(
                'choices' => array(
                    'Factory-New' => 'Factory New',
                    'Minimal-Wear'=> 'Minimal Wear',
                    'Field-Tested'=> 'Field-Tested',
                    'Well-Worn'=> 'Well-Worn',
                    'Battle-Scarred'=> 'Battle-Scarred'
                ),
                'required'    => false,
                'empty_data'  => null
            ))
            ->add('priceMin', IntegerType::class, array(
                'required'    => false,
                'empty_data'  => ""
            ))

            ->add('priceMax', IntegerType::class, array(
                'required'    => false,
                'empty_data'  => ""
            ))
            ->add('searchBtn', SubmitType::class, array('label' => 'Search'));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        $search = $wear = $priceMin = $priceMax =null;
        // Si la requête est en POST
        if($request->isMethod('POST')){
            $form->handleRequest($request);

            $search = $form['search']->getData();
            $wear = $form['wear']->getData();
            $priceMin = $form['priceMin']->getData();
            $priceMax = $form['priceMax']->getData();
        }

        if(($search != null) | ($wear != null) | ($priceMin != null) | ($priceMax != null)){
            $listWeapons = $em->getRepository('AppBundle:Weapon')->findSearchPagine($priceMin, $priceMax, $request->query->getInt('page', 1), 12);
        }else{
            $listWeapons = $em->getRepository('AppBundle:Weapon')->findByModelId($id, $request->query->getInt('page', 1), 12);
        }

        return $this->render('AppBundle:Shop:index.html.twig',
            array(
                'listWeapons' => $listWeapons,
                'form' => $form->createView(),
                'search' => $search,
                'wear' => $wear,
                'priceMin' => $priceMin,
                'priceMax' => $priceMax
            ));
    }

}

