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
namespace Aheadworks\Giftcard\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard;
use Aheadworks\Giftcard\Model\Product\Configuration;

/**
 * Class InitOptionRendererObserver
 *
 * @package Aheadworks\Giftcard\Observer
 */
class InitOptionRendererObserver implements ObserverInterface
{
    /**
     * Initialize product options renderer with aw-giftcard specific params
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getBlock();
        $block->addOptionsRenderCfg(Giftcard::TYPE_CODE, Configuration::class);
        return $this;
    }
}
