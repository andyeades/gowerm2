<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryFeeInterface;

class DeliveryFee extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryFeeInterface
{

    /**
     * Get deliveryfee_id
     * @return string|null
     */
    public function getDeliveryfeeId()
    {
        return $this->_get(self::DELIVERYFEE_ID);
    }

    /**
     * Set deliveryfee_id
     * @param string $deliveryfeeId
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeInterface
     */
    public function setDeliveryfeeId($deliveryfeeId)
    {
        return $this->setData(self::DELIVERYFEE_ID, $deliveryfeeId);
    }

    /**
     * @return mixed
     */
    public function getDeliveryareaId() {
        return $this->_get(self::DELIVERYAREA_ID);
    }

    /**
     * @param $deliveryarea_id
     *
     * @return DeliveryFee|mixed
     */
    public function setDeliveryareaId($deliveryarea_id) {
        return $this->setData(self::DELIVERYAREA_ID, $deliveryarea_id);
    }

    /**
     * @return mixed
     */
    public function getDeliverymethodId() {
        return $this->_get(self::DELIVERYMETHOD_ID);
    }

    /**
     * @param mixed $deliverymethod_id
     */
    public function setDeliverymethodId($deliverymethod_id) {
        return $this->setData(self::DELIVERYMETHOD_ID, $deliverymethod_id);
    }

    /**
     * @return mixed
     */
    public function getDay() {
        return $this->_get(self::DAY);
    }

    /**
     * @param mixed $day
     */
    public function setDay($day) {
        return $this->setData(self::DAY, $day);
    }

    /**
     * @return mixed
     */
    public function getFee() {
        return $this->_get(self::FEE);
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee) {
        return $this->setData(self::FEE, $fee);
    }
    public function getAllData() {
        return $this->_data;
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryFeeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryFeeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
