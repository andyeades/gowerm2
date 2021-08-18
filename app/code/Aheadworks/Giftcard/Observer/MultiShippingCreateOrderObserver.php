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
use Magento\Sales\Model\Order;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Aheadworks\Giftcard\Model\Order\MultiShipping\Applier as MultiShippingApplier;

/**
 * Class MultiShippingCreateOrderObserver
 *
 * @package Aheadworks\Giftcard\Observer
 */
class MultiShippingCreateOrderObserver implements ObserverInterface
{
    /**
     * @var MultiShippingApplier
     */
    private $multiShippingApplier;

    /**
     * @param MultiShippingApplier $multiShippingApplier
     */
    public function __construct(
        MultiShippingApplier $multiShippingApplier
    ) {
        $this->multiShippingApplier = $multiShippingApplier;
    }

    /**
     * Apply all gift cards to orders created via multi shipping checkout
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        /** @var Address $address */
        $address = $observer->getEvent()->getAddress();
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();
        if (!$address) {
            $address = $quote->isVirtual()
                ? $quote->getBillingAddress()
                : $quote->getShippingAddress();
        }

        if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getAwGiftcardCodes()) {
            $this->multiShippingApplier->apply($quote, $address, $order);
        }

        return $this;
    }
}
