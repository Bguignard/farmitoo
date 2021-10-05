<?php

namespace App\Entity;

class Product
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var Brand
     */
    protected $brand;

    /**
     * @param string $title
     * @param int $price
     * @param Brand $brand
     */
    public function __construct(string $title, int $price, Brand $brand)
    {
        $this->title = $title;
        $this->price = $price;
        $this->brand = $brand;
    }


    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return Product
     */
    public function setPrice(int $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     * @return Product
     */
    public function setBrand(Brand $brand): Product
    {
        $this->brand = $brand;
        return $this;
    }


}
