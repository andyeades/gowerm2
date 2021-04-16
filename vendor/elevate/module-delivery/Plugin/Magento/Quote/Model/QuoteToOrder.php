<?php

namespace Elevate\Delivery\Plugin\Magento\Quote\Model;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderRepositoryPlugin
 */
class QuoteToOrder
{

    /* @var \Magento\GiftMessage\Model\GiftMessageManager */
    protected $giftMessageManager;

    /* @var \Magento\GiftMessage\Model\MessageFactory */
    protected $giftMessageFactory;

    /* @var \Magento\GiftMessage\Model\ResourceModel\Message $resource */
    protected $giftMessageResource;

    public function __construct(
        OrderExtensionFactory $extensionFactory,
        \Magento\GiftMessage\Model\GiftMessageManager $giftMessageManager,
        \Magento\GiftMessage\Model\MessageFactory $giftMessageFactory,
        \Magento\GiftMessage\Model\ResourceModel\Message $giftMessageResource
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->giftMessageManager = $giftMessageManager;
        $this->giftMessageFactory = $giftMessageFactory;
        $this->giftMessageResource = $giftMessageResource;
    }

    /**
     * Add Our Fields
     *
     * @return OrderInterface
     */
    public function after(
        OrderRepositoryInterface $subject,
        OrderInterface $order,
        \Magento\Quote\Model\Quote $quote,
        $additional = []
    ) {

        /** @var $orderAddress \Magento\Sales\Model\Order\Address */

        $orderAddress = $order->getShippingAddress();

        $detailedDeliveryinfoDates = $orderAddress->getDetailedDeliveryInfoDates();
        $detailedDeliveryTeamnumber = $orderAddress->getDetailedDeliveryTeamnumber();

        /*
        $ev_giftmessagemessage = $orderAddress->getEvGiftmessagemessage();

        if ($ev_giftmessagemessage) {
            $gift_message_obj = $this->giftMessageFactory->create();

            $gift_message = $orderAddress->getEvGiftmessagemessage();
            $gift_message_obj->setMessage($gift_message);

            $this->giftMessageResource->save($gift_message_obj);

            $gift_message_id = $gift_message_obj->getId();
            $order->setGiftMessageId($gift_message_id);
        }
        */

        $order->setDeliverySelectedSummarytext($quote->getDeliverySelectedSummarytext());
        $order->setDetailedDeliveryInfo($detailedDeliveryinfoDates);
        $order->setDetailedDeliveryInfoDates($detailedDeliveryinfoDates);
        $order->setDetailedDeliveryTeamnumber($detailedDeliveryTeamnumber);
       //$order->setEvGiftmessagemessage($ev_giftmessagemessage);

        $billingAddress = $order->getBillingAddress();

        $billingAddressExtensionAttributes = (null !== $billingAddress->getExtensionAttributes()) ?
            $billingAddress->getExtensionAttributes() :
            $this->addressExtensionInterfaceFactory->create();

        $billingAddressExtensionAttributes->setDeliverySelectedSummarytext($billingAddress->getDeliverySelectedSummarytext());
        $billingAddressExtensionAttributes->setDetailedDeliveryInfo($billingAddress->getDetailedDeliveryInfo());
        $billingAddressExtensionAttributes->setDetailedDeliveryInfoDates($billingAddress->getDetailedDeliveryInfoDates());

        $billingAddress->setExtensionAttributes($billingAddressExtensionAttributes);

        if (!$order->getIsVirtual()) {
            $shippingAddress = $order->getShippingAddress();

            if (null!== $shippingAddress->getExtensionAttributes()) {
                $shippingAddressExtensionAttributes = $shippingAddress->getExtensionAttributes();
            } else {
                $shippingAddressExtensionAttributes  =   $this->addressExtensionInterfaceFactory->create();
            }

            $shippingAddressExtensionAttributes->setDeliverySelectedSummarytext($shippingAddress->getDeliverySelectedSummarytext());
            $shippingAddressExtensionAttributes->setDetailedDeliveryInfo($shippingAddress->getDetailedDeliveryInfo());
            $shippingAddressExtensionAttributes->setDetailedDeliveryInfoDates($shippingAddress->getDetailedDeliveryInfoDates());
            $shippingAddress->setExtensionAttributes($shippingAddressExtensionAttributes);
        }

        return $order;
    }
}
