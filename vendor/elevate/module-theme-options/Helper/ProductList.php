<?php
namespace Elevate\Themeoptions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class ProductList extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $logger;

    /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
    public $storeManager;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     *
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    public function isProductItemsAddToCartEnabled(){
        return $this->scopeConfig->getValue('theme_options/productgridlist/pitems_addtocart_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isProductItemsAddToLinksEnabled(){
        return $this->scopeConfig->getValue('theme_options/productgridlist/pitems_addtolinks_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    public function getMediaUrl() {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
    }
}
