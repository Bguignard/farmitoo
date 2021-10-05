<?php


namespace App\Tests\Unit\Entity;


use App\Entity\Brand;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testGetTitle()
    {
        $product = new Product('Cuve à gasoil', 100, new Brand('Farmitoo'));

        $this->assertSame('Cuve à gasoil', $product->getTitle());
    }

    public function testGetPrice()
    {
        $product = new Product('Cuve à gasoil', 100, new Brand('Farmitoo'));

        $this->assertSame(100, $product->getPrice());
    }
}
