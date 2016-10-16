<?php

namespace ShoppingCartBundle\Services;

use ShoppingCartBundle\Entity\ProductRepository;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

class ShoppingCartProcessor
{
    private $shoppingCart;
    private $productRepository;

    public function __construct(AttributeBagInterface $shoppingCart, ProductRepository $productRepository)
    {
        $this->shoppingCart = $shoppingCart;
        $this->productRepository = $productRepository;
    }

    public function loadProductsInformation()
    {
        $products = [];
        $cartProductIds = $this->shoppingCart->all() ? : [];

        foreach ($cartProductIds as $productId => $quantity) {
            $product = $this->productRepository->getProductById($productId);
            $products[$productId]['object'] = $this->productRepository->getProductById($productId);
            $products[$productId]['quantity'] = $quantity;
            $products[$productId]['totalPrice'] = $product->getPrice() * $quantity;
        }



        return $products;
    }

    // TODO
    public function loadProductDiscounts()
    {
        $products = $this->loadProductsInformation();
    }

    // TODO: ALL_PRODUCTS_PRIZE - ALL_DISCOUNTS
    public function loadTotalPrice()
    {
        // array_map in both to get the prices
        // array_sum(ALL_PRODUCTS_PRIZE) - array_sum(ALL_DISCOUNTS)

        return 0;
    }
}