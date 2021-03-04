<?php

namespace Elevate\Themeoptions\Model\Config\Source;

/**
 *
 * class LeftRight
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class LeftRight implements \Magento\Framework\Data\OptionSourceInterface
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
                'value' => 'left',
                'label' => 'Left',
            ),
            array(
                'value' => 'right',
                'label' => 'Right',
            )
        );
    }
}
