<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryFeeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const DELIVERYFEE_ID = 'deliveryfee_id';
    const DELIVERYAREA_ID = 'deliveryarea_id';
    const DELIVERYMETHOD_ID = 'deliverymethod_id';
    const DAY = 'day';
    const FEE = 'fee';


    /**
     * Get deliveryfee_id
     * @return string|null
     */
    public function getDeliveryfeeId();

    /**
     * Set deliveryfee_id
     * @param string $deliveryfeeId
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeInterface
     */
    public function setDeliveryfeeId($deliveryfeeId);

    /**
     * @return mixed
     */
    public function getDeliveryareaId();

    /**
     * @param $deliveryarea_id
     *
     * @return mixed
     */
    public function setDeliveryareaId($deliveryarea_id);

    /**
     * @return mixed
     */
    public function getDeliverymethodId();

    /**
     * @param mixed $deliverymethod_id
     */
    public function setDeliverymethodId($deliverymethod_id);

    /**
     * @return mixed
     */
    public function getDay();

    /**
     * @param mixed $day
     */
    public function setDay($day);

    /**
     * @return mixed
     */
    public function getFee();

    /**
     * @param mixed $fee
     */
    public function setFee($fee);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryFeeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryFeeExtensionInterface $extensionAttributes
    );
}
