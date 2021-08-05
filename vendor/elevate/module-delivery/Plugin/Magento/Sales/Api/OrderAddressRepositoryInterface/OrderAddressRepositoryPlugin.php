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
    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Add Our Fields
     *
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {



        // TODO ADD THEM LIKE THIS ALL OF THEM!
        $orderComment = $order->getData(self::FIELD_NAME);



        $extensionAttributes = $order->getExtensionAttributes();


        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();


        $extensionAttributes->setOrderComment($orderComment);



        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    /**
     * Add "order_comment" extension attribute to order data object to make it accessible in API data of all order list
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $orderComment = $order->getData(self::FIELD_NAME);
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setOrderComment($orderComment);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}
