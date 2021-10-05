<?php


namespace App\Service;

use App\Entity\Order;
use App\Entity\Promotion;

class OrderManager
{

    /**
     * @param array $productsItems
     * @param Promotion|null $promotion
     * @return Order
     */
    public function createOrder(array $productsItems, ?Promotion $promotion = null) : Order
    {
        return (new Order($productsItems, $promotion))
            ->setFreight()
            ->setTaxFreeRawAmount()
            ->setTaxFreeAmount()
            ->setAmount();
    }
}