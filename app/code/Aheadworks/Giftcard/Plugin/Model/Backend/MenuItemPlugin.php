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
namespace Aheadworks\Giftcard\Plugin\Model\Backend;

use Magento\Backend\Model\UrlInterface;
use Magento\Backend\Model\Menu\Item;

/**
 * Class MenuItemPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model\Backend
 */
class MenuItemPlugin
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @param UrlInterface $url
     */
    public function __construct(
        UrlInterface $url
    ) {
        $this->url = $url;
    }

    /**
     * Update discount amount value
     *
     * @param Item $subject
     * @param \Closure $proceed
     * @return string
     */
    public function aroundGetUrl(Item $subject, \Closure $proceed)
    {
        if ($subject->getAction() == 'catalog/product/') {
            return $this->url->getUrl(
                (string)$subject->getAction(),
                ['_cache_secret_key' => true, 'menu' => true]
            );
        }
        return $proceed();
    }
}
