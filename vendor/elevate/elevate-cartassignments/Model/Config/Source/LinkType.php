<?php

namespace Elevate\CartAssignments\Model\Config\Source;

/**
 * Class Headerstyles
 *
 * @package Elevate\Themeoptions\Model\Config\Source
 */
class LinkType implements \Magento\Framework\Data\OptionSourceInterface

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
                'value' => 'target_blank',
                'label' => 'Popup in new tab (Target = Blank)',
            ),
            array(
                'value' => 'lightbox',
                'label' => 'Lightbox',
            )
        );
    }
}