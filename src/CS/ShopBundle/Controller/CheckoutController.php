<?php

namespace CS\ShopBundle\Controller;


use CS\ShopBundle\ShopBundle;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;



class CheckoutController extends Controller
{

    /**
     * @Route("/checkout/details" , name="checkout_details")
     */
    public function detailsAction()
    {

        // On crée le FormBuilder
        $formBuilder = $this->createFormBuilder();

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('email', EmailType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('phone', IntegerType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('firstName', TextType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('lastName', TextType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('streetAddress', TextType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('city', TextType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('postalCode', TextType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('country', CountryType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))


            ->add('searchBtn', SubmitType::class, array('label' => 'Place My Order'));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        return $this->render('ShopBundle:Checkout:index.html.twig', array('form' => $form->createView()));

    }


}