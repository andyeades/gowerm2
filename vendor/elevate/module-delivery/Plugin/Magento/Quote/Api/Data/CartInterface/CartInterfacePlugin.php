<?php

namespace Elevate\Delivery\Plugin\Magento\Quote\Api\Data\CartInterfacePlugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderRepositoryPlugin
 */
class CartInterfacePlugin
{
    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Add Our Fields
     *
     * @return OrderInterface
     */
    public function afterGet(CartInterface $subject, OrderInterface $order)
    {

        $billingAddress = $order->getBillingAddress();

        $billingAddressExtensionAttributes = (null !== $billingAddress->getExtensionAttributes())?
            $billingAddress->getExtensionAttributes():
            $this->addressExtensionInterfaceFactory->create();

        $billingAddressExtensionAttributes->setDeliverySelectedSummarytext($billingAddress->getDeliverySelectedSummarytext());
        $billingAddressExtensionAttributes->setDetailedDeliveryInfo($billingAddress->getDetailedDeliveryInfo());
        $billingAddressExtensionAttributes->setDetailedDeliveryInfoDates($billingAddress->getDetailedDeliveryInfoDates());

        $billingAddress->setExtensionAttributes($billingAddressExtensionAttributes);

        if (!$order->getIsVirtual()) {
            $shippingAddress = $order->getShippingAddress();
            $shippingAddressExtensionAttributes = (null!== $shippingAddress->getExtensionAttributes())?
                $shippingAddress->getExtensionAttributes():
                $this->addressExtensionInterfaceFactory->create();


            $shippingAddressExtensionAttributes->setDeliverySelectedSummarytext($shippingAddress->getDeliverySelectedSummarytext());
            $shippingAddressExtensionAttributes->setDetailedDeliveryInfo($shippingAddress->getDetailedDeliveryInfo());
            $shippingAddressExtensionAttributes->setDetailedDeliveryInfoDates($shippingAddress->getDetailedDeliveryInfoDates());
            $shippingAddress->setExtensionAttributes($shippingAddressExtensionAttributes);
        }


        return $order;
    }

}
