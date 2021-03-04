<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryMethodInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYMETHOD_ID = 'deliverymethod_id';
    const DELIVERYAREA_ID = 'deliveryarea_id';
    const NAME = 'name';
    const INTERNAL_NAME = 'internal_name';
    const DAY1 = 'day1';
    const DAY2 = 'day2';
    const DAY3 = 'day3';
    const DAY4 = 'day4';
    const DAY5 = 'day5';
    const DAY6 = 'day6';
    const DAY7 = 'day7';
    const ENABLED = 'enabled';
    const START_TIME = 'start_time';
    const END_TIME = 'end_time';
    const BEFORE_TIME = 'before_time';
    const DELIVERYTEAMNUMBER = 'delivery_team_number';
    const DELIVERYTEAMABILITY = 'delivery_team_ability';
    const DELIVERY_DESC = 'delivery_desc';
    const DELIVERY_DESC_CHECKOUT = 'delivery_desc_checkout';
    const WORKINGDAYS_MIN = 'workingdays_min';
    const WORKINGDAYS_MAX = 'workingdays_max';
    const CALL_DAY_BEFORE = 'call_day_before';
    const DATE_RANGE_SELECTION = 'date_range_selection';
    const IS_FALLBACK_METHOD = 'is_fallback_method';

    /**
     * Get deliverymethod_id
     *
     * @return string|null
     */
    public function getDeliverymethodId();

    /**
     * Set deliverymethod_id
     *
     * @param string $deliverymethodId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodInterface
     */
    public function setDeliverymethodId($deliverymethodId);

    /**
     * @return mixed
     */
    public function getDeliveryareaId();

    /**
     * @param mixed $deliveryarea_id
     */
    public function setDeliveryareaId($deliveryarea_id);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param mixed $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getInternalName();

    /**
     * @param mixed $internal_name
     */
    public function setInternalName($internal_name);

    /**
     * @return mixed
     */
    public function getDay1();

    /**
     * @param mixed $day1
     */
    public function setDay1($day1);

    /**
     * @return mixed
     */
    public function getDay2();

    /**
     * @param mixed $day2
     */
    public function setDay2($day2);

    /**
     * @return mixed
     */
    public function getDay3();

    /**
     * @param mixed $day3
     */
    public function setDay3($day3);

    /**
     * @return mixed
     */
    public function getDay4();

    /**
     * @param mixed $day4
     */
    public function setDay4($day4);

    /**
     * @return mixed
     */
    public function getDay5();

    /**
     * @param mixed $day5
     */
    public function setDay5($day5);

    /**
     * @return mixed
     */
    public function getDay6();

    /**
     * @param mixed $day6
     */
    public function setDay6($day6);

    /**
     * @return mixed
     */
    public function getDay7();

    /**
     * @param mixed $day7
     */
    public function setDay7($day7);

    /**
     * @return mixed
     */
    public function getEnabled();

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return mixed
     */
    public function getStartTime();

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time);

    /**
     * @return mixed
     */
    public function getEndTime();

    /**
     * @param mixed $end_time
     */
    public function setEndTime($end_time);

    /**
     * @return mixed
     */
    public function getBeforeTime();

    /**
     * @param mixed $before_time
     */
    public function setBeforeTime($before_time);

    /**
     * @return mixed
     */
    public function getDeliveryteamnumber();

    /**
     * @param mixed $deliveryteamnumber
     */
    public function setDeliveryteamnumber($deliveryteamnumber);

    /**
     * @return mixed
     */
    public function getDeliveryteamability();

    /**
     * @param mixed $deliveryteamability
     */
    public function setDeliveryteamability($deliveryteamability);

    /**
     * @return mixed
     */
    public function getDeliveryDesc();

    /**
     * @param mixed $delivery_desc
     */
    public function setDeliveryDesc($delivery_desc);

    /**
     * @return mixed
     */
    public function getDeliveryDescCheckout();

    /**
     * @param mixed $delivery_desc_checkout
     */
    public function setDeliveryDescCheckout($delivery_desc_checkout);

    /**
     * @return mixed
     */
    public function getWorkingdaysMin();

    /**
     * @param mixed $workingdays_min
     */
    public function setWorkingdaysMin($workingdays_min);

    /**
     * @return mixed
     */
    public function getWorkingdaysMax();

    /**
     * @param mixed $workingdays_max
     */
    public function setWorkingdaysMax($workingdays_max);

    /**
     * @return mixed
     */
    public function getCallDayBefore();

    /**
     * @param mixed $call_day_before
     */
    public function setCallDayBefore($call_day_before);

    /**
     * @return mixed
     */
    public function getDateRangeSelection();

    /**
     * @param mixed $date_range_selection
     */
    public function setDateRangeSelection($date_range_selection);

    /**
     * @return mixed
     */
    public function getIsFallbackMethod();

    /**
     * @param mixed $is_fallback_method
     */
    public function setIsFallbackMethod($is_fallback_method);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryMethodExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryMethodExtensionInterface $extensionAttributes
    );
}
