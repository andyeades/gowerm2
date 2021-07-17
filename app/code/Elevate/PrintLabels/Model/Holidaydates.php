<?php

namespace Elevate\PrintLabels\Model;

use Elevate\PrintLabels\Api\Data\HolidaydatesInterface;
use Elevate\PrintLabels\Api\Data\HolidaydatesInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Holidaydates extends \Magento\Framework\Model\AbstractModel implements \Elevate\PrintLabels\Api\Data\HolidaydatesInterface
{

    const CACHE_TAG = 'elevate_printlabels_holidaydates';

    protected $holidaydatesDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_printlabels_holidaydates';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param HolidaydatesInterfaceFactory $holidaydatesDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\PrintLabels\Model\ResourceModel\Holidaydates $resource
     * @param \Elevate\PrintLabels\Model\ResourceModel\Holidaydates\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        HolidaydatesInterfaceFactory $holidaydatesDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\PrintLabels\Model\ResourceModel\Holidaydates $resource,
        \Elevate\PrintLabels\Model\ResourceModel\Holidaydates\Collection $resourceCollection,
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
    public function getPrintlabelsholidaydatesId() {
        return $this->_getData(self::PRINTLABELSHOLIDAYDATES_ID);

    }

    /**
     * @param mixed $printlabelsholidaydates_id
     */
    public function setPrintlabelsholidaydatesId($printlabelsholidaydates_id) {
        return $this->setData(self::PRINTLABELSHOLIDAYDATES_ID, $printlabelsholidaydates_id);
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
    public function getPrintlabelsholidaytitle() {
        return $this->_getData(self::PRINTLABELSHOLIDAYTITLE);

    }

    /**
     * @param mixed $printlabelsholidaytitle
     */
    public function setPrintlabelsholidaytitle($printlabelsholidaytitle) {
        return $this->setData(self::PRINTLABELSHOLIDAYTITLE, $printlabelsholidaytitle);
    }


public function getAllData() {
        $this->getData();
    }

    public function getExtensionAttributes() {
        return $this->_getDataData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    public function setExtensionAttributes(
        \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
    ) {
        $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

}
