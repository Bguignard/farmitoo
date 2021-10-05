<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Brand;
use PHPUnit\Framework\TestCase;

class BrandTest extends TestCase
{
    public function testSetName() : void
    {
        $testName = 'testName';
        $brand = new Brand($testName);
        self::assertEquals($testName, $brand->getName());
    }

    public function testTVARate() : void
    {
        $testName1 = 'testName1';
        $testName2 = Brand::BRANDS_NAMES['Farmitoo'];
        $brand1 = new Brand($testName1);
        $brand2 = new Brand($testName2);
        self::assertEquals(0, $brand1->getTVARate());
        self::assertEquals(0.2, $brand2->getTVARate());
    }

    public function testFreightCalculation()
    {
        $freight1 = Brand::getFreightCalculation(Brand::BRANDS_NAMES['Farmitoo'], 5);
        $freight2 = Brand::getFreightCalculation(Brand::BRANDS_NAMES['Gallagher'], 5);
        self::assertEquals(15, $freight1);
        self::assertEquals(40, $freight2);
    }


}