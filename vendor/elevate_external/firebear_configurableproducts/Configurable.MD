<?php

namespace Firebear\ConfigurableProducts\Block\Product\View\Type;

class Configurable extends \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable
{

    /**
     * Collect price options
     *
     * @return array
     */
    protected function getOptionPrices()
    {
        $prices = [];
        foreach ($this->getAllowProducts() as $product) {
            $tierPrices = [];
            $priceInfo = $product->getPriceInfo();
            $tierPriceModel =  $priceInfo->getPrice('tier_price');
            $tierPricesList = $tierPriceModel->getTierPriceList();
            foreach ($tierPricesList as $tierPrice) {
                $tierPrices[] = [
                    'qty' => $this->localeFormat->getNumber($tierPrice['price_qty']),
                    'price' => $this->localeFormat->getNumber($tierPrice['price']->getValue()),
                    'percentage' => $this->localeFormat->getNumber(
                        $tierPriceModel->getSavePercent($tierPrice['price'])
                    ),
                ];
            }

            $prices[$product->getId()] =
                [
                    'oldPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $priceInfo->getPrice('regular_price')->getAmount()->getValue()
                        ),
                    ], 
                    'basePrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $priceInfo->getPrice('final_price')->getAmount()->getBaseAmount()
                        ),
                    ],
                    'wasPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $product->getWasPrice()
                        ),
                    ],
                    'finalPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $priceInfo->getPrice('final_price')->getAmount()->getValue()
                        ),
                    ],
                    'tierPrices' => $tierPrices,
                    'msrpPrice' => [
                        'amount' => $this->localeFormat->getNumber(
                            $product->getMsrp()
                        ),
                    ],
                ];
        }
        return $prices;
    }


}