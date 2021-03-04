<?php

namespace Elevate\BundleAdvanced\Model\Product;

use Elevate\BundleAdvanced\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Bundle\Model\Product\Type as BundleProduct;

/**
 * Class Checker
 * @package Elevate\BundleAdvanced\Model\Product
 */
class Checker
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * Check if simple bundle product or not
     *
     * @param ProductInterface|null $product
     * @return bool
     */
    public function isSimpleBundleProduct($product = null)
    {
        $product = $product ? : $this->registry->registry('current_product');

        return $product instanceof Product
            && $product->getTypeId() == BundleProduct::TYPE_CODE
            && $this->getProductAttribute($product, ProductAttributeInterface::CODE_ELEVATE_BUNDLEADVANCED_BUNDLE_PRODUCT_TYPE) === "1";
    }

    /**
     * Check if bundle product or not
     *
     * @param ProductInterface|null $product
     * @return bool
     */
    public function isNotSimpleBundleProduct($product = null)
    {
        $product = $product ? : $this->registry->registry('current_product');

        return $product instanceof Product
            && $product->getTypeId() == BundleProduct::TYPE_CODE
            && $this->getProductAttribute($product, ProductAttributeInterface::CODE_ELEVATE_BUNDLEADVANCED_BUNDLE_PRODUCT_TYPE) !== "1";
    }

    /**
     * Retrieve product attribute by code
     *
     * @param Product $product
     * @param string $code
     * @return mixed
     */
    private function getProductAttribute($product, $code)
    {
        if (!$product->hasData($code)) {
            $product->getResource()->load($product, $product->getId());
        }
        return $product->getData($code);
    }
}
