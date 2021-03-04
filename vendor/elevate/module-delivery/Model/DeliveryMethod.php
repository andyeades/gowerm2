<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryMethodInterface;
use Elevate\Delivery\Api\Data\DeliveryMethodInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryMethod extends \Magento\Framework\Model\AbstractModel
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

    protected $deliverymethodDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliverymethod';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryMethodInterfaceFactory $deliverymethodDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryMethod $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryMethod\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryMethodInterfaceFactory $deliverymethodDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryMethod $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryMethod\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliverymethodDataFactory = $deliverymethodDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliverymethod model with deliverymethod data
     * @return DeliveryMethodInterface
     */
    public function getDataModel()
    {
        $deliverymethodData = $this->getData();
        
        $deliverymethodDataObject = $this->deliverymethodDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliverymethodDataObject,
            $deliverymethodData,
            DeliveryMethodInterface::class
        );
        
        return $deliverymethodDataObject;
    }

}
