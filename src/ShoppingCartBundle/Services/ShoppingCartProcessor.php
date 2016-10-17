<?php

namespace ShoppingCartBundle\Services;

use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\ProductRepository;
use ShoppingCartBundle\Entity\ShoppingCart;

/*
 * Responsible for processing prices and products along the cart
 */
class ShoppingCartProcessor
{
    const MENU_DISCOUNT_PERCENTAGE = 20;

    private $shoppingCart;
    private $productRepository;

    public function __construct(ShoppingCart $shoppingCart, ProductRepository $productRepository)
    {
        $this->shoppingCart = $shoppingCart;
        $this->productRepository = $productRepository;
    }

    public function loadShoppingCart()
    {
        $products = $this->loadShoppingCartProducts();
        $discounts = $this->loadShoppingCartDiscounts($products);
        $totalPrice = $this->loadShoppingCartTotalPrice($products, $discounts);

        return array(
            'products' => $products,
            'discounts' => $discounts,
            'totalPrice' => $totalPrice,
        );
    }

    public function loadShoppingCartProducts()
    {
        $products = [];
        $cartProductIds = $this->shoppingCart->all() ? : [];

        foreach ($cartProductIds as $productId => $quantity) {
            $product = $this->productRepository->getProductById($productId);
            $products[$productId]['product'] = $this->productRepository->getProductById($productId);
            $products[$productId]['quantity'] = $quantity;
            $products[$productId]['totalPrice'] = $product->getPrice() * $quantity;
        }

        return $products;
    }

    public function loadShoppingCartDiscounts($products)
    {
        $discounts = array_merge(
            $this->applyOffer_get3Pay2($products),
            $this->applyOffer_getMenuDiscount($products)
        );

        return $discounts;
    }

    public function loadShoppingCartTotalPrice($products, $discounts)
    {
        $productsTotalPrice = array_reduce($products,
            function($result, $x) {
                return $result + $x['totalPrice'];
            },
            0
        );

        $discountsTotalPrice = array_reduce($discounts,
            function($result, $x) {
                return $result + $x['totalDiscount'];
            },
            0
        );

        return $productsTotalPrice - $discountsTotalPrice;
    }

    private function applyOffer_get3Pay2($products)
    {
        $discounts = [];
        foreach($products as $productId => $value) {
            $product = $value['product'];
            $isUnitary = $product->isUnitary();
            $discountApplies = $isUnitary && $value['quantity'] >= 3;

            if ($discountApplies) {
                $timesToApply = floor($value['quantity'] / 3);
                $discounts[$productId]['product'] = $product;
                $discounts[$productId]['timesToApply'] = $timesToApply;
                $discounts[$productId]['totalDiscount'] = $product->getPrice() * $timesToApply;
                $discounts[$productId]['type'] = 'get3pay2';
            }
        }

        return $discounts;
    }

    private function applyOffer_getMenuDiscount($products)
    {
        $productsByType = $this->getProductsGroupedByType($products);
        $menuDiscounts = $this->getMenuDiscounts($productsByType);

        return $menuDiscounts;
    }

    private function getProductsGroupedByType($products)
    {
        $result = array();
        foreach($products as $productId => $value) {
            $product = $value['product'];
            $productType = $product->getType();

            $result[$productType][$productId]['product'] = $product;
            $result[$productType][$productId]['quantity'] = $value['quantity'];
        }

        ksort($result, SORT_NUMERIC);
        return $result;
    }

    private function getMenuDiscounts($productsByType)
    {
        $discounts = [];
        $discountIndex = 0;

        // If we have main dish, beverage and drinks in our cart, then we can apply our discounts
        while ($this->isMenuDiscountApplicable($productsByType)) {

            $mainDish = array_pop($productsByType[Product::TYPE_MAIN_DISH]);
            $beverage = array_pop($productsByType[Product::TYPE_BEVERAGE]);
            $dessert = array_pop($productsByType[Product::TYPE_DESSERT]);

            $discounts[$discountIndex]['product1'] = $mainDish['product'];
            $discounts[$discountIndex]['product2'] = $beverage['product'];
            $discounts[$discountIndex]['product3'] = $dessert['product'];
            $discounts[$discountIndex]['totalDiscount'] = $this->calculateMenuDiscount($discounts[$discountIndex]);
            $discounts[$discountIndex]['type'] = 'menuDiscount';

            $mainDish['quantity'] = $mainDish['quantity'] - 1;
            $beverage['quantity'] = $beverage['quantity'] - 1;
            $dessert['quantity'] = $dessert['quantity'] - 1;

            if ($mainDish['quantity'] > 0) {
                array_push($productsByType[Product::TYPE_MAIN_DISH], $mainDish);
            }

            if ($beverage['quantity'] > 0) {
                array_push($productsByType[Product::TYPE_BEVERAGE], $beverage);
            }

            if ($dessert['quantity'] > 0) {
                array_push($productsByType[Product::TYPE_DESSERT], $dessert);
            }

            $discountIndex++;
        }

        return $discounts;
    }

    private function isMenuDiscountApplicable($productsByType)
    {
        $productsRequiredForMenu = [Product::TYPE_MAIN_DISH, Product::TYPE_BEVERAGE, Product::TYPE_DESSERT];
        foreach ($productsRequiredForMenu as $productType) {
            if (false === array_key_exists($productType, $productsByType) || empty($productsByType[$productType])) {
                return false;
            }
        }

        return true;
    }

    private function calculateMenuDiscount(array $products)
    {
        $result = 0;
        foreach ($products as $product) {
            $result += $product->getPrice();
        }

        $result = $result * (self::MENU_DISCOUNT_PERCENTAGE / 100);
        return $result;
    }
}