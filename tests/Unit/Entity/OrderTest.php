<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Brand;
use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
use Iterator;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{

    /**
     * @param array $items
     * @dataProvider itemsDataProvider
     */
    public function testGetBrandsProductsQuantities(array $items) : void
    {
        $order = new Order($items);
        $brandProductQuantities = $order->getBrandsProductsQuantities();

        $this->assertCount(2, $brandProductQuantities);
        $this->assertArrayHasKey(Brand::BRANDS_NAMES['Farmitoo'], $brandProductQuantities);
        $this->assertArrayHasKey(Brand::BRANDS_NAMES['Gallagher'], $brandProductQuantities);
    }

    /**
     * @param array $items
     * @dataProvider itemsDataProvider
     */
    public function testGetProductsFreight(array $items) : void
    {
        $order = new Order($items);
        $freight = $order->getProductsFreight($order->getBrandsProductsQuantities());

        $this->assertEquals(35, $freight);
    }

    /**
     * @param array $items
     * @dataProvider itemsDataProvider
     */
    public function testSetTaxFreeRawAmount(array $items) : void
    {
        $order = new Order($items);
        $order->setTaxFreeRawAmount();
        $taxFreeRawAmount = $order->getTaxFreeRawAmount();

        self::assertEquals(85000, $taxFreeRawAmount);
    }

    /**
     * @param array $items
     * @dataProvider itemsDataProvider
     */
    public function testSetTaxFreeAmount(array $items) : void
    {
        $order = new Order($items);
        $order->setTaxFreeAmount();

        $freight = $order->getFreight();
        $taxFreeRawAmount = $order->getTaxFreeRawAmount();
        $taxFreeAmount = $order->getTaxFreeAmount();

        $this->assertEquals(35, $freight);
        self::assertEquals(85000, $taxFreeRawAmount);
        self::assertEquals(($freight+$taxFreeRawAmount), $taxFreeAmount);
    }

    /**
     * @param array $items
     * @dataProvider itemsDataProvider
     */
    public function testGetAmountBeforePromotion(array $items) : void
    {
        $order = new Order($items);
        $amountBeforePromotion = $order->getAmountBeforePromotion();

        $freight = $order->getFreight();
        $taxFreeRawAmount = $order->getTaxFreeRawAmount();
        $taxFreeAmount = $order->getTaxFreeAmount();

        $this->assertEquals(35, $freight);
        self::assertEquals(85000, $taxFreeRawAmount);
        self::assertEquals(($freight+$taxFreeRawAmount), $taxFreeAmount);
        self::assertEquals(($taxFreeAmount + $order->getTaxes()), $amountBeforePromotion);
        self::assertEquals(99035, $amountBeforePromotion);
    }

    /**
     * @param array $items
     * @dataProvider itemsDataProvider
     */
    public function testSetAmount(array $items) : void
    {
        $reduction = 1000;
        $promotion = new Promotion(0, $reduction, false);
        $order = new Order($items, $promotion);
        $order->setAmount();

        $freight = $order->getFreight();
        $taxFreeRawAmount = $order->getTaxFreeRawAmount();
        $taxFreeAmount = $order->getTaxFreeAmount();
        $amountBeforePromotion = $order->getAmountBeforePromotion();
        $amount = $order->getAmount();

        $this->assertEquals(35, $freight);
        self::assertEquals(85000, $taxFreeRawAmount);
        self::assertEquals(($freight+$taxFreeRawAmount), $taxFreeAmount);
        self::assertEquals(($taxFreeAmount + $order->getTaxes()), $amountBeforePromotion);
        self::assertEquals(99035, $amountBeforePromotion);
        self::assertEquals($amountBeforePromotion - $reduction, $amount);
    }

    /**
     * @return Item[]
     */
    public function itemsDataProvider() : array
    {
        $brand1 = new Brand(Brand::BRANDS_NAMES['Farmitoo']);
        $brand2 = new Brand(Brand::BRANDS_NAMES['Gallagher']);

        $product1 = new Product('product1', 5000, $brand1);
        $product2 = new Product('product2', 10000, $brand2);
        $product3 = new Product('product3', 20000, $brand1);

        return [[[
            (new Item($product1, 1)),
            (new Item($product2, 2)),
            (new Item($product3, 3))
        ]]];
    }


}