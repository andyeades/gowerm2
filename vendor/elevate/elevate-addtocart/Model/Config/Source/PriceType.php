<?php
namespace Elevate\AddToCart\Model\Config\Source;

class PriceType implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'inc_vat', 'label' => __('Inc VAT Only')],
            ['value' => 'ex_vat', 'label' => __('Ex VAT Only')],
            ['value' => 'both_vat', 'label' => __('Inc Vat & Ex Vat')],
            ['value' => 'disable_functionality', 'label' => __('Disable Functionality')]
        ];
    }
}
