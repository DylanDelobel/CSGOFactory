<?php
namespace AppBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RedirectionListener
{
    //Recuperation des information en cour du Container et de la session
    public function __construct(ContainerInterface $container, Session $session)
    {
        //Init la session
        $this->session = $session;
        //Init la route
        $this->router = $container->get('router');
        //Init l'utilisateur
        $this->securityContext = $container->get('security.token_storage');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        //On recuper la route courant
        $route = $event->getRequest()->attributes->get('_route');

        // Si la route est egale a une des c'est valeur
        if ($route == 'checkout_details' || $route == 'checkout_validation' || $route == 'checkout_address' || $route == 'checkout_buy' || $route == 'address_delete') {
            //On verifie que dans session il y a bien cart
            if ($this->session->has('cart')) {
                //On verifie qu'il y a bien des objet dans le panier 'cart' == 0 pas d'objet
                if (count($this->session->get('cart')) == 0){
                    //alors on retourne sur la page panier
                    $event->setResponse(new RedirectResponse($this->router->generate('cart')));
                }
            }


            // Si l'utilisateur est bien Connecter Sinon
            if (!is_object($this->securityContext->getToken()->getUser())) {
                //On ajoute dnas le flashBag qu'il faut se connecter
                $this->session->getFlashBag()->add('notification','You must be logged in');
                //On retourne sur la page de login
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
            }
        }
    }
}