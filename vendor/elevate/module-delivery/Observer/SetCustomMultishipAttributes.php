<?php
namespace Elevate\Delivery\Observer;

use Magento\Framework\Event\ObserverInterface;

class SetCustomMultishipAttributes implements ObserverInterface
{

    /* @var \Magento\GiftMessage\Model\GiftMessageManager */
    protected $giftMessageManager;

    /* @var \Magento\GiftMessage\Model\MessageFactory */
    protected $giftMessageFactory;

    /* @var \Magento\GiftMessage\Model\ResourceModel\Message $resource */
    protected $giftMessageResource;

    /**
     * Constructor
     *
     */
    public function __construct(
        \Magento\GiftMessage\Model\GiftMessageManager $giftMessageManager,
        \Magento\GiftMessage\Model\MessageFactory $giftMessageFactory,
        \Magento\GiftMessage\Model\ResourceModel\Message $giftMessageResource
    ) {
        $this->giftMessageManager = $giftMessageManager;
        $this->giftMessageFactory = $giftMessageFactory;
        $this->giftMessageResource = $giftMessageResource;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
        $order = $observer->getEvent()->getData('order');
        // Also Hits on multiship
        // Can Add The Data Here to the order I think if required


        $delivery_date_selected = $order->getShippingAddress()->getDeliveryDateSelected();

        //$delivery_option_selected = $order->getShippingAddress()->getDeliveryOptionSelected();
        //$delivery_area_selected = $order->getShippingAddress()->getDeliveryAreaSelected();

        //Parse Date Selected to Remove the Option/Area



        $date_array = explode('_', $delivery_date_selected);

        if (is_array($date_array)) {
            $delivery_date_selected = $date_array[0];
        } else {
            $delivery_date_selected_actual = $delivery_date_selected;
        }




        $order->setDeliveryDateSelected($delivery_date_selected);
        //$order->setDeliveryOptionSelected($delivery_option_selected);
        //$order->setDeliveryAreaSelected($delivery_area_selected);

        $order->save();


        /*

        $gift_message = $quote->getShippingAddress()->getEvGiftmessagemessage();

        if ($gift_message) {
            $gift_message_obj = $this->giftMessageFactory->create();

            $gift_message_obj->setMessage($gift_message);

            $this->giftMessageResource->save($gift_message_obj);

            $gift_message_id = $gift_message_obj->getId();
        }
        */

        return $this;
    }
}
