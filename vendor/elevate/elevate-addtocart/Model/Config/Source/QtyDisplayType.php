<?php
namespace Elevate\AddToCart\Model\Config\Source;

class QtyDisplayType implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'input', 'label' => __('Standard Input Box')],
            ['value' => 'select', 'label' => __('Dropdown Select a Value')],
        ];
    }
}
