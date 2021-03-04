<?php

namespace Elevate\Themeoptions\Model\Config\Source;

/**
 *
 * class SearchStyle
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class SearchStyle implements \Magento\Framework\Data\OptionSourceInterface
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
                'value' => 'normal',
                'label' => 'Normal',
            ),
            array(
                'value' => 'expands',
                'label' => 'Expands on click',
            )
        );
    }
}
