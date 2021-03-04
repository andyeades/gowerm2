<?php
namespace Elevate\Delivery\Observer;

use Magento\Framework\Event\ObserverInterface;

class SetCustomDeliveryAttributes implements ObserverInterface
{

    /* @var \Magento\GiftMessage\Model\GiftMessageManager */
    protected $giftMessageManager;

    /* @var \Magento\GiftMessage\Model\MessageFactory */
    protected $giftMessageFactory;

    /* @var \Magento\GiftMessage\Model\ResourceModel\Message $resource */
    protected $giftMessageResource;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;


    /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService

     */
    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\GiftMessage\Model\GiftMessageManager $giftMessageManager,
        \Magento\GiftMessage\Model\MessageFactory $giftMessageFactory,
        \Magento\GiftMessage\Model\ResourceModel\Message $giftMessageResource
    ) {
        $this->objectCopyService = $objectCopyService;
        $this->giftMessageManager = $giftMessageManager;
        $this->giftMessageFactory = $giftMessageFactory;
        $this->giftMessageResource = $giftMessageResource;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');


        // This Works!!!
        // RJ - 3/7/2020

        $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote_address', 'to_order_address', $quote, $order);



        $detailed_delivery_teamnumber = $quote->getShippingAddress()->getDetailedDeliveryTeamnumber();
        $detailed_delivery_info_dates = $quote->getShippingAddress()->getDetailedDeliveryInfoDates();

        //$order->setShippingDescription('My Test');

        $order->setDetailedDeliveryInfoDates($detailed_delivery_info_dates);
        $order->setDetailedDeliveryTeamnumber($detailed_delivery_teamnumber);



        $ev_giftmessagemessage = $quote->getEvGiftmessagemessage();
        $order->setEvGiftmessagemessage($ev_giftmessagemessage);

        if ($ev_giftmessagemessage) {
            $gift_message_obj = $this->giftMessageFactory->create();

            $gift_message = $order->getEvGiftmessagemessage();
            $gift_message_obj->setMessage($gift_message);

            $this->giftMessageResource->save($gift_message_obj);

            $gift_message_id = $gift_message_obj->getId();
            $order->setGiftMessageId($gift_message_id);
        }
       //$order->save();

        return $this;
    }
}
