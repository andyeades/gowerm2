<?php

namespace Elevate\LandingPages\Model\Config\Source;

/**
 * Class FilterOpenClose
 *
 * @package Elevate\LandingPages\Model\Config\Source
 */
class FilterOpenClose implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'chevrons-updown',
                'label' => 'Chevrons - Up (Close) - Down (Open)',
            ],
            [
                'value' => 'plusminus',
                'label' => '+ - Symbols - + (Open) - Minus (Close)',
            ]
        ];
    }
}
