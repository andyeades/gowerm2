<?php
namespace Elevate\BundleAdvanced\Observer;
class SaveOptions implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        echo "SAVE OPTIONS";
        exit;

        /* before quote submit save the freight list values in sales_order_address table
        */
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote(); $order->getShippingAddress()->setSampleAttribute($quote->getShippingAddress()->getSampleAttribute());
        return $this;
    }
}