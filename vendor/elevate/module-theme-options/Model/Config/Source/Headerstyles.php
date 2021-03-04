<?php

namespace Elevate\Themeoptions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class Headerstyles implements \Magento\Framework\Data\OptionSourceInterface
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
                'value' => 'v1',
                'label' => 'Version 1',
            ),
            array(
                'value' => 'v2',
                'label' => 'Version 2',
            ),
            array(
                'value' => 'v3',
                'label' => 'Version 3',
            ),
            array(
                'value' => 'v4',
                'label' => 'Version 4',
            )
        );
    }
}