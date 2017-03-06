<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUsers implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Get our userManager
        $userManager = $this->container->get('fos_user.user_manager');

        // Create our user (user/user)
        $user = $userManager->createUser();
        $user->setUsername('user');
        $user->setEmail('user@domain.com');
        $user->setPlainPassword('user');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER'));

        // Update the user
        $userManager->updateUser($user, true);

        // Create our user (admin/admin)
        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@domain.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));

        // Update the user
        $userManager->updateUser($user, true);
    }
}