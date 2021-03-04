<?php

namespace Firebear\ConfigurableProducts\Model\Config\Source;

use Firebear\ConfigurableProducts\Helper\Data;

/**
 * Class ProductAttributeOptions
 * @package Firebear\ConfigurableProducts\Model\Config\Source
 */
class ProductAttributeOptions implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Data
     */
    protected $icpHelperData;

    /**
     * ProductAttributeOptions constructor.
     * @param Data $icpHelperData
     */
    public function __construct(Data $icpHelperData)
    {
        $this->icpHelperData = $icpHelperData;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->icpHelperData->getAttributesOptionsForMatrix();
    }
}
