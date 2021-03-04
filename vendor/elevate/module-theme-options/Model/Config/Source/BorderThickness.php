<?php

namespace Elevate\Themeoptions\Model\Config\Source;

/**
 * Class BorderThickness
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class BorderThickness implements \Magento\Framework\Data\OptionSourceInterface

{

    /**
     * Return 
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => '1px',
                'label' => '1px',
            ),
            array(
                'value' => '2px',
                'label' => '2px',
            ),
            array(
                'value' => '3px',
                'label' => '3px',
            ),
            array(
                'value' => '4px',
                'label' => '4px',
            ),
            array(
                'value' => '5px',
                'label' => '5px',
            )
        );
    }
}