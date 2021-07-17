<?php


namespace Elevate\PrintLabels\Model\Data;

use Elevate\PrintLabels\Api\Data\HolidaydatesInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class Holidaydates extends \Magento\Framework\Api\AbstractExtensibleObject implements HolidaydatesInterface
{


    /**
     * @return mixed
     */
    public function getPrintlabelsholidaydatesId() {
        return $this->_get(self::PRINTLABELSHOLIDAYDATES_ID);
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
    public function getPrintlabelsholidaytitle() {
        return $this->_get(self::PRINTLABELSHOLIDAYTITLE);

    }

    /**
     * @param mixed $printlabelsholidaytitle
     */
    public function setPrintlabelsholidaytitle($printlabelsholidaytitle) {
        return $this->setData(self::PRINTLABELSHOLIDAYTITLE, $printlabelsholidaytitle);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface|null
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
     * @param \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\PrintLabels\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
