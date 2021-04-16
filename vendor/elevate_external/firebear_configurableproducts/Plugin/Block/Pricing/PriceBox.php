<?php
/**
 * Copyright © 2017 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Block\Pricing;

use Firebear\ConfigurableProducts\Block\Product\Configurable\Pricing\Renderer;
use Firebear\ConfigurableProducts\Helper\Data as CpiHelper;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableProductObject;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;

class PriceBox
{
    /**
     * @var Data
     */
    protected $catalogHelper;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ConfigurableProductObject
     */
    private $configurableProductObject;

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var CpiHelper
     */
    private $cpiHelper;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * PriceBox constructor.
     *
     * @param ProductRepository $productRepository
     * @param ConfigurableProductObject $configurableProductObject
     * @param LayoutFactory $layoutFactory
     * @param CpiHelper $cpiHelper
     * @param Registry $registry
     * @param Data $catalogHelper
     * @param CustomerSession $customerSession
     * @param array $data
     */
    public function __construct(
        ProductRepository $productRepository,
        ConfigurableProductObject $configurableProductObject,
        LayoutFactory $layoutFactory,
        CpiHelper $cpiHelper,
        Registry $registry,
        Data $catalogHelper,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->configurableProductObject = $configurableProductObject;
        $this->productRepository = $productRepository;
        $this->layoutFactory = $layoutFactory;
        $this->cpiHelper = $cpiHelper;
        $this->registry = $registry;
        $this->catalogHelper = $catalogHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\Framework\Pricing\Render\PriceBox $subject
     * @param $result
     * @return string
     * @throws NoSuchEntityException
     */
    public function afterRenderAmount(\Magento\Framework\Pricing\Render\PriceBox $subject, $result)
    {
        $registerKey = 'firebear_display_first_price';
        if ($this->registry->registry($registerKey)) {
            $oldValue = $this->registry->registry($registerKey);
            $this->registry->unregister($registerKey);
            $this->registry->register($registerKey, $oldValue + 1);
        } else {
            $this->registry->register($registerKey, 1);
        }
        if ($this->registry->registry($registerKey) == 1) {
            $customerGroupId = $this->customerSession->getCustomerGroupId();
            if ($this->cpiHelper->getGeneralConfig('general/price_range_product')
                && $this->registry->registry(
                    'current_product'
                )) {
                $productId = $subject->getRequest()->getParam('id');
                if ($productId) {
                    $product = $this->productRepository->getById($productId);
                    $parentIds = $this->configurableProductObject->getParentIdsByChild($productId);
                    $parentId = array_shift($parentIds);
                    $parentProduct = null;
                    if ($parentId) {
                        $parentProduct = $this->productRepository->getById($parentId);
                    }

                    if ($product->getTypeId() == 'configurable'
                        || ($parentProduct
                            && $parentProduct->getTypeId() == 'configurable' && $parentProduct->isSaleable())) {
                        $product = $this->registry->registry('current_product');
                        $productTypeInstance = $product->getTypeInstance();
                        $usedProducts = $productTypeInstance->getUsedProducts($product);
                        $priceArray = [];

                        foreach ($usedProducts as $child) {
                            if ($child->isSaleable()) {
                                $priceArray[] =
                                    $this->catalogHelper->getTaxPrice($child, $child->getFinalPrice(), true);
                            }
                        }

                        if ($this->cpiHelper->getGeneralConfig('general/price_range_compatible_with_tier_price')) {
                            foreach ($usedProducts as $child) {
                                if (!$child->isSaleable()) {
                                    continue;
                                }
                                $productTierPrices = $child->getTierPrices();
                                foreach ($productTierPrices as $tierPriceItem) {
                                    if ($tierPriceItem->getCustomerGroupId() == $customerGroupId) {
                                        $priceArray[] = round($tierPriceItem->getValue(), 2);
                                    }
                                }
                            }
                        }
                        $this->registry->unregister('firebear_product_prices');
                        $this->registry->register('firebear_product_prices', $priceArray);

                        $layout = $this->layoutFactory->create();
                        $block = $layout
                            ->createBlock(Renderer::class)
                            ->setTemplate('Firebear_ConfigurableProducts::product/configurable/pricing/renderer.phtml')
                            ->toHtml();

                        if ($this->cpiHelper->getGeneralConfig('general/price_range_product_original')) {
                            $block .= $result;
                        }
                        return $block;
                    }
                }
                return $result;
            }
            return $result;
        }
        return $result;
    }
}
