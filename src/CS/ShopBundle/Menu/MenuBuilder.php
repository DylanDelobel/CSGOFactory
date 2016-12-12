<?php

namespace CS\ShopBundle\Menu;

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
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $models = $this->modelRepository->findAll();

        foreach ($models as $model) {
            $menu->addChild($model->getFamily()->getName(),
                array('uri' => 'shop/'.$model->getFamily()->getName().'/'));
        }

        foreach ($models as $model) {
            $menu[$model->getFamily()->getName()]->addChild($model->getName(),
                array('uri' => 'shop/'.$model->getFamily()->getName().'/'.$model->getName()));
        }

        return $menu;
    }
}