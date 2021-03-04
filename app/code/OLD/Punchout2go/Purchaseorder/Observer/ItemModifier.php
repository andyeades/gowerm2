<?php

namespace Punchout2go\Purchaseorder\Observer;

use Magento\Framework\Event\ObserverInterface;

class ItemModifier implements ObserverInterface
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

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest $document */
        $document = $observer->getData('document');
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getData('order');
        /** @var \Punchout2go\Purchaseorder\Model\Order\Admin\Create $createOrder */
        $createOrder = $observer->getData('create_order');

        /** @var \Punchout2go\Purchaseorder\Model\Order\Request\Items $documentItems */
        $documentItems = $document->getItems();

        $indexedDocumentItems = array();
        $this->_helper->debug("there are " . count($documentItems) . " document items");
        foreach ($documentItems as $item) {
            $indexedDocumentItems[$item->getInternalReferenceId()] = $item;
        }
        $this->_helper->debug("indexed document items : " . print_r($indexedDocumentItems, true));

        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($order->getAllVisibleItems() as $item) {
            $quoteItemId = $item->getQuoteItemId();
            $lookupKey = $order->getQuoteId() . '/' . $quoteItemId;
            if (isset($indexedDocumentItems[$lookupKey])) {
                $theDocumentItem = $indexedDocumentItems[$lookupKey];
                $item->setLineNumber($theDocumentItem->getLineNumber());
                $this->_helper->debug("Quote item {$order->getQuoteId()}/{$quoteItemId}: set line number to {$theDocumentItem->getLineNumber()}");
            } else {
                $this->_helper->debug("Quote item $lookupKey: not set");
            }
        }

        $order->place();
    }
}
