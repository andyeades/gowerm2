<?php

namespace Elevate\Themeoptions\Model\Config\Source;

/**
 * Class TextTransform
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class TextTransform implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'lowercase',
                'label' => 'Lowercase',
            ),
            array(
                'value' => 'uppercase',
                'label' => 'Uppercase',
            ),
            array(
                'value' => 'capitalize',
                'label' => 'Capitalise',
            ),
            array(
                'value' => 'inherit',
                'label' => 'Inherit',
            ),
            array(
                'value' => 'full-width',
                'label' => 'Full Width',
            )
        );
    }
}