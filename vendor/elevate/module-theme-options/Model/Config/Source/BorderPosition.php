<?php

namespace Elevate\Themeoptions\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class BorderPosition implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'all',
                'label' => 'All Sides Border',
            ),
            array(
                'value' => 'bottom',
                'label' => 'Bottom Border',
            )
        );
    }
}