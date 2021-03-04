<?php

namespace Elevate\BundleAdvanced\Api;

/**
 * Interface BundleProductManagementInterface
 * @api
 */
interface BundleProductManagementInterface
{
    /**
     * Duplicate product as Simple Bundle Product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface int $product
     * @return \Magento\Catalog\Api\Data\ProductInterface|\Magento\Catalog\Model\Product
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function duplicateAsSimpleBundle($product);
}
