<?php
namespace Elevate\AddToCart\Model\Config\Source;

class QtyIncrementerType implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'sidebyside', 'label' => __('Horizontal (Side by Side)')],
            ['value' => 'stacked', 'label' => __('Vertical (Stacked)')]
        ];
    }
}
