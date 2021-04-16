<?php
namespace Elevate\Delivery\Plugin;

class DeliveryAttributesQuoteAddressToOrderAddress
{
    /* @var \Magento\GiftMessage\Model\GiftMessageManager */
    protected $giftMessageManager;

    /* @var \Magento\GiftMessage\Model\MessageFactory */
    protected $giftMessageFactory;

    /* @var \Magento\GiftMessage\Model\ResourceModel\Message $resource */
    protected $giftMessageResource;



    public function __construct(
        \Magento\GiftMessage\Model\GiftMessageManager $giftMessageManager,
\Magento\GiftMessage\Model\MessageFactory $giftMessageFactory,
        \Magento\GiftMessage\Model\ResourceModel\Message $giftMessageResource
    ) {
        $this->giftMessageManager = $giftMessageManager;
        $this->giftMessageFactory = $giftMessageFactory;
        $this->giftMessageResource = $giftMessageResource;
    }
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Address\ToOrderAddress $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Address $quoteAddress,
        $additional = []
    ) {
        /** @var $orderAddress \Magento\Sales\Model\Order\Address */
        $orderAddress = $proceed($quoteAddress, $additional);


        if ($quoteAddress->getEvGiftmessagemessage()) {
            $gift_message_obj = $this->giftMessageFactory->create();

            $gift_message = $quoteAddress->getEvGiftmessagemessage();
            $gift_message .= "+ 3 +";
            $gift_message_obj->setMessage($gift_message);


            $this->giftMessageResource->save($gift_message_obj);



            /* @var $quote \Magento\Quote\Model\Quote */
            $quote = $quoteAddress->getQuote();

            $quote->setEvGiftmessagemessage($quoteAddress->getEvGiftmessagemessage());
            $quote->save();

            //$order->setEvGiftmessagemessage($quoteAddress->getEvGiftmessagemessage());
           //$order->save();

        }







        $orderAddress->setDeliveryDateSelected($quoteAddress->getDeliveryDateSelected());

        $orderAddress->setDeliveryOptionSelected($quoteAddress->getDeliveryOptionSelected());

        $orderAddress->setDeliveryAreaSelected($quoteAddress->getDeliveryAreaSelected());

        $orderAddress->setDeliverySelectedSummarytext($quoteAddress->getDeliverySelectedSummarytext());
        $orderAddress->setDetailedDeliveryInfo($quoteAddress->getDetailedDeliveryInfo());
        $orderAddress->setDetailedDeliveryInfoDates($quoteAddress->getDetailedDeliveryInfoDates());
        $orderAddress->setDetailedDeliveryTeamnumber($quoteAddress->getDetailedDeliveryTeamnumber());

        $orderAddress->setDetailedDeliveryStartTime($quoteAddress->getDetailedDeliveryStartTime());
        $orderAddress->setDetailedDeliveryBeforeTime($quoteAddress->getDetailedDeliveryBeforeTime());
        $orderAddress->setDetailedDeliveryEndTime($quoteAddress->getDetailedDeliveryEndTime());

        $orderAddress->setEvGiftmessagemessage($quoteAddress->getEvGiftmessagemessage());

        return $orderAddress;
    }


}
