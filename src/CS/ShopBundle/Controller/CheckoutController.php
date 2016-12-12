<?php

namespace CS\ShopBundle\Controller;


use CS\ShopBundle\Entity\User;
use CS\ShopBundle\Entity\UsersAdressesInfo;
use CS\ShopBundle\ShopBundle;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;




class CheckoutController extends Controller
{

    /**
     * @Route("/checkout/address" , name="checkout_address")
     */
    public function addressAction()
    {
        $em = $this->getDoctrine()->getManager();


        $userId =$this->container->get('security.token_storage')->getToken()->getUser()->getId();


        $infosUser = $em->getRepository('ShopBundle:UsersAdressesInfo')->findBy(array('user' => $userId));

        return $this->render('ShopBundle:Checkout:selectAddress.html.twig', array('infosUser'=> $infosUser));
    }

    /**
     * @Route("/checkout/details" , name="checkout_details")
     */
    public function detailsAction(Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $usersAdressesInfo = new UsersAdressesInfo();

        // On crée le FormBuilder
        $formBuilder = $this->createFormBuilder($usersAdressesInfo);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('emailPro', EmailType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('phone', TextType::class, array(
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

            ->add('cp', TextType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))

            ->add('country', CountryType::class, array(
                'required'    => true,
                'empty_data'  => null
            ))


            ->add('save', SubmitType::class, array('label' => 'Place My Order'));

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $usersAdressesInfo = $form->getData();

            $usersAdressesInfo->setUser($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($usersAdressesInfo);
            $em->flush();


            return $this->redirectToRoute('checkout_address');
        }

        return $this->render('ShopBundle:Checkout:index.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/checkout/details/delete/{id}", name="address_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $infoUserDelete = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($id);

        $em->remove($infoUserDelete);
        $em->flush();

        return $this->redirectToRoute('checkout_address');
    }


}