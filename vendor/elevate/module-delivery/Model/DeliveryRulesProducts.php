<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesProductsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryRulesProducts extends \Magento\Framework\Model\AbstractModel
{

    protected $deliveryrulesproductsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryrules_products';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryRulesProductsInterfaceFactory $deliveryrulesproductsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesProducts $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesProducts\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryRulesProductsInterfaceFactory $deliveryrulesproductsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesProducts $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesProducts\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryrulesproductsDataFactory = $deliveryrulesproductsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryrulesproducts model with deliveryrulesproducts data
     * @return DeliveryRulesProductsInterface
     */
    public function getDataModel()
    {
        $deliveryrulesproductsData = $this->getData();

        $deliveryrulesproductsDataObject = $this->deliveryrulesproductsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryrulesproductsDataObject,
            $deliveryrulesproductsData,
            DeliveryRulesProductsInterface::class
        );

        return $deliveryrulesproductsDataObject;
    }
}
