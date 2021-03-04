<?php

namespace Elevate\Assignments\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class LinkType implements ArrayInterface
{

/**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Please Select..',
                'value' => -1
            ],
            1 => [
                'label' => 'Popup in new tab (Target = Blank)',
                'value' => 'target_blank'
            ],
            2  => [
                'label' => 'Lightbox',
                'value' => 'lightbox'
            ],
        ];

        return $options;
    }

}