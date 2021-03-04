<?php

namespace Punchout2go\Purchaseorder\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderRequestIdUpdater implements ObserverInterface
{
    protected $_helper;

    /**
     * ItemModifier constructor.
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     */
    public function __construct(
        \Punchout2go\Purchaseorder\Helper\Data $helper
    )
    {
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        /** @var \Magento\Sales\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        $order->setOrderRequestId($quote->getOrderRequestId());
        $order->addStatusHistoryComment("Order received (PO Number {$order->getPayment()->getPoNumber()})");
    }
}
