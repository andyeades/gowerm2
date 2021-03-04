<?php

namespace Elevate\Themeoptions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Border styles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class BorderStyle implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Return list of Custom Header Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'solid',
                'label' => 'solid',
            ),
            array(
                'value' => 'dotted',
                'label' => 'dotted',
            ),
            array(
                'value' => 'none',
                'label' => 'none',
            ),
            array(
                'value' => 'dashed',
                'label' => 'dashed',
            ),
            array(
                'value' => 'dotted',
                'label' => 'dotted',
            ),
            array(
                'value' => 'double',
                'label' => 'double',
            ),
            array(
                'value' => 'groove',
                'label' => 'groove',
            ),
            array(
                'value' => 'hidden',
                'label' => 'hidden'
            ),
            array(
                'value' => 'inset',
                'label' => 'inset'
            ),
            array(
                'value' => 'outset',
                'label' => 'outset'
            ),
        );
    }
}