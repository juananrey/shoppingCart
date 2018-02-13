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
        $query = $this->_em->createQuery('SELECT p FROM ShoppingCartBundle:Product p WHERE p.id LIKE :productId');
        $query->setParameter('productId', $productId);

        // Change this flag to notice the huuuuge performance change...
        $query->useResultCache(true, 3600, 'query_performance_crazy_difference');
        return $query->getSingleResult();
    }
}