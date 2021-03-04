<?php

namespace Elevate\Shipping\Observer;

use Magento\Framework\Event\ObserverInterface;

class DataAssignObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();
        
        $order->setDeliveryDate($quote->getDeliveryDate());
        
        if($quote->getDeliveryLocation()) {
        	$order->setDeliveryLocation($quote->getDeliveryLocation());
        }
        return $this;
    }
}