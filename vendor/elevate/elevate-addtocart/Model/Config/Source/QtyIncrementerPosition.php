<?php
namespace Elevate\AddToCart\Model\Config\Source;

class QtyIncrementerPosition implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'leftofqtyinput', 'label' => __('Left of Qty Input')],
            ['value' => 'rightofqtyinput', 'label' => __('Right of Qty Input')],
            ['value' => 'minusleftplusright', 'label' => __('Minus to Left of Qty, Plus to right of Qty Input')],
            ['value' => 'minusrightplusleft', 'label' => __('Plus to Left of Qty, Minus to right of Qty Input')],
        ];
    }
}
