<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function getAllProducts($filter='')
    {
        $query = $this->_em->createQuery('SELECT p FROM ShoppingCartBundle:Product p WHERE p.name LIKE :filter');
        $query->setParameter('filter', "%{$filter}%");

        return $query->getArrayResult();
    }

    public function getProductById($productId)
    {
        $query = $this->_em->createQuery('SELECT p FROM ShoppingCartBundle:Product p WHERE p.id = :productId');
        $query->setParameter('productId', $productId);

        return $query->getSingleResult();
    }
}