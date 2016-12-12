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

    public function setDeliveryOneSession($request){

        $session = new Session();

        if(!$session->has('address')){
            $session->set('address',array());
        }else{
            $address = $session->get('address');
        }

        if ($request->request->get('Delivery') != null && $request->request->get('Billing') != null){
            $address['Delivery'] = $request->request->get('Delivery');
            $address['Billing'] = $request->request->get('Billing');
        }else{
            return $this->redirect($this->generateUrl('checkout_validation'));
        }
        $session->set('address',$address);

        return $this->redirect($this->generateUrl('checkout_validation'));
    }

    /**
     * @Route("/checkout/details/validation", name="checkout_validation")
     */
    public function validationAction(Request $request)
    {
        if ($request->getMethod() == 'POST'){
            $this->setDeliveryOneSession($request);
        }

        $em = $this->getDoctrine()->getManager();
        $session = new Session();

        $address = $session->get('address');

        $cart = $session->get('cart');
        $listWeapons = $em->getRepository('ShopBundle:Weapon')->findArray(array_keys($cart));
        $delivery = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($address['Delivery']);
        $billing = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($address['Billing']);



        return $this->render('ShopBundle:Checkout:validation.html.twig', array('listWeapons' => $listWeapons, 'delivery' =>$delivery, 'billing' =>$billing, 'cart' => $cart));
    }

    /**
     * @Route("/checkout/details/delete/{id}", name="address_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $infoUserDelete = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($id);

        //Verfi si l'utilisateur qui suprimer est bien celui connecter
        if ($this->container->get('security.token_storage')->getToken()->getUser() != $infoUserDelete->getUser() || !$infoUserDelete){
            return $this->redirectToRoute('checkout_address');
        }

        $em->remove($infoUserDelete);
        $em->flush();

        return $this->redirectToRoute('checkout_address');
    }






}