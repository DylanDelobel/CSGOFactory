<?php

namespace CS\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CS\ShopBundle\Entity\Collection;
use CS\ShopBundle\Entity\Image;

class LoadCollections implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		// List of collection to add
		$collections = array(
			"Alpha Collection",
			"Assault Collection",
			"Aztec Collection",
			"Baggage Collectio",
			"Bank Collection",
			"Cache Collection",
			"Chop Shop Collection",
			"Cobblestone Collection",
			"Dust Collection",
			"Dust 2 Collection",
			"Gods and Monsters Collection",
			"Inferno Collection",
			"Italy Collection",
			"Lake Collection",
			"Militia Collection",
			"Mirage Collection",
			"Nuke Collection",
			"Office Collection",
			"Overpass Collection",
			"Rising Sun Collection",
			"Safehouse Collection",
			"Train Collection",
			"Vertigo Collection"
			);

	// Loop collections to add them
	foreach ($collections as $collectionName)
	{
		$image = new Image();
		$image->setPath("path/test");
		// Create the collection
		$collection = new Collection();

		// Set the name
		$collection->setName($collectionName);
		$collection->setImage($image);
		// Persist the entity
		$manager->persist($collection);
	}
	// Record them in the database
	$manager->flush();
	}
}