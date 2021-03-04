<?php

namespace Elevate\Delivery\Api\Data;

interface HolidaydatesInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const DELIVERYHOLIDAYDATES_ID = 'deliveryholidaydates_id';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const DELIVERYHOLIDAYTITLE = 'deliveryholidaytitle';
    const EXTENSION_ATTRIBUTES = '';

    /**
     * Get deliveryholidaydates_id
     * @return string|null
     */
    public function getDeliveryholidaydatesId();

    /**
     * Set deliveryholidaydates_id
     * @param string $deliveryholidaydatesId
     * @return \Elevate\Delivery\Api\Data\HolidaydatesInterface
     */
    public function setDeliveryholidaydatesId($deliveryholidaydatesId);

    /**
     * @return mixed
     */
    public function getStartDate();


    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date);

    /**
     * @return mixed
     */
    public function getEndDate();

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date);

    /**
     * @return mixed
     */
    public function getDeliveryholidaytitle();

    /**
     * @param mixed $deliveryholidaytitle
     */
    public function setDeliveryholidaytitle($deliveryholidaytitle);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\HolidaydatesExtensionInterface $extensionAttributes
    );

}
