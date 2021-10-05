<?php


namespace App\Entity;


class Order
{

    /**
     * @var array
     */
    protected $items;
    /**
     * @var Promotion|null
     */
    protected $promotion;
    /**
     * @var Payment|null
     */
    protected $payment;
    /**
     * @var bool
     */
    protected $payed = false;
    /**
     * @var int
     */
    protected $freight;
    /**
     * @var int
     */
    protected $taxFreeRawAmount;
    /**
     * @var int
     */
    protected $taxFreeAmount;
    /**
     * @var float
     */
    protected $amount;

    public function __construct(array $items, ?Promotion $promotion = null, ?Payment $payment = null)
    {
        $this->items = $items;
        $this->promotion = $promotion;
        $this->payment = $payment;
        $this->freight = 0;
        $this->taxFreeRawAmount = 0;
        $this->taxFreeAmount = 0;
    }

    /**
     * Get the number of products in the order for each brands as an array
     * ['brandName' => (int)productQuantity]
     * @return array
     */
    public function getBrandsProductsQuantities() : array
    {
        $brandsProductsQuantities = [];

        foreach ($this->items as $item) {
            if(!in_array($item->getProduct()->getBrand()->getName(), array_keys($brandsProductsQuantities))){
                $brandsProductsQuantities[$item->getProduct()->getBrand()->getName()] = $item->getQuantity();
            }
            else{
                $brandsProductsQuantities[$item->getProduct()->getBrand()->getName()] += $item->getQuantity();
            }
        }
        return $brandsProductsQuantities;
    }

    /**
     * Calculate the amount of Farmitoo products freight in euros
     * 20€ par tranche de 3 produits entamée (ex: 20€ pour 3 produits et 40€ pour 4 produits)
     * @param array $brandProductsQuantities
     * @return int
     */
    public function getProductsFreight(array $brandProductsQuantities) : int
    {
        $freight = 0;

        foreach ($brandProductsQuantities as $brandName => $brandProductsQuantity) {
            $freight += Brand::getFreightCalculation($brandName, $brandProductsQuantity);
        }

        return $freight;
    }

    /**
     * set the freight
     * @return Order
     */
    public function setFreight(): Order
    {
        $this->freight = $this->getProductsFreight($this->getBrandsProductsQuantities());
        return $this;
    }

    /**
     * calculate the price with no tax and no freight
     * @return $this
     */
    public function setTaxFreeRawAmount() : Order
    {
        $taxFreeRawAmount = 0;
        foreach ($this->items as $item){
            $taxFreeRawAmount += ($item->getProduct()->getPrice() * $item->getQuantity());
        }
        $this->taxFreeRawAmount = $taxFreeRawAmount;
        return $this;
    }

    /**
     * calculate the price with no tax
     * @return $this
     */
    public function setTaxFreeAmount() : Order
    {
        if($this->taxFreeRawAmount === 0){
            $this->setTaxFreeRawAmount();
        }
        if($this->freight === 0){
            $this->setFreight();
        }

        $this->taxFreeAmount = $this->taxFreeRawAmount + $this->freight;
        return $this;
    }

    /**
     * Set amount with taxes and freight
     * @return float
     */
    public function getAmountBeforePromotion() : float
    {
        if($this->taxFreeAmount === 0){
            $this->setTaxFreeAmount();
        }

        return $this->taxFreeAmount + $this->getTaxes();
    }

    /**
     * Calculate amount with promotions
     * @return $this
     */
    public function setAmount() : Order
    {
        $amount = $this->getAmountBeforePromotion();

        // if there is a promotion, calculate it
        if(!is_null($this->promotion)){
            // int $minAmount, int $reduction, bool $freeDelivery
            if($amount >= $this->promotion->getMinAmount()){
                if($this->promotion->isFreeDelivery()){
                    $amount -= $this->freight;
                }
                if($this->promotion->getReduction() > 0){
                    // to avoid negative number
                    if($amount < $this->promotion->getReduction()){
                        $amount = 0;
                    }
                    else{
                        $amount -= $this->promotion->getReduction();
                    }
                }
            }
        }
        $this->amount = $amount;
        return $this;
    }

    /**
     * get tax amount
     * @return float
     */
    public function getTaxes() : float
    {
        $taxAmount = 0;
        foreach ($this->items as $item) {
            $taxAmount += $item->getQuantity() * $item->getProduct()->getPrice() * $item->getProduct()->getBrand()->getTVARate();
        }
        return $taxAmount;
    }

    /**
     * @return int
     */
    public function getFreight() : int
    {
        return $this->freight;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return Promotion|null
     */
    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    /**
     * @return int
     */
    public function getTaxFreeRawAmount() : int
    {
        return $this->taxFreeRawAmount;
    }

    /**
     * @return int
     */
    public function getTaxFreeAmount() : int
    {
        return $this->taxFreeAmount;
    }

    /**
     * @return float
     */
    public function getAmount() : float
    {
        return $this->amount;
    }


}
