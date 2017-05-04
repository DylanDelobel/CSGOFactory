<?php

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Weapon;
use AppBundle\Entity\Image;

class LoadWeapons implements FixtureInterface
{
	/**
     * @param ObjectManager $manager
     */
	public function load(ObjectManager $manager)
	{
		// Get all possible models
		$modelRepository = $manager->getRepository("AppBundle:Model");
		$listModels = $modelRepository->findAll();
		$numberModels = count($listModels)-1;

		$qualitys = array(
			"Factory New",
			"Minimal Wear",
			"Field-Tested",
			"Well-Worn",
			"Battle-Scarred"
			);

		// Random 500 Weapons
		for ($i=0; $i < 500; $i++) { 
			$weapon = new Weapon();
			$model = $listModels[mt_rand(0,$numberModels)];
			$weapon->setModel($model);

			$image = new Image();
			$image->setPath("http://placehold.it/360x300");
			$weapon->setImage($image);
			$manager->persist($image);c

			if ($i % 2 == 0) {
				// Get all possible crates
				$crateRepository = $manager->getRepository("AppBundle:Crate");
				$listCrates = $crateRepository->findAll();
				$numberCrates = count($listCrates)-1;
				$weapon->setCrate($listCrates[mt_rand(0,$numberCrates)]);
			}
            // Get all possible collections
            $collectionRepository = $manager->getRepository("AppBundle:Collection");
            $listCollections = $collectionRepository->findAll();
            $numberCollections = count($listCollections)-1;
            $weapon->setCollection($listCollections[rand(0,$numberCollections)]);

			
			$weapon->setName($model->getName() . " " . $i);
			$weapon->setQuality($qualitys[rand(0,4)]);
			$weapon->setQuantity(rand(0,999));
			$weapon->setPrice(rand(1,999));

			$manager->persist($weapon);
		}
	// Record them in the database
	$manager->flush();
	}
}