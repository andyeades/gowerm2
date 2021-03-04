<?php
namespace Elevate\ShopAll\Helper;
 
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
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
 
    }
 
    /**
     * @return string
     */
    public function getModuleRoute()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CUSTOMROUTE_ROUTE, ScopeInterface::SCOPE_STORE);
    }
}