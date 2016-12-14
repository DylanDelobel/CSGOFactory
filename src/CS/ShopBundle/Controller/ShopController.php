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

//            $transport = \Swift_SmtpTransport::newInstance('mail.smtp2go.com', 465, 'ssl')
//                ->setUsername('contact.csgo@gmail.com')
//                ->setPassword('ENUwbtS2COP9')
//            ;
//
//
//            $mailer = \Swift_Mailer::newInstance($transport);
//
//            $message = \Swift_Message::newInstance()
//                ->setSubject($cleanSubject)
//                ->setFrom($cleanEmail)
//                //->setTo(array('contact.csgo@gmail.com' => 'toto'))
//                ->setTo('contact.csgo@gmail.com')
//                ->setCharset('utf-8')
//                ->setContentType('text/html')
//                ->setBody($cleanData);
//
//            //$result = $mailer->send($message);
//            $this->get('mailer')->send($message);

            //Transport for email
            //$transport = \Swift_SendmailTransport::newInstance('C:\UwAmp\sendmail -bs');  // First method
            $transport = \Swift_SmtpTransport::newInstance('mail.smtp2go.com', 587 )
                ->setUsername('contact.csgo@gmail.com')
                ->setPassword('ENUwbtS2COP9');
            //$transport = \Swift_MailTransport::newInstance();


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



            $mailer = \Swift_Mailer::newInstance($transport);
            $result = $mailer->send($message);
            //$this->get('mailer')->send($message);




            if($result == 1){
                $session = new Session();
                $session->getFlashBag()->add('notificationEmail','Email sent.');

            }else{
                $session = new Session();
                $session->getFlashBag()->add('notificationEmail',"Error, mail not sent.");
                $result = 0;
            }

        }

        return $this->render('ShopBundle:Contact:index.html.twig', array('form' => $form->createView(),
            'result' => $result
        ));
    }
}

