<?php

namespace Elevate\BundleAdvanced\Model\Product\Type\SimpleBundle;

use Magento\Bundle\Model\Product\Type as BundleProduct;
use Magento\Catalog\Model\Product;

/**
 * Class OptionResolver
 *
 * @package Elevate\BundleAdvanced\Model\Product\Type\SimpleBundle
 */
class OptionResolver
{
    /**
     * Retrieve default options data
     *
     * @param Product $product
     * @return array
     */
    public function getDefaultOptionsData($product)
    {
        $defaultOptionsData = [];
        $typeInstance = $product->getTypeInstance();
        if ($typeInstance instanceof BundleProduct) {
            $options = $typeInstance->getSelectionsCollection(
                $typeInstance->getOptionsIds($product),
                $product
            );

            foreach ($options as $option) {
                if ($option->isSalable()) {
                    $defaultOptionsData[$option->getOptionId()][$option->getEntityId()] = $option->getSelectionId();
                }
            }
        }

        return $defaultOptionsData;
    }
}
