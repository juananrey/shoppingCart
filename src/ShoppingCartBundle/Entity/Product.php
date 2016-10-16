<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("product")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Entity\ProductRepository")
 */
class Product
{
    const TYPE_MAIN_DISH = 0;
    const TYPE_BEVERAGE = 1;
    const TYPE_DESSERT = 2;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="isUnitary", type="boolean")
     */
    private $isUnitary;

    /**
     * @ORM\Column(name="price", type="decimal", precision=4, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isUnitary()
    {
        return (bool) $this->isUnitary;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getType()
    {
        return $this->type;
    }
}
