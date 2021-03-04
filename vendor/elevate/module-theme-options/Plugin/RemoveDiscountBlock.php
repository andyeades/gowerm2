<?php

namespace Elevate\Themeoptions\Plugin;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\AbstractBlock;

class RemoveDiscountBlock {
    const BLOCK_NAME = 'checkout.cart.coupon';

    const CONFIG_PATH = 'theme_options/cart/remove_discount_block';

    private $_scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig) {
        $this->_scopeConfig = $scopeConfig;
    }

    public function afterToHtml(AbstractBlock $subject, $result)
    {
        if ($subject->getNameInLayout() === self::BLOCK_NAME && $this->_scopeConfig->getValue('theme_options/cart/remove_discount_block') === 1) {

            return '';
        }

        return $result;
    }
}
