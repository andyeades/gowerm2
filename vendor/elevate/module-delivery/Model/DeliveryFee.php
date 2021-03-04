<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryFeeInterface;
use Elevate\Delivery\Api\Data\DeliveryFeeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryFee extends \Magento\Framework\Model\AbstractModel
{
    protected $deliveryfeeDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryfee';

    const CACHE_TAG = 'elevate_delivery_deliveryfee';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryFeeInterfaceFactory $deliveryfeeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryFee $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryFee\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryFeeInterfaceFactory $deliveryfeeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryFee $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryFee\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryfeeDataFactory = $deliveryfeeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryfee model with deliveryfee data
     * @return DeliveryFeeInterface
     */
    public function getDataModel()
    {
        $deliveryfeeData = $this->getData();
        
        $deliveryfeeDataObject = $this->deliveryfeeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryfeeDataObject,
            $deliveryfeeData,
            DeliveryFeeInterface::class
        );
        
        return $deliveryfeeDataObject;
    }
}
