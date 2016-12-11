<?php
namespace CS\ShopBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RedirectionListener
{
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->session = $session;
        $this->router = $container->get('router');
        $this->securityContext = $container->get('security.token_storage');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        //On recuper la route courant
        $route = $event->getRequest()->attributes->get('_route');

        if ($route == 'checkout_details' || $route == 'validation') {
            if ($this->session->has('cart')) {
                if (count($this->session->get('cart')) == 0){
                    $event->setResponse(new RedirectResponse($this->router->generate('cart')));
                }
            }
            // Si l'utilisateur est bien Connecter
            if (!is_object($this->securityContext->getToken()->getUser())) {
                $this->session->getFlashBag()->add('notification','You must be logged in');
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
            }
        }
    }
}