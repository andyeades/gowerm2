<?php

namespace Elevate\CartAssignments\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class AddonType implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'product',
                'label' => 'Product',
            ),
            array(
                'value' => 'multibox',
                'label' => 'Multibox',
            ),
            array(
                'value' => 'static',
                'label' => 'Static',
            ),
            array(
                'value' => 'promotion',
                'label' => 'Promotion',
            )
        );
    }
}