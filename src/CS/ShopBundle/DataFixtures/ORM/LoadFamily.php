<?php

namespace CS\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CS\ShopBundle\Entity\Family;

class LoadFamily implements FixtureInterface
{
	// Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
	public function load(ObjectManager $manager)
	{
		// Liste des noms de famille à ajouter
		$names = array(
			'Pistols',
			'Rifles',
			'SMGs',
			'Heavy',
			'Knives'
		);

	foreach ($names as $name) 
	{
		// On créé la famille
		$family = new Family();
		$family->setName($name);

		// On la persiste
		$manager->persist($family);
	}

	// On déclenche l'enregistrement de toutes les catégories
	$manager->flush();

	}
}