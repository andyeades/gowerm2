<?php

namespace Elevate\Delivery\Plugin\Magento\Sales\Api\OrderRepositoryInterface;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderRepositoryPlugin
 */
class OrderRepositoryPlugin
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
    public function __construct(
        OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }


    /**
     * Add extension attribute to order data object to make it accessible in API data of all order list
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $detailed_delivery_info_dates = $order->getData('detailed_delivery_info_dates');
            $detailed_delivery_teamnumber = $order->getData('detailed_delivery_teamnumber');
            $delivery_selected_summarytext = $order->getData('delivery_selected_summarytext');
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

            $extensionAttributes->setDetailedDeliveryInfoDates($detailed_delivery_info_dates);
            $extensionAttributes->setDetailedDeliveryTeamnumber($detailed_delivery_teamnumber);
            $extensionAttributes->setDeliverySelectedSummarytext($delivery_selected_summarytext);

            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
    /**
     * Add Our Fields
     *
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {

        $detailed_delivery_info_dates = $order->getData('detailed_delivery_info_dates');
        $detailed_delivery_teamnumber = $order->getData('detailed_delivery_teamnumber');
        $delivery_selected_summarytext = $order->getData('delivery_selected_summarytext');
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

        $extensionAttributes->setDetailedDeliveryInfoDates($detailed_delivery_info_dates);
        $extensionAttributes->setDetailedDeliveryTeamnumber($detailed_delivery_teamnumber);
        $extensionAttributes->setDeliverySelectedSummarytext($delivery_selected_summarytext);
        $order->setExtensionAttributes($extensionAttributes);


        return $order;
    }

}
