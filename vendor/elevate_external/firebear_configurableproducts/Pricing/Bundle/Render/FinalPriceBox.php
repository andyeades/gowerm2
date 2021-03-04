<?php

namespace Firebear\ConfigurableProducts\Pricing\Bundle\Render;

use Magento\Bundle\Pricing\Price\FinalPrice;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\CustomOptionPrice;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;
use Firebear\ConfigurableProducts\Helper\Data as IcpHelper;


class FinalPriceBox extends \Magento\Bundle\Pricing\Render\FinalPriceBox
{
    /**
     * @var IcpHelper
     */
    public $icpHelper;

    /**
     * FinalPriceBox constructor.
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param IcpHelper $icpHelper
     * @param array $data
     * @param SalableResolverInterface|null $salableResolver
     * @param MinimalPriceCalculatorInterface|null $minimalPriceCalculator
     */
    public function __construct(
        Context $context,
        SaleableInterface
        $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        IcpHelper $icpHelper,
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
     * @return bool|mixed
     */
    public function showPrice()
    {
        if ($this->icpHelper->hidePrice()) {
            return $this->icpHelper->getGeneralConfig('general/price_text');
        } else {
            return true;
        }
    }
}
