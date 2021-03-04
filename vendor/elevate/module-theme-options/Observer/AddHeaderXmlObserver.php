<?php
namespace Elevate\Themeoptions\Observer;

use Magento\Framework\Event\ObserverInterface;

class AddHeaderXmlObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    const XML_PATH_ELEVATEHEADER_STYLE = 'theme_options/themeoptionsheader/header_style';

    /*
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Add New Layout handle
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $elevateHeaderStyle = $this->scopeConfig->getValue(self::XML_PATH_ELEVATEHEADER_STYLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $layout = $observer->getData('layout');

        /** Apply only on pages where page is rendered */

        $currentHandles = $layout->getUpdate()->getHandles();

        if (!in_array('default', $currentHandles)) {
            return $this;
        }
                  
        switch ($elevateHeaderStyle) {
            case 'v1':
                $layout->getUpdate()->addHandle('elevate_theme_header_v1');
                break;
            case 'v2':
                $layout->getUpdate()->addHandle('elevate_theme_header_v2');
                break;
            case 'v3':
                $layout->getUpdate()->addHandle('elevate_theme_header_v3');
                break;
            case 'v4':
                $layout->getUpdate()->addHandle('elevate_theme_header_v4');
                break;
            default :
                break;
        }

        return $this;
    }
}
