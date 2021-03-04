<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryRulesFunctions extends \Magento\Framework\Model\AbstractModel
{

    protected $deliveryrulesfunctionsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryrules_functions';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryRulesFunctionsInterfaceFactory $deliveryrulesfunctionsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryRulesFunctionsInterfaceFactory $deliveryrulesfunctionsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryrulesfunctionsDataFactory = $deliveryrulesfunctionsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryrulesfunctions model with deliveryrulesfunctions data
     * @return DeliveryRulesFunctionsInterface
     */
    public function getDataModel()
    {
        $deliveryrulesfunctionsData = $this->getData();

        $deliveryrulesfunctionsDataObject = $this->deliveryrulesfunctionsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryrulesfunctionsDataObject,
            $deliveryrulesfunctionsData,
            DeliveryRulesFunctionsInterface::class
        );

        return $deliveryrulesfunctionsDataObject;
    }
}
