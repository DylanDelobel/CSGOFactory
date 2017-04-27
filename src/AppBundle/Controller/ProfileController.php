<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/* FormType */
use AppBundle\Form\Type\UserType;


class ProfileController extends Controller
{
    /**
     * @Route("/profile/", name="profile")
     */
    public function showProfilUserAction()
    {
        // 1) Savoir si l'utilisateur est login
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            // 2) Si utilisateur login, en récupère son id
            $user =  $this->getUser();

        }else{
            // 3) Sinon on redirige l'utilisateur vers la page login
            return $this->redirectToRoute('fos_user_security_login');
        }

        return $this->render('AppBundle:Profile:profile.html.twig');
    }

    /**
     * @Route("/profile/update", name="profile_update")
     */
    public function updateProfilAction(Request $request)
    {
        // 1) Savoir si l'utilisateur est login
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            // 2) Si utilisateur login, en récupère son id
            $user =  $this->getUser();

        }else{
            // 3) Sinon on redirige l'utilisateur vers la page login
            return $this->redirectToRoute('fos_user_security_login');
        }

        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);
        $saltUser = $user->getSalt();
        $actualPassword = $user->getPassword();

        $form = $this->createForm(UserType::class);
        $form->setData($user);


        $error = 0;
        $result = 0;

        if ($request->getMethod() == 'POST') {
            $session = new Session();

            $form->handleRequest($request);
            $data = $form->getData();

            if($form->isValid()){


                $user->setUsernameCanonical(strtolower($data->getUsername()));
                $user->setEmailCanonical(strtolower($data->getEmail()));

                $newMdp = $form['password']->getData();
                $repeatpassword = $form['repeatPassword']->getData();

                if(!empty($newMdp)){
                    if(!empty($repeatpassword)) {

                        // les new mdp doivent être identiques
                        if($newMdp == $repeatpassword){

                            $newMdp = $encoder->encodePassword($newMdp, $saltUser);
                            $user->setPassword($newMdp);

                            $this->getDoctrine()->getManager()->flush();
                            $session->getFlashBag()->add('updateUser','Modifications save.');
                            return $this->redirectToRoute('profile_update');

                        }else{
                            $session->getFlashBag()->add('errorMdp','Both passwords are not the same.');
                            $result=1;
                        }

                    }else{
                        // prévient que les deux inputs doivent être plein
                        $session->getFlashBag()->add('errorRepeatMdp','For change password, please fill in both fields.');
                        $result=1;
                    }
                }


                if($result == 0){
                    $user->setPassword($actualPassword);
                    $this->getDoctrine()->getManager()->flush();
                    $session->getFlashBag()->add('updateUser','Modifications save.');
                    return $this->redirectToRoute('profile_update');
                }else{
                    $session->getFlashBag()->add('updateUserFalse','Modifications no\'t save.');
                    return $this->redirectToRoute('profile_update');
                }

            }

        }


        return $this->render('AppBundle:Profile:form_update_profile.html.twig', array(
            'form' => $form->createView(),
            'result' => $result,
        ));
    }

}
