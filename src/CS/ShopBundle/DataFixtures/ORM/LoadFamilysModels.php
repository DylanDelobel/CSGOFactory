<?php

namespace CS\ShopBundle\DataFixtures\ORM;

use CS\ShopBundle\Entity\Model;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CS\ShopBundle\Entity\Family;

class LoadFamilysModels implements FixtureInterface
{
	// Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
	public function load(ObjectManager $manager)
	{
		// Liste des noms de famille à ajouter
		$items = array(
			'Pistols' => array(
				'CZ75-Auto',
				'Desert Eagle',
				'Dual Berettas',
				'Five-Seven',
				'Glock-18',
				'P2000',
				'P250',
				'R8 Revolver',
				'Tec-9',
				'USP-S'
			),
			'Rifles' => array(
				'Ak-47',
				'AUG',
				'AWP',
				'FAMAS',
				'G3SG1',
				'Galil AR',
				'M4A1-S',
				'M4A4',
				'SCAR-20',
				'SG 553',
				'SSG 08'
			),
			'SMGs' => array(
				'MAC-10',
				'MP7',
				'MP9',
				'PP-Bizon',
				'P90',
				'UMP-45'
			),
			'Heavy' => array(
				'MAG-7',
				'Nova',
				'Sawed-Off',
				'XM1014',
				'M249',
				'Negev'
			),
			'Knives' => array(
				'Bayonet',
				'Bowie',
				'Butterfly',
				'Falchion',
				'Flip',
				'Gut',
				'Huntsman',
				'Karambit',
				'Shadow Daggers',
				'M9 Bayonet'
			)
		);

	//On fait une boucle sur le tableau pour avoir la family et le models
	foreach ($items as $familys => $models)
	{

		// On créé la famille
		$family = new Family();

		//On Set le Nom avec $familys
		$family->setName($familys);

		// On la persiste
		$manager->persist($family);

		//On fait une boucle sur le tableau Models
		foreach ($models as $value){

			// On crée le Model
			$model = new Model();

			//Set le name avec la valeur du deuxieme tableau.
			$model->setName($value);
			//On set la clef étranger (Family cléf primaire)
			$model->setFamily($family);

			//On persist le model
			$manager->persist($model);

		}
	}
	// On déclenche l'enregistrement de toutes les catégories
	$manager->flush();

	}
}