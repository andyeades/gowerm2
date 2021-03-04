<?php
namespace Elevate\Delivery\Plugin;

class DeliveryAttributesQuoteToOrderItem
{
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem \Magento\Sales\Model\Order\Item */

        // This needs to be off the fresh item, not the quote item surely?
        $orderItem = $proceed($item, $additional);
        $orderItem->setHandlingTime($item->getHandlingTime());
        $orderItem->setDateNextAvailable($item->getDateNextAvailable());
        return $orderItem;
    }
}
