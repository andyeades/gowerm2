<?php

namespace Elevate\Megamenu\Plugin;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\AbstractBlock;

class RemoveBlock
{
    const BLOCK_NAME = 'catalog.topnav';

    const CONFIG_PATH = 'elevatemegamenuconfig/general/is_enabled';

    private $_scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig) {
        $this->_scopeConfig = $scopeConfig;
    }

    public function afterToHtml(AbstractBlock $subject, $result)
    {

        if ($subject->getNameInLayout() === self::BLOCK_NAME && $this->_scopeConfig->getValue(self::CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            return '';
        }

        return $result;
    }
}