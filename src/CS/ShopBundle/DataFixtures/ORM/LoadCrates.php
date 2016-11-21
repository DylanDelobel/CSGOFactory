<?php

namespace CS\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CS\ShopBundle\Entity\Crate;
use CS\ShopBundle\Entity\Image;

class LoadCrates implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		// List of collection to add
		$crates = array(
			"CS:GO Weapon Case",
			"CS:GO Weapon Case 2",
			"CS:GO Weapon Case 3",
			"Chroma Case",
			"Chroma Case 2",
			"Chroma Case 3",
			"eSports 2013 Case",
			"eSports 2013 Winter Case",
			"eSports 2014 Summer Case",
			"Falchion Case",
			"Gamma Case",
			"Gamma Case 2",
			"Huntsman Weapon Case",
			"Operation Bravo Case",
			"Operation Breakout Weapon Case",
			"Operation Phoenix Weapon Case",
			"Operation Vanguard Weapon Case",
			"Operation Wildfire Case",
			"Revolver Case",
			"Shadow Case",
			"Winter Offensive Weapon Case"
			);

	// Loop collections to add them
	foreach ($crates as $crateName)
	{
		$image = new Image();
		$image->setPath("http://placehold.it/256x198");
		// Create the Crate
		$crate = new Crate();

		// Set the name
		$crate->setName($crateName);
		$crate->setImage($image);
		// Persist the entity
		$manager->persist($crate);
	}
	// Record them in the database
	$manager->flush();
	}
}