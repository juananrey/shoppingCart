<?php

namespace ShoppingCartBundle\Entity;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCart implements ShoppingCartInterface
{
    const CART_NAMESPACE = 'cart';
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function has($name)
    {
        $name = self::CART_NAMESPACE.'/'.$name;
        return $this->session->has($name);
    }

    public function get($name)
    {
        $name = self::CART_NAMESPACE.'/'.$name;
        return $this->session->get($name);
    }

    public function set($name, $value)
    {
        $name = self::CART_NAMESPACE.'/'.$name;
        $this->session->set($name, $value);
    }

    public function remove($name)
    {
        $name = self::CART_NAMESPACE.'/'.$name;
        return $this->session->remove($name);
    }

    public function all()
    {
        $result = [];

        $allValues = $this->session->all();
        foreach ($allValues as $key => $value) {
            $newKey = str_replace(self::CART_NAMESPACE.'/', '', $key);
            $result[$newKey] = $value;
        }

        return $result;
    }
}