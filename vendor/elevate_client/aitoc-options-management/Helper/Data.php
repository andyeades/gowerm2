<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

namespace Aitoc\OptionsManagement\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_IS_DEFAULT_VALUE_ENABLED = 'product_options_management/general/is_default_value_enabled';
    const XML_PATH_IS_ENABLED_PER_OPTION_ENABLED = 'product_options_management/general/is_enabled_per_option_enabled';

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    protected $productMetadata;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     */
    public function __construct(Context $context, \Magento\Framework\App\ProductMetadata $productMetadata)
    {
        parent::__construct($context);
        $this->productMetadata = $productMetadata;
    }

    /**
     * @return bool
     */
    public function isMagento21x()
    {
        return version_compare($this->productMetadata->getVersion(), '2.2.0', '<');
    }

    /**
     * @return bool
     */
    public function isDefaultValueEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_IS_DEFAULT_VALUE_ENABLED);
    }

    /**
     * @return bool
     */
    public function isEnabledPerOptionEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_IS_ENABLED_PER_OPTION_ENABLED);
    }
}
