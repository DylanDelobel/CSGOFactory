<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Doctrine\ORM\EntityRepository;


class MenuBuilder
{
    private $factory;
    private $modelRepository;

    public function __construct(FactoryInterface $factory, EntityRepository $modelRepository)
    {
        $this->factory = $factory;
        $this->modelRepository = $modelRepository;
    }

    public function createMainMenu()
    {
        // Build the root
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $models = $this->modelRepository->findAll();

        // Addding family to the first branch
        foreach ($models as $model) {
            $menu->addChild($model->getFamily()->getName(),
                array('uri' => 'shop/'.$model->getFamily()->getName().'/'));
        }

        // Addding model to the second branch
        foreach ($models as $model) {
            $menu[$model->getFamily()->getName()]->addChild($model->getName(),
                array('route' => 'catalog', 'routeParameters' => array(
                    'family' => $model->getFamily()->getName(),
                    'model' => $model->getName()
                )));
        }

        return $menu;
    }
}
