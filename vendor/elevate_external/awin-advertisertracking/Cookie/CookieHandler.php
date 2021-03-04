<?php

namespace Awin\AdvertiserTracking\Cookie;

use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

class CookieHandler
{
    private $cookieManager;

    private $cookieMetadataFactory;

    private $sessionManager;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }

    public function get($name)
    {
        return $this->cookieManager->getCookie($name);
    }

    public function set($name, $value, $duration = 2592000)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($duration)
            ->setDomain($this->sessionManager->getCookieDomain())
            ->setPath($this->sessionManager->getCookiePath())
            ->setSecure(true)
            ->setHttpOnly(true);

        $this->cookieManager->setPublicCookie(
            $name,
            $value,
            $metadata
        );
    }
}