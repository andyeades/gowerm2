<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryRulesInterface;

class DeliveryRules extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryRulesInterface
{
    /**
     * @return string|null
     */
    public function getDeliveryRulesId() {
        return $this->_get(self::DELIVERYRULES_ID);
    }

    /**
     * @param string $deliveryrulesId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     */
    public function setDeliveryrulesId($deliveryrulesId) {
        return $this->setData(self::DELIVERYRULES_ID, $deliveryrulesId);
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
     * @return string|null
     */
    public function getDeliverymethodId() {
        return $this->_get(self::DELIVERYMETHOD_ID);
    }

    /**
     * @param string $deliverymethodId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     */
    public function setDeliverymethodId($deliverymethodId) {
        return $this->setData(self::DELIVERYMETHOD_ID, $deliverymethodId);
    }

    /**
     * @return mixed
     */
    public function getDeliveryrulesTypeId() {
        return $this->_get(self::DELIVERYRULES_TYPE_ID);
    }

    /**
     * @param mixed $deliveryrules_type_id
     *
     * @return mixed
     */
    public function setDeliveryrulesTypeId($deliveryrules_type_id) {
        return $this->setData(self::DELIVERYRULES_TYPE_ID, $deliveryrules_type_id);
    }

    /**
     * @return mixed
     */
    public function getRuleOperator() {
        return $this->_get(self::RULE_OPERATOR);
    }

    /**
     * @param mixed $rule_operator
     *
     * @return mixed
     */
    public function setRuleOperator($rule_operator) {
        return $this->setData(self::RULE_OPERATOR, $rule_operator);
    }

    /**
     * @return mixed
     */
    public function getRuleQuantity() {
        return $this->_get(self::RULE_QUANTITY);
    }

    /**
     * @param mixed $rule_quantity
     *
     * @return mixed
     */
    public function setRuleQuantity($rule_quantity) {
        return $this->setData(self::RULE_QUANTITY, $rule_quantity);
    }

    /**
     * @return mixed
     */
    public function getRuleFunction() {
        return $this->_get(self::RULE_FUNCTION);
    }

    /**
     * @param mixed $rule_function
     *
     * @return mixed
     */
    public function setRuleFunction($rule_function) {
        return $this->setData(self::RULE_FUNCTION, $rule_function);
    }

    /**
     * @return mixed
     */
    public function getRuleSortValue() {
        return $this->_get(self::RULE_SORT_VALUE);
    }

    /**
     * @param mixed $rule_sort_value
     *
     * @return mixed
     */
    public function setRuleSortValue($rule_sort_value) {
        return $this->setData(self::RULE_SORT_VALUE, $rule_sort_value);
    }

    /**
     * @return mixed
     */
    public function getRuleMode() {
        return $this->_get(self::RULE_MODE);
    }

    /**
     * @param mixed $rule_mode
     *
     * @return mixed
     */
    public function setRuleMode($rule_mode) {
        return $this->setData(self::RULE_MODE, $rule_mode);
    }

    /**
     * @return mixed
     */
    public function getRuleEnable() {
        return $this->_get(self::RULE_ENABLE);
    }

    /**
     * @param mixed $rule_enable
     *
     * @return mixed
     */
    public function setRuleEnable($rule_enable) {
        return $this->setData(self::RULE_ENABLE, $rule_enable);
    }

    /**
     * @return mixed
     */
    public function getRuleValue() {
        return $this->_get(self::RULE_VALUE);
    }

    /**
     * @param mixed $rule_value
     *
     * @return mixed
     */
    public function setRuleValue($rule_value) {
        return $this->setData(self::RULE_VALUE, $rule_value);
    }

    public function getDeliveryrulesfunctionId() {
        return $this->_get(self::DELIVERYRULESFUNCTION_ID);
    }

    public function setDeliveryrulesfunctionId($deliveryrulesfunctionId) {
        return $this->setData(self::DELIVERYRULESFUNCTION_ID, $deliveryrulesfunctionId);
    }

    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
