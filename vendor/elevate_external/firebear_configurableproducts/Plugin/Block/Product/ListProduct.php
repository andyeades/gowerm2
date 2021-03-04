<?php
/**
 * Copyright © 2017 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Block\Product;

use Firebear\ConfigurableProducts\Helper\Data as CpiHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\View\Element\Template;
use Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type\Configurable as IcpConfigurable;
use Magento\Customer\Model\Session;

class ListProduct
{
    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    private $listProductBlock;

    /**
     * @var Configurable
     */
    private $configurableProduct;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $pricingHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var CpiHelper
     */
    private $cpiHelper;

    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogHelper;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * ListProduct constructor.
     *
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Firebear\ConfigurableProducts\Helper\Data $cpiHelper
     */
    public function __construct(
        Configurable $configurableProduct,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Helper\Data $catalogHelper,
        CpiHelper $cpiHelper,
        Session $customerSession
    ) {
        $this->configurableProduct = $configurableProduct;
        $this->pricingHelper = $pricingHelper;
        $this->logger = $logger;
        $this->cpiHelper = $cpiHelper;
        $this->catalogHelper = $catalogHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\Catalog\Block\Product\ListProduct $subject
     * @param \Closure $proceed
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return mixed|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundGetProductPrice(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
    ) {

        if (Configurable::TYPE_CODE !== $product->getTypeId()) {
            return $proceed($product);
        }

        $this->listProductBlock = $subject;
        if ($this->cpiHelper->getGeneralConfig('general/price_range_category')) {
            $priceText = $this->getPriceRange($product);
        } else {
            return $proceed($product);
        }
        return $priceText;
    }


    /**
     * Get configurable product price range
     *
     * @param $product
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPriceRange($product)
    {
        $childProductPrice = [];
        $childProducts     = $this->configurableProduct->getUsedProducts($product);
        $customerGroupId = $this->customerSession->getCustomerGroupId();

        foreach ($childProducts as $child) {
            $price = number_format(
                $this->catalogHelper->getTaxPrice($child, $child->getPrice(), true),
                2,
                '.',
                ''
            );
            $finalPrice = number_format(
                $this->catalogHelper->getTaxPrice($child, $child->getFinalPrice(), true),
                2,
                '.',
                ''
            );
            $productTierPrices = $child->getTierPrices();
            if ($this->cpiHelper->getGeneralConfig('general/price_range_compatible_with_tier_price')) {
                foreach ($productTierPrices as $tierPriceItem) {
                    $tierPriceCustomerGroupId = $tierPriceItem->getCustomerGroupId();
                    if ($tierPriceCustomerGroupId == $customerGroupId ||
                        $tierPriceCustomerGroupId == IcpConfigurable::ALL_CUSTOMER_GROUPS) {
                        $childProductPrice[] = round($tierPriceItem->getValue(), 2);
                    }
                }
            }
            if ($price == $finalPrice) {
                $childProductPrice[] = $price;
            } elseif ($finalPrice < $price) {
                $childProductPrice[] = $finalPrice;
            }
        }
        $max = $this->pricingHelper->currencyByStore(max($childProductPrice));
        $min = $this->pricingHelper->currencyByStore(min($childProductPrice));
        if (!$this->cpiHelper->hidePrice()) {
            if ($min === $max) {
                return $this->getPriceRenderChange($product, "$min", '');
            }
            if ($this->cpiHelper->getGeneralConfig('general/price_range_category_from_to_option')) {
                return $this->getPriceRenderChange($product, __('From') . " $min - $max", '');
            }
            return $this->getPriceRenderChange($product, __('From') . " $min", '');
        } else {
            return $this->getPriceRenderChange($product, $this->cpiHelper->getGeneralConfig('general/price_text'));
        }
    }

    /**
     * Price renderer
     *
     * @param $product
     * @param $price
     *
     * @param string $text
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getPriceRenderChange($product, $price, $text = '')
    {
        return $this->listProductBlock->getLayout()->createBlock(Template::class)
            ->setTemplate('Firebear_ConfigurableProducts::product/price/range/price.phtml')
            ->setData('price_id', 'product-price-' . $product->getId())
            ->setData('display_label', $text)
            ->setData('product_id', $product->getId())
            ->setData('display_value', $price)->toHtml();
    }
}