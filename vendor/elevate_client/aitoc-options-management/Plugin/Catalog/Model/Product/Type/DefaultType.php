<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Model\Product\Type;


class DefaultType
{
    /**
     * @var \Magento\Catalog\Model\Product;
     */
    protected $product = null;

    /**
     * @param \Magento\Catalog\Model\Product\Type\AbstractType $productType
     * @param \Magento\Catalog\Model\Product $product
     * @param string $sku
     * @return array
     */
    public function beforeGetOptionSku($productType, $product, $sku = '')
    {
        $this->product = $product;
        return [$product, $sku];
    }

    /**
     * @param \Magento\Catalog\Model\Product\Type\AbstractType $productType
     * @param string $result
     * @return string
     */
    public function afterGetOptionSku($productType, $result)
    {
        if ($this->product->getIsReplaceProductSku() && strlen($result) > strlen($this->product->getData('sku'))) {
            $result = trim(substr($result, strlen($this->product->getData('sku'))), '-');
        }

        return $result;
    }
}
