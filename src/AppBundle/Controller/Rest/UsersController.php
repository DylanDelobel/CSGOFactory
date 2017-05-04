<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('AppBundle:User');
        $users = $userRepo->findAll();

        if (empty($users)){
            return array("code" => "404", "message" => "Not Found");
        }

        return $users;
    }

    public function getUserAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if (empty($user)){
            return array("code" => "404", "message" => "Not Found");
        }
        return $user;
    }

    /* supprime l'user */
    public function deleteUserAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);


        if (empty($user)){
            return array("code" => "404", "message" => "Not Found");
        }

        $userManager = $this->get('fos_user.user_manager');

        $userManager->deleteUser($user);

        return array("code" => "200", "message" => "Ok");

    }

    /* modifie l'user */
    public function putUserAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if (empty($user)){
            return array("code" => "404", "message" => "Form is Invalid");
        }

        $user->setUsername($request->get('username'));
        $user->setUsernameCanonical(strtolower($request->get('username')));
        $user->setEmail($request->get('email'));
        $user->setEmailCanonical(strtolower($request->get('email')));
        $user->setPassword($request->get('password'));

        $em->merge($user);
        $em->flush();

        return array("code" => "200", "message" => "Ok");

    }

}

