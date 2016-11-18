<?php

namespace CS\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weapon
 *
 * @ORM\Table(name="weapon")
 * @ORM\Entity(repositoryClass="CS\ShopBundle\Repository\WeaponRepository")
 */
class Weapon
{
    /**
     * @ORM\ManyToOne(targetEntity="CS\ShopBundle\Entity\Image")
     * @ORM\JoinColumn(nullable=false)
     */
    private $image;
    /**
     * @ORM\ManyToOne(targetEntity="CS\ShopBundle\Entity\Crate")
     * @ORM\JoinColumn(nullable=true)
     */
    private $crate;
    /**
     * @ORM\ManyToOne(targetEntity="CS\ShopBundle\Entity\Collection")
     * @ORM\JoinColumn(nullable=true)
     */
    private $collection;
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
     * @var string
     *
     * @ORM\Column(name="quality", type="string", length=50)
     */
    private $quality;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;


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
     * @return Weapon
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
     * Set quality
     *
     * @param string $quality
     *
     * @return Weapon
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * Get quality
     *
     * @return string
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Weapon
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Weapon
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image
     *
     * @param \CS\ShopBundle\Entity\Image $image
     *
     * @return Weapon
     */
    public function setImage(\CS\ShopBundle\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \CS\ShopBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
}