<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryAreaInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class DeliveryArea extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryAreaInterface
{

    /**
     * Get deliveryarea_id
     * @return string|null
     */
    public function getDeliveryareaId()
    {
        return $this->_get(self::DELIVERYAREA_ID);
    }

    /**
     * Set deliveryarea_id
     * @param string $deliveryareaId
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaInterface
     */
    public function setDeliveryareaId($deliveryareaId)
    {
        return $this->setData(self::DELIVERYAREA_ID, $deliveryareaId);
    }
    /**
     * @return string
     */
    public function getStoreId() {
        return $this->_get(self::STOREID);
    }
    /**
     * @param string $store_id
     * @return void
     */
    public function setStoreId($store_id) {
        return $this->setData(self::STOREID, $store_id);
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name) {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return int
     */
    public function getEnabled()  {
        return $this->_get(self::ENABLED);
    }

    /**
     * @param int $enabled
     * @return void
     */
    public function setEnabled($enabled) {
        return $this->setData(self::ENABLED, $enabled);
    }

    /**
     * @return int
     */
    public function getPostcodes()  {
        return $this->_get(self::POSTCODES);
    }

    /**
     * @param int $position
     * @return void
     */
    public function setPostcodes($postcodes) {
        return $this->setData(self::POSTCODES, $postcodes);
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface|null
     */


    public function getAllData() {
        $data = $this->_data;
        return $data;
    }


    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryAreaExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
