<?php
namespace Elevate\AddToCart\Model\Config\Source;

class ActionsPosition implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'inside_qty_container', 'label' => __('Inside Qty Container')],
            ['value' => 'outside_qty_container', 'label' => __('Outside Qty Container')],
        ];
    }
}
