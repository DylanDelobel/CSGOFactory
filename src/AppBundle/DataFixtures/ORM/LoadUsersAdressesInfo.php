<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\UsersAdressesInfo;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUsersAdressesInfo implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $info = array(
            'firstName' => array(
                'Jérôme',
                'Dylan',
                'Lisa',
                'Alexandre',
                'Paul',
                'Marie',
                'Emma',
            ),
            'lastName' => array(
                'Massip',
                'Gublin',
                'Delobel',
                'Alvarez',
                'Golotion',
                'Optolo'
            ),
            'streetAddress' => array(
                '41 rue Olphatono',
                '2 place la marche',
                '56 rue du jean-jean',
                '60 place alfred fosta',
                '25 chemin du lotuse',
                '42 rue martin'
            ),
            'city' => array(
                'Perpignan',
                'Avignon',
                'New-York',
                'Londre',
                'Paris',
            ),
            'cp' => array(
                '66000',
                '72568',
                '85624',
                '30256',
                '02356'
            ),
            'country' => array(
                'FR',
                'US',
                'EN'
            ),
            'phone' => array(
                '0600000001',
                '0600000002',
                '0600000003',
                '0600000004',
                '0600000005',
            ),
            'emailPro' => array(
                'Jerome@gmail.com',
                'Paul@gmail.com',
                'Emma@gmail.com',
                'Lisa@gmail.com',
                'Alexandre@gmail.com',
                'Dylan@gmail.com'
            )
        );

        $user = $manager->getRepository("ShopBundle:User");
        $listUser = $user->findAll();

        for ($i=0; $i < 20; $i++) {
            $userInfo = new UsersAdressesInfo();

            $userInfo->setUser($listUser[mt_rand(0,1)]);
            $userInfo->setFirstName($info['firstName'][mt_rand(0,6)]);
            $userInfo->setLastName($info['lastName'][mt_rand(0,5)]);
            $userInfo->setStreetAddress($info['streetAddress'][mt_rand(0,5)]);
            $userInfo->setCity($info['city'][mt_rand(0,4)]);
            $userInfo->setCp($info['cp'][mt_rand(0,4)]);
            $userInfo->setCountry($info['country'][mt_rand(0,2)]);
            $userInfo->setPhone($info['phone'][mt_rand(0,4)]);
            $userInfo->setEmailPro($info['emailPro'][mt_rand(0,5)]);

            $manager->persist($userInfo);
        }
        // Record them in the database
        $manager->flush();
    }
}