<?php


namespace App\Entity;


class Brand
{
    public const BRANDS_NAMES = [
        'Farmitoo' => 'Farmitoo',
        'Gallagher' => 'Gallagher',
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $TVARate;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->setTVARate();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Brand
     */
    public function setName(string $name): Brand
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getTVARate(): float
    {
        return $this->TVARate;
    }

    protected function setTVARate() : void
    {
        $taxRate = 0.0;
        if($this->name === self::BRANDS_NAMES['Farmitoo']){
            $taxRate = 0.2;
        }
        elseif($this->name === self::BRANDS_NAMES['Gallagher']){
            $taxRate = 0.05;
        }
        // todo : add rates

        $this->TVARate = $taxRate;
    }

    /**
     * Calculate the freight depending on product quantity
     * @param string $brandName
     * @param int $productsQuantity
     * @return int
     */
    public static function getFreightCalculation(string $brandName, int $productsQuantity) : int
    {
        $freight = 0;
        if($brandName === self::BRANDS_NAMES['Farmitoo'] && $productsQuantity > 0){
            $freight = 15;
        }
        elseif($brandName === self::BRANDS_NAMES['Gallagher'] && $productsQuantity > 0){
            $freight = (ceil($productsQuantity / 3)) * 20;
        }

        // todo : add cases if we add brands (best if we have it in database)

        return $freight;
    }


}