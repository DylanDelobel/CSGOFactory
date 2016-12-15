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
    public function contactAction(Request $request)
    {
        $cleanName = $cleanEmail = $cleanSubject = $cleanData = $result = null;

        //$formContact =

        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('subject', TextType::class)
            ->add('message', TextareaType::class)
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //if($request->isMethod('POST')){
            $name = $form['name']->getData();
            $email = $form['email']->getData();
            $subject = $form['subject']->getData();
            $data = $form['message']->getData();

            //Clear var
            $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $cleanSubject = filter_var($subject, FILTER_SANITIZE_STRING);
            $cleanData = filter_var($data, FILTER_SANITIZE_STRING);


            //Message object
            $message = \Swift_Message::newInstance();
            $message->setSubject($cleanSubject);
            $message->setFrom(array(
                $cleanEmail => $cleanName
            ));
            //$message->setFrom('alexandre.gublin66@gmail.com');
            //$message->setTo(array('contact.csgo@gmail.com' => 'totoroot'));
            $message->setTo('contact.csgo@gmail.com');
            $message->setBody($cleanData);
            
            $result = $this->get('mailer')->send($message);


            $session = new Session();

            if($result == 1){
                $session->getFlashBag()->add('notificationEmail','Email sent.');

            }else{
                $session->getFlashBag()->add('notificationEmail',"Error, mail not sent.");
                $result = 0;
            }

        }

        return $this->render('ShopBundle:Contact:index.html.twig', array('form' => $form->createView(),
            'result' => $result
        ));
    }
}