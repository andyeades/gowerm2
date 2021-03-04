<?php

namespace Elevate\CartAssignments\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class ProductNamePosition implements \Magento\Framework\Data\OptionSourceInterface

{

    /**
     * Return list of Search Versions
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'above',
                'label' => 'Product Name Above Image/Price Container',
            ),
            array(
                'value' => 'below',
                'label' => 'Product Name below Image/Price Container',
            ),
            array(
                'value' => 'insidepricecontainer',
                'label' => 'Product Name Inside Price Container',
            )
        );
    }
}
