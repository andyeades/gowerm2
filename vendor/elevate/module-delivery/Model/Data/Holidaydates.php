<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\HolidaydatesInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class Holidaydates extends \Magento\Framework\Api\AbstractExtensibleObject implements HolidaydatesInterface
{


    /**
     * @return mixed
     */
    public function getDeliveryholidaydatesId() {
        return $this->_get(self::DELIVERYHOLIDAYDATES_ID);
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
        return $this->_get(self::START_DATE);

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
        return $this->_get(self::END_DATE);

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
        return $this->_get(self::DELIVERYHOLIDAYTITLE);

    }

    /**
     * @param mixed $deliveryholidaytitle
     */
    public function setDeliveryholidaytitle($deliveryholidaytitle) {
        return $this->setData(self::DELIVERYHOLIDAYTITLE, $deliveryholidaytitle);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface|null
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
     * @param \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
