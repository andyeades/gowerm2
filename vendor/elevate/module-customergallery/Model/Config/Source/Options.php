<?php

namespace Elevate\CustomerGallery\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Options implements ArrayInterface
{

/**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Please select',
                'value' => 0
            ],
            1 => [
                'label' => 'Instagram',
                'value' => 1
            ],
            2  => [
                'label' => 'Twitter',
                'value' => 2
            ],
            3 => [
                'label' => 'Facebook',
                'value' => 3
            ],
            4 => [
                'label' => 'Email',
                'value' => 4
            ],
            5 => [
                'label' => 'Feefo',
                'value' => 5
            ],
        ];

        return $options;
    }

}