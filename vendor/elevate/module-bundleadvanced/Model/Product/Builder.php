<?php

namespace Elevate\BundleAdvanced\Model\Product;

use Magento\Catalog\Model\Product;

/**
 * Class Builder
 * @package Elevate\BundleAdvanced\Model\Product
 */
class Builder
{
    /**
     * Build product
     *
     * @param Product $product
     * @return Product
     */
    public function build($product)
    {
        $product->setAwSbpDuplicateToSimpleProduct(true);

        return $product;
    }
}
