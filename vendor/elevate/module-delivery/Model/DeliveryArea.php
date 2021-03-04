<?php

namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryAreaInterface;
use Elevate\Delivery\Api\Data\DeliveryAreaInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class DeliveryArea extends \Magento\Framework\Model\AbstractModel implements \Elevate\Delivery\Api\Data\DeliveryAreaInterface
{

    const CACHE_TAG = 'elevate_delivery_deliveryarea';

    protected $deliveryareaDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'elevate_delivery_deliveryarea';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param DeliveryAreaInterfaceFactory $deliveryareaDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryArea $resource
     * @param \Elevate\Delivery\Model\ResourceModel\DeliveryArea\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        DeliveryAreaInterfaceFactory $deliveryareaDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Elevate\Delivery\Model\ResourceModel\DeliveryArea $resource,
        \Elevate\Delivery\Model\ResourceModel\DeliveryArea\Collection $resourceCollection,
        array $data = []
    ) {
        $this->deliveryareaDataFactory = $deliveryareaDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve deliveryarea model with deliveryarea data
     * @return DeliveryAreaInterface
     */
    public function getDataModel()
    {
        $deliveryareaData = $this->getData();
        
        $deliveryareaDataObject = $this->deliveryareaDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $deliveryareaDataObject,
            $deliveryareaData,
            DeliveryAreaInterface::class
        );
        
        return $deliveryareaDataObject;
    }

    public function getDeliveryareaId() {
        return $this->_getData(self::DELIVERYAREAID);
    }

    public function setDeliveryareaId($deliveryarea_id) {
        $this->setData(self::DELIVERYAREAID, $deliveryarea_id);
    }

    public function getStoreId() {
        return $this->_getData(self::STOREID);
    }

    public function setStoreId($store_id) {
        $this->setData(self::STOREID, $store_id);
    }

    public function getName() {
        return $this->_getData(self::NAME);
    }

    public function setName($name) {
        $this->setData(self::NAME, $name);
    }

    public function getEnabled() {
        return $this->_getData(self::ENABLED);
    }

    public function setEnabled($enabled) {
        $this->setData(self::ENABLED, $enabled);
    }

    public function getPostcodes() {
        return $this->_getData(self::POSTCODES);
    }

    public function setPostcodes($postcodes) {
        $this->setData(self::POSTCODES, $postcodes);
    }

    public function getAllData() {
        $this->getData();
    }

    public function getExtensionAttributes() {
        return $this->_getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface $extensionAttributes
    ) {
        $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

}
