<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryRulesCombinations extends \Magento\Framework\Model\AbstractModel
{


    protected $deliveryrulescombinationsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryrulescombinations';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryRulesCombinationsInterfaceFactory $deliveryrulescombinationsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryRulesCombinationsInterfaceFactory $deliveryrulescombinationsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryrulescombinationsDataFactory = $deliveryrulescombinationsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryrulescombinations model with deliveryrulescombinations data
     * @return DeliveryRulesCombinationInterface
     */
    public function getDataModel()
    {
        $deliveryrulescombinationsData = $this->getData();

        $deliveryrulescombinationsDataObject = $this->deliveryrulescombinationsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryrulescombinationsDataObject,
            $deliveryrulescombinationsData,
            DeliveryRulesCombinationsInterface::class
        );

        return $deliveryrulescombinationsDataObject;
    }
}
