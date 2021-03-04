<?php


namespace Elevate\Delivery\Model;



use Elevate\Delivery\Api\Data\DeliveryProductsInterface;
use Elevate\Delivery\Api\Data\DeliveryProductsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryProducts extends \Magento\Framework\Model\AbstractModel
{

    protected $deliveryproductsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryproducts';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryProductsInterfaceFactory $deliveryproductsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryProducts $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryProducts\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryProductsInterfaceFactory $deliveryproductsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryProducts $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryProducts\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryproductsDataFactory = $deliveryproductsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryproducts model with deliveryproducts data
     * @return DeliveryProductsInterface
     */
    public function getDataModel()
    {
        $deliveryproductsData = $this->getData();

        $deliveryproductsDataObject = $this->deliveryproductsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryproductsDataObject,
            $deliveryproductsData,
            DeliveryProductsInterface::class
        );

        return $deliveryproductsDataObject;
    }
}
