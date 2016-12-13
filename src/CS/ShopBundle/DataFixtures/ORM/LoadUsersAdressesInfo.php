<?php

namespace CS\ShopBundle\DataFixtures\ORM;


use CS\ShopBundle\Entity\UsersAdressesInfo;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CS\ShopBundle\Entity\User;



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
                'Martin',
                'Didier',
                'Marie',
                'Emma',
                'Fabien'
            ),
            'lastName' => array(
                'Massip',
                'Gublin',
                'Delobel',
                'Alvarez',
                'Golotion',
                'Hakoon',
                'Conton',
                'Optolo'
            ),
            'streetAddress' => array(
                '41 rue Olphatono',
                '2 place la marche',
                '56 rue du jean-jean',
                '60 place alfred fosta',
                '7 rue du ivra',
                '25 chemin du lotuse',
                '42 rue martin'
            ),
            'city' => array(
                'Perpignan',
                'Avignon',
                'Toulouse',
                'New-York',
                'Londre',
                'Paris',
                'Kourou'
            ),
            'cp' => array(
                '66000',
                '72568',
                '85624',
                '32505',
                '30256',
                '02356'
            ),
            'country' => array(
                'FR',
                'US',
                'EN'
            ),
            'phone' => array(
                '0609835715',
                '0409565716',
                '0493556717',
                '0656835718',
                '0609835719',
                '0605235720'
            ),
            'emailPro' => array(
                'Jerome@gmail.com',
                'Paul@gmail.com',
                'Emma@gmail.com',
                'Lisa@gmail.com',
                'Alexandre@gmail.com',
                'Fabien@gmail.com',
                'Dylan@gmail.com'
            )
        );

        $user = $manager->getRepository("ShopBundle:User");
        $listUser = $user->findAll();


        for ($i=0; $i < 20; $i++) {
            $userInfo = new UsersAdressesInfo();

            $userInfo->setUser($listUser[mt_rand(0,1)]);
            $userInfo->setFirstName($info['firstName'][mt_rand(0,9)]);
            $userInfo->setLastName($info['lastName'][mt_rand(0,7)]);
            $userInfo->setStreetAddress($info['streetAddress'][mt_rand(0,6)]);
            $userInfo->setCity($info['city'][mt_rand(0,6)]);
            $userInfo->setCp($info['cp'][mt_rand(0,5)]);
            $userInfo->setCountry($info['country'][mt_rand(0,2)]);
            $userInfo->setPhone($info['phone'][mt_rand(0,5)]);
            $userInfo->setEmailPro($info['emailPro'][mt_rand(0,6)]);


            $manager->persist($userInfo);
        }




        // Record them in the database
        $manager->flush();
    }
}