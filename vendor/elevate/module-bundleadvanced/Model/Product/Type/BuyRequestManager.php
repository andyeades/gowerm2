<?php

namespace Elevate\BundleAdvanced\Model\Product\Type;

use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;
use Elevate\BundleAdvanced\Model\Product\Type\SimpleBundle\OptionResolver as SimpleBundleProductOptionResolver;

/**
 * Class BuyRequestManager
 * @package Elevate\BundleAdvanced\Model\Product\Type
 */
class BuyRequestManager
{
    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @var SimpleBundleProductOptionResolver
     */
    private $simpleBundleProductOptionResolver;

    /**
     * @param ProductChecker $productChecker
     * @param SimpleBundleProductOptionResolver $simpleBundleProductOptionResolver
     */
    public function __construct(
        ProductChecker $productChecker,
        SimpleBundleProductOptionResolver $simpleBundleProductOptionResolver
    ) {
        $this->productChecker = $productChecker;
        $this->simpleBundleProductOptionResolver = $simpleBundleProductOptionResolver;
    }

    /**
     * Add default options to $buyRequest if needed
     *
     * @param Product $product
     * @param DataObject $buyRequest
     * @return DataObject
     */
    public function addDefaultOptions($product, $buyRequest)
    {
        if ($this->productChecker->isSimpleBundleProduct($product)) {
            $bundleOptionsData = $this->simpleBundleProductOptionResolver->getDefaultOptionsData($product);
            $buyRequest->setBundleOption($bundleOptionsData);
        }
        return $buyRequest;
    }
}
