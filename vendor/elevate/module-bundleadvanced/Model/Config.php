<?php

namespace Elevate\BundleAdvanced\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Elevate\BundleAdvanced\Model
 */
class Config
{
    /**#@+
     * Constants for config path
     */
    const XML_PATH_GENERAL_DEFAULT_TITLE_FOR_LIST_OF_BUNDLE_PRODUCTS =
        'elevate_bundleadvanced/general/default_title_for_list_of_bundle_products';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve default title for list of bundle products
     *
     * @return string
     */
    public function getDefaultTitleForListOfBundleProducts()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_GENERAL_DEFAULT_TITLE_FOR_LIST_OF_BUNDLE_PRODUCTS);
    }
}
