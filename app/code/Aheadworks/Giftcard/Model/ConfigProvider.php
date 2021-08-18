<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;

/**
 * Class ConfigProvider
 *
 * @package Aheadworks\Giftcard\Model
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return [
            'awGiftcard' => [
                'removeUrl' => $this->getRemoveUrl()
            ]
        ];
    }

    /**
     * Get controller URL to remove Gift Card code on cart page
     *
     * @return string
     */
    private function getRemoveUrl()
    {
        return $this->urlBuilder->getUrl('awgiftcard/cart/remove');
    }
}
