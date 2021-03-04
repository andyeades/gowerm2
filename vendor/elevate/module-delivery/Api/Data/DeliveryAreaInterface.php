<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryAreaInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const DELIVERYAREA_ID = 'deliveryarea_id';
    const STOREID = 'store_id';
    const NAME = 'name';
    const ENABLED = 'enabled';
    const POSTCODES = 'postcodes';
    const EXTENSION_ATTRIBUTES = '';

    /**
     * Get deliveryarea_id
     * @return string|null
     */
    public function getDeliveryareaId();

    /**
     * Set deliveryarea_id
     * @param string $deliveryareaId
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaInterface
     */
    public function setDeliveryareaId($deliveryareaId);

    /**
     * @return string
     */
    public function getStoreId();

    /**
     * @param string $store_id
     * @return void
     */
    public function setStoreId($store_id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getEnabled();

    /**
     * @param int $enabled
     * @return void
     */
    public function setEnabled($enabled);

    /**
     * @return int
     */
    public function getPostcodes();

    /**
     * @param int $position
     * @return void
     */
    public function setPostcodes($postcodes);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface $extensionAttributes
    );

}
