<?php

namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\HolidaydatesInterface;
use Elevate\Delivery\Api\Data\HolidaydatesInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Holidaydates extends \Magento\Framework\Model\AbstractModel implements \Elevate\Delivery\Api\Data\HolidaydatesInterface
{

    const CACHE_TAG = 'elevate_delivery_holidaydates';

    protected $holidaydatesDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_holidaydates';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param HolidaydatesInterfaceFactory $holidaydatesDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\Holidaydates $resource
     * @param \Elevate\Delivery\Model\ResourceModel\Holidaydates\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        HolidaydatesInterfaceFactory $holidaydatesDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\Holidaydates $resource,
        \Elevate\Delivery\Model\ResourceModel\Holidaydates\Collection $resourceCollection,
        array $data = []
    ) {
        $this->holidaydatesDataFactory = $holidaydatesDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve holidaydates model with holidaydates data
     * @return HolidaydatesInterface
     */
    public function getDataModel()
    {
        $holidaydatesData = $this->getData();

        $holidaydatesDataObject = $this->holidaydatesDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $holidaydatesDataObject,
            $holidaydatesData,
            HolidaydatesInterface::class
        );

        return $holidaydatesDataObject;
    }

    /**
     * @return mixed
     */
    public function getDeliveryholidaydatesId() {
        return $this->_getData(self::DELIVERYHOLIDAYDATES_ID);

    }

    /**
     * @param mixed $deliveryholidaydates_id
     */
    public function setDeliveryholidaydatesId($deliveryholidaydates_id) {
        return $this->setData(self::DELIVERYHOLIDAYDATES_ID, $deliveryholidaydates_id);
    }

    /**
     * @return mixed
     */
    public function getStartDate() {
        return $this->_getData(self::START_DATE);

    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date) {
        return $this->setData(self::START_DATE, $start_date);
    }

    /**
     * @return mixed
     */
    public function getEndDate() {
        return $this->_getData(self::END_DATE);

    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date) {
        return $this->setData(self::END_DATE, $end_date);
    }

    /**
     * @return mixed
     */
    public function getDeliveryholidaytitle() {
        return $this->_getData(self::DELIVERYHOLIDAYTITLE);

    }

    /**
     * @param mixed $deliveryholidaytitle
     */
    public function setDeliveryholidaytitle($deliveryholidaytitle) {
        return $this->setData(self::DELIVERYHOLIDAYTITLE, $deliveryholidaytitle);
    }


public function getAllData() {
        $this->getData();
    }

    public function getExtensionAttributes() {
        return $this->_getDataData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
    ) {
        $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

}
