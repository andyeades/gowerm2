<?php

namespace Elevate\Themeoptions\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class CounterPosition implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'right-centre-offset',
                'label' => 'Right - Centre - Offset From Icon',
            ),
            array(
                'value' => 'right-bottom-inset-edge',
                'label' => 'Right - Bottom - Inset Edge',
            ),
            array(
                'value' => 'left-bottom-inset-edge',
                'label' => 'Left - Bottom - Inset Edge',
            )
        );
    }
}