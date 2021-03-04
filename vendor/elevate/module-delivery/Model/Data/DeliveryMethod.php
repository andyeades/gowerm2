<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryMethodInterface;

class DeliveryMethod extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryMethodInterface
{
    /**
     * Get deliverymethod_id
     * @return string|null
     */
    public function getDeliverymethodId()
    {
        return $this->_get(self::DELIVERYMETHOD_ID);
    }

    /**
     * Set deliverymethod_id
     * @param string $deliverymethodId
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodInterface
     */
    public function setDeliverymethodId($deliverymethodId)
    {
        return $this->setData(self::DELIVERYMETHOD_ID, $deliverymethodId);
    }

    /**
     * @return mixed
     */
    public function getDeliveryareaId() {
        return $this->_get(self::DELIVERYAREA_ID);
    }

    /**
     * @param mixed $deliveryarea_id
     */
    public function setDeliveryareaId($deliveryarea_id) {
        return $this->setData(self::DELIVERYAREA_ID, $deliveryarea_id);
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->_get(self::NAME);
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return mixed
     */
    public function getInternalName() {
        return $this->_get(self::INTERNAL_NAME);
    }

    /**
     * @param mixed $internal_name
     */
    public function setInternalName($internal_name) {
        return $this->setData(self::INTERNAL_NAME, $internal_name);
    }

    /**
     * @return mixed
     */
    public function getDay1() {
        return $this->_get(self::DAY1);
    }

    /**
     * @param mixed $day1
     */
    public function setDay1($day1) {
        return $this->setData(self::DAY1, $day1);
    }

    /**
     * @return mixed
     */
    public function getDay2() {
        return $this->_get(self::DAY2);
    }

    /**
     * @param mixed $day2
     */
    public function setDay2($day2){
        return $this->setData(self::DAY2, $day2);
    }

    /**
     * @return mixed
     */
    public function getDay3() {
        return $this->_get(self::DAY1);
    }

    /**
     * @param mixed $day3
     */
    public function setDay3($day3) {
        return $this->setData(self::DAY3, $day3);
    }

    /**
     * @return mixed
     */
    public function getDay4() {
        return $this->_get(self::DAY4);
    }

    /**
     * @param mixed $day4
     */
    public function setDay4($day4){
        return $this->setData(self::DAY4, $day4);
    }

    /**
     * @return mixed
     */
    public function getDay5() {
        return $this->_get(self::DAY5);
    }

    /**
     * @param mixed $day5
     */
    public function setDay5($day5){
        return $this->setData(self::DAY5, $day5);
    }

    /**
     * @return mixed
     */
    public function getDay6() {
        return $this->_get(self::DAY6);
    }

    /**
     * @param mixed $day6
     */
    public function setDay6($day6){
        return $this->setData(self::DAY6, $day6);
    }

    /**
     * @return mixed
     */
    public function getDay7() {
        return $this->_get(self::DAY7);
    }

    /**
     * @param mixed $day7
     */
    public function setDay7($day7){
        return $this->setData(self::DAY7, $day7);
    }

    /**
     * @return mixed
     */
    public function getEnabled() {
        return $this->_get(self::ENABLED);
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled){
        return $this->setData(self::ENABLED, $enabled);
    }

    /**
     * @return mixed
     */
    public function getStartTime() {
        return $this->_get(self::START_TIME);
    }

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time){
        return $this->setData(self::START_TIME, $start_time);
    }

    /**
     * @return mixed
     */
    public function getEndTime() {
        return $this->_get(self::END_TIME);
    }

    /**
     * @param mixed $end_time
     */
    public function setEndTime($end_time){
        return $this->setData(self::END_TIME, $end_time);
    }

    /**
     * @return mixed
     */
    public function getBeforeTime() {
        return $this->_get(self::BEFORE_TIME);
    }

    /**
     * @param mixed $before_time
     */
    public function setBeforeTime($before_time){
        return $this->setData(self::BEFORE_TIME, $before_time);
    }

    /**
     * @return mixed
     */
    public function getDeliveryteamnumber() {
        return $this->_get(self::DELIVERYTEAMNUMBER);
    }

    /**
     * @param mixed $deliveryteamnumber
     */
    public function setDeliveryteamnumber($deliveryteamnumber){
        return $this->setData(self::DELIVERYTEAMNUMBER, $deliveryteamnumber);
    }

    /**
     * @return mixed
     */
    public function getDeliveryteamability() {
        return $this->_get(self::DELIVERYTEAMABILITY);
    }

    /**
     * @param mixed $deliveryteamability
     */
    public function setDeliveryteamability($deliveryteamability){
        return $this->setData(self::DELIVERYTEAMABILITY, $deliveryteamability);
    }

    /**
     * @return mixed
     */
    public function getDeliveryDesc() {
        return $this->_get(self::DELIVERY_DESC);
    }

    /**
     * @param mixed $delivery_desc
     */
    public function setDeliveryDesc($delivery_desc){
        return $this->setData(self::DELIVERY_DESC, $delivery_desc);
    }

    /**
     * @return mixed
     */
    public function getDeliveryDescCheckout() {
        return $this->_get(self::DELIVERY_DESC_CHECKOUT);
    }

    /**
     * @param mixed $delivery_desc_checkout
     */
    public function setDeliveryDescCheckout($delivery_desc_checkout){
        return $this->setData(self::DELIVERY_DESC_CHECKOUT, $delivery_desc_checkout);
    }

    /**
     * @return mixed
     */
    public function getWorkingdaysMin() {
        return $this->_get(self::WORKINGDAYS_MIN);
    }

    /**
     * @param mixed $workingdays_min
     */
    public function setWorkingdaysMin($workingdays_min){
        return $this->setData(self::WORKINGDAYS_MIN, $workingdays_min);
    }

    /**
     * @return mixed
     */
    public function getWorkingdaysMax() {
        return $this->_get(self::WORKINGDAYS_MAX);
    }

    /**
     * @param mixed $workingdays_max
     */
    public function setWorkingdaysMax($workingdays_max){
        return $this->setData(self::WORKINGDAYS_MAX, $workingdays_max);
    }

    /**
     * @return mixed
     */
    public function getCallDayBefore() {
        return $this->_get(self::CALL_DAY_BEFORE);
    }

    /**
     * @param mixed $call_day_before
     */
    public function setCallDayBefore($call_day_before){
        return $this->setData(self::CALL_DAY_BEFORE, $call_day_before);
    }

    /**
     * @return mixed
     */
    public function getDateRangeSelection() {
        return $this->_get(self::DATE_RANGE_SELECTION);
    }

    /**
     * @param mixed $date_range_selection
     */
    public function setDateRangeSelection($date_range_selection){
        return $this->setData(self::DATE_RANGE_SELECTION, $date_range_selection);
    }

    /**
     * @return mixed
     */
    public function getIsFallbackMethod() {
        return $this->_get(self::IS_FALLBACK_METHOD);
    }

    /**
     * @param mixed $is_fallback_method
     */
    public function setIsFallbackMethod($is_fallback_method){
        return $this->setData(self::IS_FALLBACK_METHOD, $is_fallback_method);
    }

    public function getAllData() {
        return $this->_data;
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryMethodExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryMethodExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}
