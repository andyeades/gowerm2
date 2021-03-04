<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryRulesInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryRules extends \Magento\Framework\Model\AbstractModel
{


    protected $name;
    protected $internal_name;
    protected $day1;
    protected $day2;
    protected $day3;
    protected $day4;
    protected $day5;
    protected $day6;
    protected $day7;
    protected $enabled;
    protected $start_time;
    protected $end_time;
    protected $before_time;
    protected $deliveryteamnumber;
    protected $deliveryteamability;
    protected $delivery_desc;
    protected $delivery_desc_checkout;
    protected $workingdays_min;
    protected $workingdays_max;
    protected $call_day_before;
    protected $date_range_selection;

    protected $deliveryrulesDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryrules';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryRulesInterfaceFactory $deliveryrulesDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRules $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRules\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryRulesInterfaceFactory $deliveryrulesDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRules $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRules\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryrulesDataFactory = $deliveryrulesDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryrules model with deliveryrules data
     * @return DeliveryRulesInterface
     */
    public function getDataModel()
    {
        $deliveryrulesData = $this->getData();

        $deliveryrulesDataObject = $this->deliveryrulesDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryrulesDataObject,
            $deliveryrulesData,
            DeliveryRulesInterface::class
        );

        return $deliveryrulesDataObject;
    }
}
