<?php

namespace CS\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderC
 *
 * @ORM\Table(name="order_c")
 * @ORM\Entity(repositoryClass="CS\ShopBundle\Repository\OrderCRepository")
 */
class OrderC
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CS\ShopBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="validate", type="boolean")
     */
    private $validate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="ref", type="integer")
     */
    private $ref;

    /**
     * @var array
     *
     * @ORM\Column(name="listOrder", type="array")
     */
    private $listOrder;


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
     * Set validate
     *
     * @param boolean $validate
     *
     * @return OrderC
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get validate
     *
     * @return bool
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return OrderC
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set ref
     *
     * @param integer $ref
     *
     * @return OrderC
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return int
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set listOrder
     *
     * @param array $listOrder
     *
     * @return OrderC
     */
    public function setListOrder($listOrder)
    {
        $this->listOrder = $listOrder;

        return $this;
    }

    /**
     * Get listOrder
     *
     * @return array
     */
    public function getListOrder()
    {
        return $this->listOrder;
    }

    /**
     * Set user
     *
     * @param \CS\ShopBundle\Entity\User $user
     *
     * @return OrderC
     */
    public function setUser(\CS\ShopBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CS\ShopBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
