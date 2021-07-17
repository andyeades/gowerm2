<?php

namespace Elevate\PrintLabels\Model;

use Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface;
use Elevate\PrintLabels\Api\Data\PrintlabelsApiInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class PrintlabelsApi extends \Magento\Framework\Model\AbstractModel implements \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface
{
    const CACHE_TAG = 'elevate_printlabels_printlabels_api';

    protected $printlabelsApiDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_printlabels_printlabels_api';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PrintlabelsApiInterfaceFactory $printlabelsApiDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi $resource
     * @param \Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PrintlabelsApiInterfaceFactory $printlabelsApiDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi $resource,
        \Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi\Collection $resourceCollection,
        array $data = []
    ) {
        $this->printlabelsApiDataFactory = $printlabelsApiDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve printlabelsApi model with printlabelsApi data
     * @return PrintlabelsApiInterface
     */
    public function getDataModel()
    {
        $printlabelsApiData = $this->getData();

        $printlabelsApiDataObject = $this->printlabelsApiDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $printlabelsApiDataObject,
            $printlabelsApiData,
            PrintlabelsApiInterface::class
        );

        return $printlabelsApiDataObject;
    }

    /**
     * @return mixed
     */
    public function getPrintlabelsApiId()
    {
        return $this->_getData(self::PRINTLABELS_API_ID);
    }

    /**
     * @param int $printlabelsApi_id
     */
    public function setPrintlabelsApiId($printlabelsApi_id)
    {
        return $this->setData(self::PRINTLABELS_API_ID, $printlabelsApi_id);
    }

    /**
     * @return mixed
     */
    public function getLastChecked()
    {
        return $this->_getData(self::LAST_CHECKED);
    }

    /**
     * @param mixed $last_checked
     */
    public function setLastChecked($last_checked)
    {
        return $this->setData(self::LAST_CHECKED, $last_checked);
    }

    /**
     * @return mixed
     */
    public function getGeosession()
    {
        return $this->_getData(self::GEOSESSION);
    }

    /**
     * @param mixed $geosession
     */
    public function setGeosession($geosession)
    {
        return $this->setData(self::GEOSESSION, $geosession);
    }

    public function getAllData()
    {
        $this->getData();
    }

    public function getExtensionAttributes()
    {
        return $this->_getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    public function setExtensionAttributes(
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiExtensionInterface $extensionAttributes
    ) {
        $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
