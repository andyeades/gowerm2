<?php

namespace Elevate\Assignments\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CapQtyType implements ArrayInterface
{

/**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'No Cap',
                'value' => ''
            ],
            1 => [
                'label' => 'Cap Qty to assigned parent',
                'value' => 'product'
            ],
            2  => [
                'label' => 'User Defined Quanity',
                'value' => 'custom'
            ],
        ];

        return $options;
    }

}