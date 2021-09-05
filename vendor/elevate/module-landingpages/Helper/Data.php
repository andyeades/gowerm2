<?php
namespace Elevate\LandingPages\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_CUSTOMROUTE_ROUTE  = 'shopall/general/route';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    public function getLandingPageCategoryExcludes()
    {
        return $this->scopeConfig->getValue('elevate_landingpages/categories/exclude_categories', ScopeInterface::SCOPE_STORE);
    }

    public function getShowMoreNumber()
    {
        return $this->scopeConfig->getValue('elevate_landingpages/categories/show_more_number', ScopeInterface::SCOPE_STORE);
    }

    public function getFilterOpenCloseIconType()
    {
        return $this->scopeConfig->getValue('elevate_landingpages/categories/filter_openclose', ScopeInterface::SCOPE_STORE);
    }
    /**
     * @return string
     */
    public function getModuleRoute()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CUSTOMROUTE_ROUTE, ScopeInterface::SCOPE_STORE);
    }
}
