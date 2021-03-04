<?php
declare(strict_types=1);
/**
 * ProductOptionPriceRender
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\ViewModel\Product\Renderer\Bundle;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\TierPrice;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;

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

    /**
     * @param Product $selection
     * @param array $args
     * @return string
     */
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
