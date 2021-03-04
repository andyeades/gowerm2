<?php

namespace Firebear\ConfigurableProducts\Plugin\SalesRule\Model\Rule\Condition;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

/**
 * Class Product
 *
 * @package Firebear\ConfigurableProducts\Plugin\SalesRule\Model\Rule\Condition
 */
class Product
{
    /**
     * Prepare configurable product for validation.
     *
     * @param \Magento\SalesRule\Model\Rule\Condition\Product $subject
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return array
     */
    public function beforeValidate(
        \Magento\SalesRule\Model\Rule\Condition\Product $subject,
        \Magento\Framework\Model\AbstractModel $model
    ) {
        $validateProduct = $this->getProductToValidate($subject, $model);
        if ($model->getProduct() !== $validateProduct) {
            $clone = clone $model;
            $clone->setProduct($validateProduct);
            $model = $clone;
        }
        return [$model];
    }

    /**
     * Select appropriate product for validation.
     *
     * @param \Magento\SalesRule\Model\Rule\Condition\Product $subject
     * @param \Magento\Framework\Model\AbstractModel $model
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|\Magento\Catalog\Model\Product
     */
    private function getProductToValidate(
        \Magento\SalesRule\Model\Rule\Condition\Product $subject,
        \Magento\Framework\Model\AbstractModel $model
    ) {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $model->getProduct();

        $attributeCode = $subject->getAttribute();

        $children = current($model->getChildren());
        if ($product->getTypeId() == Configurable::TYPE_CODE &&
            !$product->hasData($attributeCode) &&
            !empty($children)) {
            /** @var \Magento\Catalog\Model\AbstractModel $childProduct */
            $childProduct = $children->getProduct();
            if ($childProduct->hasData($attributeCode)) {
                $product = $childProduct;
            }
        }

        return $product;
    }
}
