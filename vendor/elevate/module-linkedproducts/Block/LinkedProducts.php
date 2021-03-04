<?php


namespace Elevate\LinkedProducts\Block;

/**
 * Class LinkedProducts
 *
 * @category Elevate
 * @package  Elevate\LinkedProducts\Block
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */

use Magento\Store\Model\ScopeInterface;

class LinkedProducts extends \Magento\Framework\View\Element\Template {
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Variable\Model\VariableFactory
     */
    protected $_varFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Variable\Model\VariableFactory            $varFactory
     * @param \Magento\Framework\View\Element\Template\Context   $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Variable\Model\VariableFactory $varFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_varFactory = $varFactory;
        $this->_registry = $registry;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve current Product object
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getCurrentProduct() {
        return $this->_registry->registry('current_product');
    }

    /**
     * Get Custom Variable Value
     *
     * @param string $var_to_get
     *
     * @return string
     */
    public function getVariableValue(string $var_to_get) {
        $var = $this->_varFactory->create();
        $var->loadByCode($var_to_get);

        return $var->getValue();
    }

    public function getLinkedProductShowProductPageSectionTitle() {
        $storeId = $this->_storeManager->getStore()->getId();

        return $this->scopeConfig->getValue('linkedproducts/general/show_pp_title', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getLinkedProductProductPageSectionTitle() {
        $storeId = $this->_storeManager->getStore()->getId();

        return $this->scopeConfig->getValue('linkedproducts/general/pp_title', ScopeInterface::SCOPE_STORE, $storeId);

    }
}
