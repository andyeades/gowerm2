<?php
namespace Elevate\Delivery\Model\Carrier;

use Magento\Checkout\Model\ConfigProviderInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;

class CustomConfigProvider implements ConfigProviderInterface
{
    protected $checkoutSession;

    public function __construct(ScopeConfig $scopeConfig) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfig()
    {
        $config = [
            'shipping' => [
                'delivery_date' => [
                    'format' => "hihi"
                ]
            ]
        ];

        return $config;
    }


}