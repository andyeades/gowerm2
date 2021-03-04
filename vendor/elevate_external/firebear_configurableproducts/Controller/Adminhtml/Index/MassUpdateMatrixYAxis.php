<?php

namespace Firebear\ConfigurableProducts\Controller\Adminhtml\Index;

use Firebear\ConfigurableProducts\Model\ProductOptions;

/**
 * Class MassUpdateMatrixYAxis
 * @package Firebear\ConfigurableProducts\Controller\Adminhtml\Index
 */
class MassUpdateMatrixYAxis extends MassUpdateMatrixXAxis
{
    /**
     * @param ProductOptions $productOption
     * @param $attributeForMatrixAxis
     */
    protected function updateMatrixAxis($productOption, $attributeForMatrixAxis)
    {
        $productOption->setYAxis($attributeForMatrixAxis);
    }
}
