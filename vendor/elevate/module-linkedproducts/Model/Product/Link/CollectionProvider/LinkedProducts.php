<?php

namespace Elevate\LinkedProducts\Model\Product\Link\CollectionProvider;

class LinkedProducts implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        $products = $product->getLinkedproductsProducts();

        if (!isset($products)) {
            return [];
        }

        return $products;
    }
}