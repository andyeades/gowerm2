<?php

namespace Firebear\ConfigurableProducts\Block\DataProviders;


use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Render;
use Magento\Catalog\Pricing\Price\TierPrice;

class ProductOptionPriceRender implements ArgumentInterface
{
    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * ProductOptionPriceRender constructor.
     * @param LayoutInterface $layout
     */
    public function __construct(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function renderTierPrice(Product $selection, array $args = [])
    {
        if (!array_key_exists('zone', $args)) {
            $args['zone'] = Render::ZONE_ITEM_OPTION;
        }
        $productPriceHtml = '';

        /** @var Render $productPriceRender */
        $productPriceRender = $this->layout->getBlock('product.price.render.default');
        if ($productPriceRender !== false) {
            $productPriceHtml = $productPriceRender->render(
                TierPrice::PRICE_CODE,
                $selection,
                $args
            );
        }
        return $productPriceHtml;
    }
}
