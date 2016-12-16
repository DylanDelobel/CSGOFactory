<?php

namespace CS\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ContactController extends Controller
{

    /**
     * @Route("/contact/", name="contact")
     */
    public function contactAction(Request $request) //Reçevoir l'objet lors de la validation du formulaire
    {
        $cleanName = $cleanEmail = $cleanSubject = $cleanData = $result = null;

        //Création d'un formulaire sans entité
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('subject', TextType::class)
            ->add('message', TextareaType::class)
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        //Traiter les données reçu
        $form->handleRequest($request);

        //Si on appuie sur le btn envoyer et que le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            //Mise en variable des données nécessaires
            $name = $form['name']->getData();
            $email = $form['email']->getData();
            $subject = $form['subject']->getData();
            $data = $form['message']->getData();

            //Nettoyage des variables
            $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $cleanSubject = filter_var($subject, FILTER_SANITIZE_STRING);
            $cleanData = filter_var($data, FILTER_SANITIZE_STRING);


            //Création de l'objet du message
            $message = \Swift_Message::newInstance();
            $message->setSubject($cleanSubject);
            $message->setFrom(array(
                $cleanEmail => $cleanName
            ));
            $message->setTo('contact.csgo@gmail.com');
            $message->setBody($cleanData);

            //On passe notre mesage a mailer pour qu'il l'envoie
            $result = $this->get('mailer')->send($message);

            //Création d'une session
            $session = new Session();

            //Message d'erreur/succès
            if($result == 1){
                $session->getFlashBag()->add('notificationEmail','Email sent.');

            }else{
                $session->getFlashBag()->add('notificationEmail',"Error, mail not sent.");
                $result = 0;
            }

        }

        //Renvoyer sur la page contact avec le formulaire
        return $this->render('ShopBundle:Contact:index.html.twig', array('form' => $form->createView(),
            'result' => $result
        ));
    }
}