<?php

namespace CS\ShopBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CS\ShopBundle\Entity\Weapon;
use CS\ShopBundle\Entity\Image;

class LoadWeapons implements FixtureInterface
{
	/**
     * @param ObjectManager $manager
     */
	public function load(ObjectManager $manager)
	{
		// Get all possible models
		$modelRepository = $manager->getRepository("ShopBundle:Model");
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
			$weapon->setModel($listModels[mt_rand(0,$numberModels)]);

			$image = new Image();
			$image->setPath("http://placehold.it/360x300");
			$weapon->setImage($image);
			$manager->persist($image);

			if ($i % 2 == 0) {
				// Get all possible crates
				$crateRepository = $manager->getRepository("ShopBundle:Crate");
				$listCrates = $crateRepository->findAll();
				$numberCrates = count($listCrates)-1;
				$weapon->setCrate($listCrates[mt_rand(0,$numberCrates)]);
			} else {
				// Get all possible collections
				$collectionRepository = $manager->getRepository("ShopBundle:Collection");
				$listCollections = $collectionRepository->findAll();
				$numberCollections = count($listCollections)-1;
				$weapon->setCollection($listCollections[rand(0,$numberCollections)]);
			}
			
			$weapon->setName("Weapon " . $i);
			$weapon->setQuality($qualitys[rand(0,4)]);
			$weapon->setQuantity(rand(0,999));
			$weapon->setPrice(rand(1,999));

			$manager->persist($weapon);
		}
	// Record them in the database
	$manager->flush();
	}
}