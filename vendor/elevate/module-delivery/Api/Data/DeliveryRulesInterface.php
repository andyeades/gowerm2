<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYRULES_ID = 'deliveryrules_id';
    const DELIVERYMETHOD_ID = 'deliverymethod_id';
    const DELIVERYAREA_ID = 'deliveryarea_id';
    const DELIVERYRULES_TYPE_ID = 'deliveryrules_type_id';
    const RULE_OPERATOR = 'rule_operator';
    const RULE_QUANTITY = 'rule_quantity';
    const RULE_FUNCTION = 'rule_function';
    const RULE_SORT_VALUE = 'rule_sort_value';
    const RULE_STOP_CONTINUE = 'rule_stop_continue';
    const RULE_ENABLE = 'rule_enable';
    const RULE_VALUE = 'rule_value'; // Value whih will be tested if required
    const RULE_MODE = 'rule_mode';
    const DELIVERYRULESFUNCTION_ID = 'deliveryrulesfunction_id';

    /**
     * Get deliveryrules_id
     *
     * @return string|null
     */
    public function getDeliveryRulesId();

    /**
     * Set deliveryrules_id
     *
     * @param string $deliveryrulesId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     */
    public function setDeliveryrulesId($deliveryrulesId);


    /**
     * @return mixed
     */
    public function getDeliveryareaId();

    /**
     * @param mixed $deliveryarea_id
     */
    public function setDeliveryareaId($deliveryarea_id);


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
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     */
    public function setDeliverymethodId($deliverymethodId);

    /**
     * @return mixed
     */
    public function getDeliveryrulesTypeId();

    /**
     * @param mixed $deliveryrules_type_id
     */
    public function setDeliveryrulesTypeId($deliveryrules_type_id);


    /**
     * @return mixed
     */
    public function getRuleOperator();

    /**
     * @param mixed $rule_operator
     */
    public function setRuleOperator($rule_operator);


    /**
     * @return mixed
     */
    public function getRuleQuantity();


    /**
     * @param mixed $rule_quantity
     */
    public function setRuleQuantity($rule_quantity);


    /**
     * @return mixed
     */
    public function getRuleFunction();

    /**
     * @param mixed $rule_function
     */
    public function setRuleFunction($rule_function);

    /**
     * @return mixed
     */
    public function getRuleSortValue();

    /**
     * @param mixed $rule_sort_value
     */
    public function setRuleSortValue($rule_sort_value);
    /**
     * @return mixed
     */
    public function getRuleEnable();

    /**
     * @return mixed
     */
    public function getRuleValue();
    /**
     * @param mixed $rule_value
     */
    public function setRuleValue($rule_value);


    /**
     * @return mixed
     */
    public function getRuleMode();
    /**
     * @param mixed $rule_mode
     */
    public function setRuleMode($rule_mode);

    /**
     * @param mixed $rule_enable
     */
    public function setRuleEnable($rule_enable);


    public function getDeliveryrulesfunctionId();

    /**
     * @param $deliveryrulesfunctionId
     *
     * @return mixed
     */
    public function setDeliveryrulesfunctionId($deliveryrulesfunctionId);

    /**
     * @return mixed
     */
    public function getAllData();

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesExtensionInterface $extensionAttributes
    );
}
