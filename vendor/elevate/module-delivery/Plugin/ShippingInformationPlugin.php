<?php

namespace Elevate\Delivery\Plugin;

class ShippingInformationPlugin
{
    protected $quoteRepository;

    protected $cartInterface;

    protected $cartRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cartRepository = $cartRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {

        // Ok This works but needs to be data from front end/etc


        //$cart->setDetailedDeliveryInfo($cart_ext_attributes->getDetailedDeliveryInfo());
        // data-methodrangedates
        //$cart->setDetailedDeliveryInfoDates($cart_ext_attributes->getDetailedDeliveryInfoDates());
        //$cart->setDetailedDeliveryTeamnumber($cart_ext_attributes->getDetailedDeliveryTeamnumber());



        $shippingAddress = $addressInformation->getShippingAddress();
        $shippingAddressExtensionAttributes = $shippingAddress->getExtensionAttributes();

        if ($shippingAddressExtensionAttributes) {
            $delivery_date_selected = $shippingAddressExtensionAttributes->getDeliveryDateSelected();
            $shippingAddress->setDeliveryDateSelected($delivery_date_selected);

            $delivery_option_selected = $shippingAddressExtensionAttributes->getDeliveryOptionSelected();
            $shippingAddress->setDeliveryOptionSelected($delivery_option_selected);

            $delivery_area_selected = $shippingAddressExtensionAttributes->getDeliveryAreaSelected();
            $shippingAddress->setDeliveryAreaSelected($delivery_area_selected);

            $delivery_selected_summarytext = $shippingAddressExtensionAttributes->getDeliverySelectedSummarytext();
            $shippingAddress->setDeliverySelectedSummarytext($delivery_selected_summarytext);

            $detailed_delivery_info_dates = $shippingAddressExtensionAttributes->getDetailedDeliveryInfoDates();
            $shippingAddress->setDetailedDeliveryInfoDates($detailed_delivery_info_dates);

            $detailed_delivery_info = $shippingAddressExtensionAttributes->getDetailedDeliveryInfo();
            $shippingAddress->setDetailedDeliveryInfo($detailed_delivery_info);

            $detailed_delivery_start_time = $shippingAddressExtensionAttributes->getDetailedDeliveryStartTime();
            $shippingAddress->setDetailedDeliveryStartTime($detailed_delivery_start_time);

            $detailed_delivery_end_time = $shippingAddressExtensionAttributes->getDetailedDeliveryEndTime();
            $shippingAddress->setDetailedDeliveryEndTime($detailed_delivery_end_time);

            $detailed_delivery_before_time = $shippingAddressExtensionAttributes->getDetailedDeliveryBeforeTime();
            $shippingAddress->setDetailedDeliveryBeforeTime($detailed_delivery_before_time);

            $detailed_delivery_teamnumber = $shippingAddressExtensionAttributes->getDetailedDeliveryTeamnumber();
            $shippingAddress->setDetailedDeliveryTeamnumber($detailed_delivery_teamnumber);

            $ev_giftmessagemessage = $shippingAddressExtensionAttributes->getEvGiftmessagemessage();
            $shippingAddress->setEvGiftmessagemessage($ev_giftmessagemessage);
        }
    }
}
