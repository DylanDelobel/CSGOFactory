<?php

namespace CS\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model
 *
 * @ORM\Table(name="model")
 * @ORM\Entity(repositoryClass="CS\ShopBundle\Repository\ModelRepository")
 */
class Model
{
    /**
     * @ORM\ManyToOne(targetEntity="CS\ShopBundle\Entity\Family")
     * @ORM\JoinColumn(nullable=false)
     */
    private $family;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Model
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set family
     *
     * @param \CS\ShopBundle\Entity\Family $family
     *
     * @return Model
     */
    public function setFamily(\CS\ShopBundle\Entity\Family $family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return \CS\ShopBundle\Entity\Family
     */
    public function getFamily()
    {
        return $this->family;
    }
}
