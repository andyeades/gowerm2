<?php

namespace Elevate\Delivery\Observer;

use Magento\Framework\Event\ObserverInterface;

class SetItemDeliveryAttributes implements ObserverInterface {
    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        $quoteItem->setHandlingTime($product->getHandlingTime());
        $quoteItem->setDateNextAvailable($product->getDateNextAvailable());
    }
}
