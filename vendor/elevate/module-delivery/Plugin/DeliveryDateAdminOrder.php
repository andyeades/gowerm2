<?php
namespace Elevate\Delivery\Plugin;

class DeliveryDateAdminOrder
{
    public function aroundConvert(
        \Magento\Quote\Model\Quote\ToOrder $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote $quote,
        $additional = []
    ) {
        /** @var $order \Magento\Sales\Model\Order */
        $order = $proceed($quote, $additional);


        /** @var $orderAddress \Magento\Sales\Model\Order\Address */

        $orderAddress = $order->getShippingAddress();

        $detailedDeliveryinfoDates = $orderAddress->getDetailedDeliveryInfoDates();
        $detailedDeliveryTeamnumber = $orderAddress->getDetailedDeliveryTeamnumber();
        $ev_giftmessagemessage = $orderAddress->getEvGiftmessagemessage();


        $order->setDeliverySelectedSummarytext($quote->getDeliverySelectedSummarytext());
        $order->setDetailedDeliveryInfo($detailedDeliveryinfoDates);
        $order->setDetailedDeliveryInfoDates($detailedDeliveryinfoDates);
        $order->setDetailedDeliveryTeamnumber($detailedDeliveryTeamnumber);
        $order->setEvGiftmessagemessage($ev_giftmessagemessage);


        return $order;
    }
}
