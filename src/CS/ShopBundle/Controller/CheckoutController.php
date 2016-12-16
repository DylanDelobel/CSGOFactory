<?php

namespace CS\ShopBundle\Controller;


use CS\ShopBundle\Entity\OrderC;
use CS\ShopBundle\Entity\UsersAdressesInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;





class CheckoutController extends Controller
{

    /**
     * @Route("/checkout/address" , name="checkout_address")
     */
    public function addressAction()
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        //On recupere l'id de l'utilisateur en cour
        $userId =$this->container->get('security.token_storage')->getToken()->getUser()->getId();

        //On recupere tout les info des addresse de l'utilisateur pour pouvoir les afficher dans la vue
        $infosUser = $em->getRepository('ShopBundle:UsersAdressesInfo')->findBy(array('user' => $userId));

        //On retourne la page du choix des addresse est on envoie les infosUser a la vue
        return $this->render('ShopBundle:Checkout:selectAddress.html.twig', array('infosUser'=> $infosUser));
    }

    /**
     * @Route("/checkout/details" , name="checkout_details")
     */
    public function detailsAction(Request $request)
    {

        //On recuper les information de l'utilisateur en cour
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        //Init les UsersAdressesInfo
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

        //On recupere les info Post est on les insere dans le $form
        $form->handleRequest($request);

        //On verifie suis on a bient Submitted et que les form est bien valide
        if($form->isSubmitted() && $form->isValid()){
            //L'on mais tout les donner du formulaire dans $usersAddresseInfo (les champs sont les meme que ce de la basse de donner)
            $usersAdressesInfo = $form->getData();

            //On cette l'utillisateur pour lui affecter l'addresse
            $usersAdressesInfo->setUser($user);

            //Entity Manager
            $em = $this->getDoctrine()->getManager();

            //On persist $userAdressesInfo
            $em->persist($usersAdressesInfo);
            //Puis on envoie tout
            $em->flush();

            //On retourne sur le choix des addresse
            return $this->redirectToRoute('checkout_address');
        }

        //On retourne sur la vue pour ajouter une nouvelle addresse avec les formulaire
        return $this->render('ShopBundle:Checkout:index.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/checkout/details/validation", name="checkout_validation")
     */
    public function validationAction(Request $request)
    {
        //verification que le $request et bien un POST
        if ($request->getMethod() == 'POST'){
            // utiliser la function setDeliveryOneSession (les addressec sont ajouter directement dans la variable session)
            $this->setDeliveryOneSession($request);
        }
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        //On utilise la function preparesOrder on recuper la valeur dans pOrder (On recuper l'id de la commande)
        $pOrder = $this->forward('ShopBundle:Checkout:preparesOrder');
        //On va chercher les information de la commande grace a sont id
        $order = $em->getRepository('ShopBundle:OrderC')->find($pOrder->getContent());


        //On retourne sur la page de validation en donnent les infromation sur la commande
        return $this->render('ShopBundle:Checkout:validation.html.twig', array('listOrder' => $order ));
    }

    /**
     * @Route("/checkout/details/delete/{id}", name="address_delete")
     */
    public function deleteAction($id)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        // on recupere l'addresse a supprimer
        $infoUserDelete = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($id);

        //Verfi si l'utilisateur qui supprimer est bien celui connecter
        if ($this->container->get('security.token_storage')->getToken()->getUser() != $infoUserDelete->getUser() || !$infoUserDelete){
            //retourne checkout_address
            return $this->redirectToRoute('checkout_address');
        }

        //remove pour la base
        $em->remove($infoUserDelete);
        //execute la requete
        $em->flush();

        //retourne sur checkout_address
        return $this->redirectToRoute('checkout_address');
    }

    /**
     * @Route("/checkout/details/validation/buy/{id}", name="checkout_buy")
     */
    public function buyPaypalValidationAction($id){

        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        //Recuperation de la commande dans la base de donner grace a l'id
        $order = $em->getRepository('ShopBundle:OrderC')->find($id);

        //verification si la commande existe et si elle a deja été traiter
        if (!$order || $order->getValidate() == 1){
            //Si oui erreur
            throw $this->createNotFoundException("The order don't exist");
        }
        //Init Session
        $session = new Session();
        //remove dans la session l'address
        $session->remove('address');
        //remove dans la session cart
        $session->remove('cart');
        //remove dans la session order
        $session->remove('order');

        //On set la validation a 1 pour dire que la commande a été traiter
        $order->setValidate(1);
        //on persist
        $em->persist($order);
        //et on envoie
        $em->flush();


        //On retourne sur la page Billing et on envoie l'id
        return $this->render('ShopBundle:Checkout:billing.html.twig',array('orderId' => $id));
    }

    /**
     * @Route("/checkout/details/validation/pdf/{id}", name="checkout_pdf")
     * @return Response
     */
    public function htmlToPdfAction($id){
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        //On recuper les differente donner pour la vue a transformer
        //recupere la commande
        $order = $em->getRepository('ShopBundle:OrderC')->find($id);

        //Appele du bundle KNP_SNAPPY
        $snappy = $this->get('knp_snappy.pdf');
        //recuperation de la vue avec envoie des donner a traiter
        $html = $this->renderView('ShopBundle:Checkout:orderPdf.html.twig', array('order' => $order));

        //Nom du fichier a télécharger
        $fileName = 'BillingCSGOFactory';

        //envoie tout les reponse et le transforme en PDF
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$fileName.'.pdf"'
            )
        );

    }

    public function setDeliveryOneSession($request){
        //Init Session
        $session = new Session();

        //Verififaction si address existe dans la session
        if(!$session->has('address')){
            //alors crée addresse
            $session->set('address',array());
        }else{
            //sinon recupere les information stocker
            $address = $session->get('address');
        }
        //Si les adresse Delivery et Billing sont different de null
        if ($request->request->get('Delivery') != null && $request->request->get('Billing') != null){
            //alors on cette les deux dans la variable $address
            $address['Delivery'] = $request->request->get('Delivery');
            $address['Billing'] = $request->request->get('Billing');
        }else{
            //On retourne sur la page validation
            return $this->redirect($this->generateUrl('checkout_validation'));
        }
        //Mise a jours des deux addresse
        $session->set('address',$address);

        //retourne sur la page de validation
        return $this->redirect($this->generateUrl('checkout_validation'));
    }

    public function bill(){
        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        //Init Session
        $session = new Session();
        //On recuper les adresse de la session
        $adresse = $session->get('address');
        //On recuper le panier dans la session
        $cart = $session->get('cart');
        //Init order
        $order = array();
        //Init totalHT
        $totalHT = 0;
        //Init totalTTC
        $totalTTC = 0;

        //l'on va chercher l'adresse de delivery dans la base de donner grace a l'id
        $delivery = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($adresse['Delivery']);
        //l'on va chercher l'adresse de billing dans la base de donner grace a l'id
        $billing = $em->getRepository('ShopBundle:UsersAdressesInfo')->find($adresse['Billing']);
        //l'on va chercher dan la base de donner la liste des weapons (information) grace au donner dans la session 'cart'
        $listWeapons = $em->getRepository('ShopBundle:Weapon')->findArray(array_keys($session->get('cart')));

        
        foreach($listWeapons as $weapons)
        {
            //On recuper les prixHT de l'objet que l'on multi par la quantiter
            $priceHT = $weapons->getPrice() * $cart[$weapons->getId()];
            //On recuper les prixTTC de l'objet que l'on multi par la quantiter et la taxe
            $priceTTC = ($weapons->getPrice() * $cart[$weapons->getId()] * (1.20));
            //On fait le totalHT a chauqe boucle
            $totalHT += $priceHT;
            //Ont fait le totalTTC a chaque boucle
            $totalTTC += $priceTTC;


            //L'on fait un tableau avec les information de l'objet pour chaque objet Nom, Quantité, prixHT, prixTTC
            $order['weapons'][$weapons->getId()] = array('Name' => $weapons->getName(),
                'quantite' => $cart[$weapons->getId()],
                'prixHT' => round($weapons->getPrice(),2),
                'prixTTC' => round($weapons->getPrice() * (1.20)));
        }

        // l'on fait un tableau avec tout les information des addresse de livraison et de facturation
        $order['Delivery'] = array('firstName' => $delivery->getFirstName(),
            'lastName' => $delivery->getLastName(),
            'phone' => $delivery->getPhone(),
            'adresse' => $delivery->getStreetAddress(),
            'cp' => $delivery->getCp(),
            'city' => $delivery->getCity(),
            'country' => $delivery->getCountry(),
            'emailPro' => $delivery->getEmailPro());
        $order['Billing'] = array('firstName' => $billing->getFirstName(),
            'lastName' => $billing->getLastName(),
            'phone' => $billing->getPhone(),
            'adresse' => $billing->getStreetAddress(),
            'cp' => $billing->getCp(),
            'city' => $billing->getCity(),
            'country' => $billing->getCountry(),
            'emailPro' => $billing->getEmailPro());

        //tableau prix TotalHT
        $order['prixHT'] = round($totalHT,2);
        //tableau prix TotalTTC
        $order['prixTTC'] = round($totalTTC,2);

        //retourn la commande ($order)
        return $order;
    }

    public function preparesOrderAction()
    {
        //Init Session
        $session = new Session();

        //Entity Manager
        $em = $this->getDoctrine()->getManager();

        //verification si order existe dans la variable session
        if (!$session->has('order')){
            //Init OrderC
            //Pour crée une nouvelle commande
            $order = new OrderC();
        }else{
            //recupere la commande dans la base de doner et la mettre dans $order
            $order = $em->getRepository('ShopBundle:OrderC')->find($session->get('order'));
        }
        //set tout les information
        //Date
        $order->setDate(new \DateTime());
        //l'utilisateur via l'utilisateur courant (qui et connecter)
        $order->setUser($this->container->get('security.token_storage')->getToken()->getUser());
        //si la commande a été payer valeur par defaut 0
        $order->setValidate(0);
        //Pour la reference (pas de reference pour le moment a a ajouter dans une future maj)
        $order->setRef(0);
        // On cette la commande pour la mettre en base de donner avec tout les information (bill() crée un tableau avec tout les information)
        $order->setListOrder($this->bill());

        //verification si order existe dans la variable session
        if (!$session->has('order')) {
            //On persist la commande
            $em->persist($order);
            //On set la commande dans la session pour l'avoir dans la variable session
            $session->set('order',$order);
        }

        //On envoie tout dans la base de donner
        $em->flush();

        //envoie l'id de la commande
        return new Response($order->getId());

    }








}