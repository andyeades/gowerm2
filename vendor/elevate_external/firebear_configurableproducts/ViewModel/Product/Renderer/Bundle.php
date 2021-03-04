<?php
declare(strict_types=1);
/**
 * Bundle
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\ViewModel\Product\Renderer;

use Magento\Customer\Model\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Bundle
 * @package Firebear\ConfigurableProducts\ViewModel\Product\Renderer
 */
class Bundle implements ArgumentInterface
{
    /**
     * Config path if swatch tooltips are enabled
     */
    private const XML_PATH_HIDE_PRICE = 'firebear_configurableproducts/general/hide_price';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var HttpContext|null
     */
    protected $httpContext;

    /**
     * Bundle constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param HttpContext|null $httpContext
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        HttpContext $httpContext = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->httpContext = $httpContext ?: ObjectManager::getInstance()->get(HttpContext::class);
    }

    /**
     * @return bool
     */
    public function hidePrice()
    {
        $isLoggedIn = $this->httpContext->getValue(Context::CONTEXT_AUTH);
        return $this->scopeConfig->getValue(
            self::XML_PATH_HIDE_PRICE,
            ScopeInterface::SCOPE_STORE
        ) && $isLoggedIn;
    }
}
