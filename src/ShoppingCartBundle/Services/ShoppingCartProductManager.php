<?php

namespace ShoppingCartBundle\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/*
 * Responsible for adding and remove items into our cart
 */
class ShoppingCartProductManager
{
    private $shoppingCart;

    public function __construct(SessionInterface $shoppingCart)
    {
        $this->shoppingCart = $shoppingCart;
    }

    public function addProduct($productId)
    {
        if (false === $this->shoppingCart->has($productId)) {
            $this->shoppingCart->set($productId, 0);
        }

        $this->increaseQuantity($productId);
    }

    public function removeProduct($productId)
    {
        if (false === $this->shoppingCart->has($productId)) {
            $this->shoppingCart->set($productId, 0);
        }

        $this->decreaseQuantity($productId);

        if ($this->shoppingCart->get($productId) <= 0) {
            $this->shoppingCart->remove($productId);
        }
    }

    private function increaseQuantity($productId)
    {
        $quantity = $this->shoppingCart->get($productId);
        $quantity++;

        $this->shoppingCart->set($productId, $quantity);
    }

    private function decreaseQuantity($productId)
    {
        $quantity = $this->shoppingCart->get($productId);
        $quantity--;

        $this->shoppingCart->set($productId, $quantity);
    }
}