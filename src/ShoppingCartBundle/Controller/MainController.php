<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ShoppingCartBundle\Entity\ProductRepository;
use ShoppingCartBundle\Services\ShoppingCartProcessor;
use ShoppingCartBundle\Services\ShoppingCartProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="controller.main")
 */
class MainController extends Controller
{
    private $productRepository;
    private $cartProcessor;
    private $cartProductManager;

    public function __construct(ProductRepository $productRepository, ShoppingCartProcessor $cartProcessor, ShoppingCartProductManager $cartProductManager)
    {
        $this->productRepository = $productRepository;
        $this->cartProcessor = $cartProcessor;
        $this->cartProductManager = $cartProductManager;
    }

    /**
     * @Route("/", name="landing")
     * @Template("index.html.twig")
     */
    public function lookupAction(Request $request)
    {
        $filter = $request->query->get('filter');
        $products = $this->productRepository->getAllProducts($filter);
        $cart = $this->cartProcessor->loadShoppingCart();

        return array(
            'products' => $products,
            'cart' => $cart['products'],
            'discounts' => $cart['discounts'],
            'totalPrice' => $cart['totalPrice']
        );
    }

    /**
     * @Route("/products/add/{productId}", name="add_product")
     * @Template("cart.html.twig")
     */
    public function addItemAction(Request $request, $productId)
    {
        $this->cartProductManager->addProduct($productId);
        $cart = $this->cartProcessor->loadShoppingCart();

        return array(
            'cart' => $cart['products'],
            'discounts' => $cart['discounts'],
            'totalPrice' => $cart['totalPrice']
        );
    }

    /**
     * @Route("/products/remove/{productId}", name="remove_product")
     * @Template("cart.html.twig")
     */
    public function removeItemAction(Request $request, $productId)
    {
        $this->cartProductManager->removeProduct($productId);
        $cart = $this->cartProcessor->loadShoppingCart();

        return array(
            'cart' => $cart['products'],
            'discounts' => $cart['discounts'],
            'totalPrice' => $cart['totalPrice']
        );
    }
}
