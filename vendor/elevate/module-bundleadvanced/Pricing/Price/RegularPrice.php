<?php

namespace Elevate\BundleAdvanced\Pricing\Price;

use Magento\Bundle\Pricing\Price\BundleRegularPrice;
use Magento\Bundle\Pricing\Adjustment\BundleCalculatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Bundle\Model\Product\Price;
use Magento\Catalog\Pricing\Price\CustomOptionPrice;

/**
 * Class RegularPrice
 * @package Elevate\BundleAdvanced\Pricing\Price
 */
class RegularPrice extends BundleRegularPrice
{
    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @var AmountInterface
     */
    protected $fakeMinimalPrice;

    /**
     * @param Product $saleableItem
     * @param float $quantity
     * @param BundleCalculatorInterface $calculator
     * @param PriceCurrencyInterface $priceCurrency
     * @param ProductChecker $productChecker
     */
    public function __construct(
        Product $saleableItem,
        $quantity,
        BundleCalculatorInterface $calculator,
        PriceCurrencyInterface $priceCurrency,
        ProductChecker $productChecker
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);
        $this->productChecker = $productChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinimalPrice()
    {
        if ($this->productChecker->isSimpleBundleProduct($this->getProduct())) {
            $result = $this->getFakeMinimalPrice();
        } else {
            $result = parent::getMinimalPrice();
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaximalPrice()
    {
        if (!$this->maximalPrice) {
            $price = $this->getValue();
            $fakePrice = $price;
            if ($this->product->getPriceType() == Price::PRICE_TYPE_FIXED) {
                /** @var \Magento\Catalog\Pricing\Price\CustomOptionPrice $customOptionPrice */
                $customOptionPrice = $this->priceInfo->getPrice(CustomOptionPrice::PRICE_CODE);
                $min = $customOptionPrice->getCustomOptionRange(true, $this->getPriceCode());
                $max = $customOptionPrice->getCustomOptionRange(false, $this->getPriceCode());
                $price += $max;
                $fakePrice = $min != $max ? $fakePrice + $min : $price;
            }
            $this->fakeMinimalPrice = $this->calculator->getMaxRegularAmount($fakePrice, $this->product);
            $this->maximalPrice = $this->calculator->getMaxRegularAmount($price, $this->product);
        }
        return $this->maximalPrice;
    }

    /**
     * Returns fake min price
     *
     * @return AmountInterface
     */
    public function getFakeMinimalPrice()
    {
        $this->getMaximalPrice();
        return $this->fakeMinimalPrice;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        if ($this->productChecker->isSimpleBundleProduct($this->getProduct())) {
            $result = $this->getMaximalPrice();
        } else {
            $result = parent::getAmount();
        }
        return $result;
    }
}
