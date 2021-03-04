<?php

namespace Firebear\ConfigurableProducts\Pricing\Render;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Firebear\ConfigurableProducts\Helper\Data as ICPHelperData;

/**
 * Class FinalPriceBox
 * @package Firebear\ConfigurableProducts\Pricing\Render
 */
class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{
    /**
     * @var ICPHelperData
     */
    public $icpHelper;

    /**
     * FinalPriceBox constructor.
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param ICPHelperData $icpHelper
     * @param array $data
     * @param SalableResolverInterface|null $salableResolver
     * @param MinimalPriceCalculatorInterface|null $minimalPriceCalculator
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        ICPHelperData $icpHelper,
        array $data = [],
        SalableResolverInterface $salableResolver = null,
        MinimalPriceCalculatorInterface $minimalPriceCalculator = null
    ) {
        parent::__construct(
            $context,
            $saleableItem,
            $price,
            $rendererPool,
            $data,
            $salableResolver,
            $minimalPriceCalculator
        );
        $this->icpHelper = $icpHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function wrapResult($html)
    {
        if ($this->icpHelper->hidePrice()) {
            $html = $this->icpHelper->getGeneralConfig('general/price_text');
            $css = $this->getData('css_classes') . ' firebear-hide-price';
            $this->setData('css_classes', $css);
        }
        return parent::wrapResult($html);
    }
}
