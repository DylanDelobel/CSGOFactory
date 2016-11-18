<?php

namespace CS\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Crate
 *
 * @ORM\Table(name="crate")
 * @ORM\Entity(repositoryClass="CS\ShopBundle\Repository\CrateRepository")
 */
class Crate
{
    /**
     * @ORM\OneToOne(targetEntity="CS\ShopBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;
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
     * @ORM\Column(name="name", type="string", length=255)
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
     * @return Crate
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
}

