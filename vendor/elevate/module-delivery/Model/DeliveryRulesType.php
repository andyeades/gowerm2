<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesTypeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryRulesType extends \Magento\Framework\Model\AbstractModel
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

    protected $deliveryrulestypeDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryrulestype';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryRulesTypeInterfaceFactory $deliveryrulestypeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesType $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryRulesType\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryRulesTypeInterfaceFactory $deliveryrulestypeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesType $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryRulesType\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryrulestypeDataFactory = $deliveryrulestypeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryrulestype model with deliveryrulestype data
     * @return DeliveryRulesTypeInterface
     */
    public function getDataModel()
    {
        $deliveryrulestypeData = $this->getData();

        $deliveryrulestypeDataObject = $this->deliveryrulestypeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryrulestypeDataObject,
            $deliveryrulestypeData,
            DeliveryRulesTypeInterface::class
        );

        return $deliveryrulestypeDataObject;
    }
}
