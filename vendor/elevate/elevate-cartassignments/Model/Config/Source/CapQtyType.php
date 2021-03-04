<?php

namespace Elevate\CartAssignments\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class CapQtyType implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => '',
                'label' => 'No Cap',
            ),
            array(
                'value' => 'product',
                'label' => 'Cap Qty to assigned parent',
            ),
            array(
                'value' => 'Custom',
                'label' => 'User Defined Quanity',
            )
        );
    }
}