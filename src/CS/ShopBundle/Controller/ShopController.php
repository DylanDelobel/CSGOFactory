<?php

namespace CS\ShopBundle\Controller;

use CS\ShopBundle\Entity\Weapon;
use CS\ShopBundle\ShopBundle;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;


class ShopController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $collection2 = $em->getRepository('ShopBundle:Collection')->findOneById(66);

        $repository = $this->getDoctrine()->getRepository('ShopBundle:Collection');

        $collection = $repository->findOneById(66);

        return $this->render('ShopBundle:Home:index.html.twig', array('Collection' => $collection));
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
            $listWeapons = $em->getRepository('ShopBundle:Weapon')->findSearchPagine($priceMin, $priceMax, $request->query->getInt('page', 1), 12);
        }else{
            $listWeapons = $em->getRepository('ShopBundle:Weapon')->findAllPagine($request->query->getInt('page', 1), 12);
        }







        return $this->render('ShopBundle:Shop:index.html.twig',
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

