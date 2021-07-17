<?php
namespace Elevate\Delivery\Observer;

use Magento\Framework\Event\ObserverInterface;

class SetCustomCheckoutAfterAttributes implements ObserverInterface
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

        // This one Hits on Multishipping Checkout (after the orders are split)

        // HOWEVER the orders are in an array in that case!

        if (!empty(key_exists('orders', $observer->getEvent()->getData()))) {// Multiship Checkout
            $orders = $observer->getEvent()->getData('orders');

            foreach ($orders as $order) {
                // Presume it does it one time?
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

                /* Is this Necessary ? */

                //$gift_message = $quote->getShippingAddress()->getEvGiftmessagemessage();

                /*
                if ($gift_message) {
                    $gift_message_obj = $this->giftMessageFactory->create();

                    $gift_message_obj->setMessage($gift_message);

                    $this->giftMessageResource->save($gift_message_obj);

                    $gift_message_id = $gift_message_obj->getId();

                    $quote->setGiftMessageId($gift_message_id);
                }
                */

                $wordy_date = '';
                if (!empty($date_array)) {
                    $actual_date = $date_array[0];

                    $selected_date = strtotime($actual_date);
                    $selected_date = date("l jS F Y", $selected_date);
                    $wordy_date = $selected_date;
                }

                $detailed_delivery_info_dates = $wordy_date;

                $order->setDetailedDeliveryInfoDates($wordy_date);
                $order->save();
            }

            // Not Currently setting this

            //$detailed_delivery_teamnumber = $quote->getShippingAddress()->getDetailedDeliveryTeamnumber();
            //$detailed_delivery_info_dates = $quote->getShippingAddress()->getDetailedDeliveryInfoDates();
        } else {
            // Normal Checkout

            $detailed_delivery_teamnumber = $quote->getShippingAddress()->getDetailedDeliveryTeamnumber();
            $detailed_delivery_info_dates = $quote->getShippingAddress()->getDetailedDeliveryInfoDates();

            $delivery_date_selected = $quote->getShippingAddress()->getDeliveryDateSelected();
            $delivery_option_selected = $quote->getShippingAddress()->getDeliveryOptionSelected();
            $delivery_area_selected = $quote->getShippingAddress()->getDeliveryAreaSelected();

            $order->setDeliveryDateSelected($delivery_date_selected);
            $order->setDeliveryOptionSelected($delivery_option_selected);
            $order->setDeliveryAreaSelected($delivery_area_selected);

            //$order->setShippingDescription('My Test');

            $order->setDetailedDeliveryInfoDates($detailed_delivery_info_dates);
            $order->setDetailedDeliveryTeamnumber($detailed_delivery_teamnumber);

            /*
             // Not Necessary
            $gift_message = $quote->getShippingAddress()->getEvGiftmessagemessage();

            if ($gift_message) {
                $gift_message_obj = $this->giftMessageFactory->create();
                $gift_message .= ' 22';

                $gift_message_obj->setMessage($gift_message);


                $this->giftMessageResource->save($gift_message_obj);

                $gift_message_id = $gift_message_obj->getId();

                $quote->setGiftMessageId($gift_message_id);
            }
            */
        }

        return $this;
    }
}
