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

use Aheadworks\Giftcard\Api\Data\ProductAttributeInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class UpdateExcludedFieldListObserver
 *
 * @package Aheadworks\Giftcard\Observer
 */
class UpdateExcludedFieldListObserver implements ObserverInterface
{
    /**
     * Exclude Gift Card attributes from Update Attributes mass-action form
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getEvent()->getObject();
        $list = $block->getFormExcludedFieldList();
        $excludedAttributes = [
            ProductAttributeInterface::CODE_AW_GC_EMAIL_TEMPLATES,
            ProductAttributeInterface::CODE_AW_GC_AMOUNTS,
        ];
        $list = array_merge($list, $excludedAttributes);
        $block->setFormExcludedFieldList($list);
        return $this;
    }
}
