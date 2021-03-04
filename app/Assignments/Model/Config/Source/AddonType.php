<?php

namespace Elevate\Assignments\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class AddonType implements ArrayInterface
{

/**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Product',
                'value' => 'product'
            ],
            1 => [
                'label' => 'MultiBox',
                'value' => 'multibox'
            ],
            2  => [
                'label' => 'Static',
                'value' => 'static'
            ],
            3 => [
                'label' => 'Promotion',
                'value' => 'promo'
            ],
        ];

        return $options;
    }

}