<?php

namespace Elevate\AddToCart\Block\Frontend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Minicart extends \Magento\Framework\View\Element\Template {

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $noItemText;

    protected $minicartCustomCartIconOn;

    protected $customCartIconStyling;

    protected $customCartIconHtml;

    protected $actionsPosition;

    protected $cartIconClass;
    /**
     * @param \Magento\Backend\Block\Template\Context            $context
     * @param \Magento\Framework\Registry                        $registry
     * @param \Magento\Framework\Data\FormFactory                $formFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;

        $this->minicartCustomCartIconOn = $this->scopeConfig->getValue('elevate_addtocart/minicart/custom_cart_icon_on', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->noItemText = $this->scopeConfig->getValue('elevate_addtocart/minicart/no_items_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->cartIconClass = $this->scopeConfig->getValue('elevate_addtocart/minicart/cart_icon_class', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->customCartIconHtml = $this->scopeConfig->getValue('elevate_addtocart/minicart/custom_cart_icon_html', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->customCartIconStyling = $this->scopeConfig->getValue('elevate_addtocart/minicart/custom_cart_icon_styling', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

    }
    public function getMinicartNoItemText() {
        return $this->noItemText;
    }

    public function getMinicartCustomCartIconOn() {
        return $this->minicartCustomCartIconOn;
    }
    public function getMinicartCartIconClass() {
        return $this->cartIconClass;
    }

    public function getCustomCartIconHtml() {
        return $this->customCartIconHtml;
    }

    public function getCustomCartIconStyling() {
        return $this->customCartIconStyling;
    }
}
