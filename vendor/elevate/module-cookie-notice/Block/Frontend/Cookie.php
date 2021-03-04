<?php

namespace Elevate\CookieNotice\Block\Frontend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Cookie extends \Magento\Framework\View\Element\Template {

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $enable;
	
    protected $message;
	
    protected $closeText;
	
    protected $divlinkbg;
	
    protected $divlink;
	
    protected $moreinfo;

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

        $this->enable = $this->scopeConfig->getValue('elevate_cookienotice/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->message = $this->scopeConfig->getValue('elevate_cookienotice/general/display_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);


        $this->datafg = $this->scopeConfig->getValue('elevate_cookienotice/general/datafg', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->databg = $this->scopeConfig->getValue('elevate_cookienotice/general/databg', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->closeText = $this->scopeConfig->getValue('elevate_cookienotice/general/close_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->divlinkbg = $this->scopeConfig->getValue('elevate_cookienotice/general/divlinkbg', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->divlink = $this->scopeConfig->getValue('elevate_cookienotice/general/divlinktextcolour', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->moreinfo = $this->scopeConfig->getValue('elevate_cookienotice/general/moreinfolink', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

    }
    public function getDataBg() {
        return $this->databg;
    }

    public function getDataFg() {
        return $this->datafg;
    }

    public function getEnable() {
        return $this->enable;
    }
	public function getMessage() {
        return $this->message;
    }
	public function getCloseText() {
        return $this->closeText;
    }
	public function getDivlinkbg() {
        return $this->divlinkbg;
    }
	public function getDivlink() {
        return $this->divlink;
    }
	public function getMoreinfo() {
        return $this->moreinfo;
    }
}
