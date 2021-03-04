<?php
namespace Elevate\Themeoptions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Settings extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $logger;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    public function getSetting($value) {
        $setting = $this->scopeConfig->getValue($value);
        return $setting;
    }
}
