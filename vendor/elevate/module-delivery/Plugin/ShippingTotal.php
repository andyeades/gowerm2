<?php
namespace Elevate\Delivery\Plugin;
//namespace /Model/Quote/Address/Total;

class ShippingTotal {

    public function aroundCollect(
        \Magento\Quote\Model\Quote\Address\Total\Shipping $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ){
        //do something before

        //$returnValue = $proceed($quote, $shippingAssignment, );

        //$returnValue = 1;

        //do something after

        return;
    }

}