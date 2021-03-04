<?php

namespace Elevate\Discontinuedproducts\Model\Product\Link\CollectionProvider;

class Discontinuedproducts implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        $products = $product->getDiscontinuedproductsProducts();

        if (!isset($products)) {
            return [];
        }

        return $products;
    }
}
