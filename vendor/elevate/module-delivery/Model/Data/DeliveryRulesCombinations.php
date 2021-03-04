<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface;

class DeliveryRulesCombinations extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryRulesCombinationsInterface
{

    /**
     * @return mixed
     */
    public function getDeliveryRulesCombinationsId() {
        return $this->_get(self::DELIVERYRULESCOMBINATIONS_ID);
    }

    /**
     * @param string $deliveryrulescombinations_id
     *
     * @return mixed
     */
    public function setDeliveryRulesCombinationsId($deliveryrulescombinations_id) {
        return $this->setData(self::DELIVERYRULESCOMBINATIONS_ID, $deliveryrulescombinations_id);
    }

    /**
     * @return mixed
     */
    public function getDeliveryrulesIds() {
        return $this->_get(self::DELIVERYRULES_IDS);
    }

    /**
     * @param string $deliveryrules_ids
     *
     * @return mixed
     */
    public function setDeliveryrulesIds($deliveryrules_ids) {
        return $this->setData(self::DELIVERYRULES_IDS, $deliveryrules_ids);
    }

    public function getDeliveryrulesfunctionId() {
        return $this->_get(self::DELIVERYRULESFUNCTION_ID);
    }

    public function setDeliveryrulesfunctionId($deliveryrulesfunctionId) {
        return $this->setData(self::DELIVERYRULESFUNCTION_ID, $deliveryrulesfunctionId);
    }

    /**
     * @return mixed
     */
    public function getComboAreaId() {
        return $this->_get(self::COMBO_AREA_ID);
    }

    /**
     * @param mixed $combo_area_id
     *
     * @return mixed
     */
    public function setComboAreaId($combo_area_id) {
        return $this->setData(self::COMBO_AREA_ID, $combo_area_id);
    }

    /**
     * @return mixed
     */
    public function getComboRuleFunction() {
        return $this->_get(self::COMBO_RULE_FUNCTION);
    }

    /**
     * @param mixed $combo_rule_function
     *
     * @return mixed
     */
    public function setComboRuleFunction($combo_rule_function) {
        return $this->setData(self::COMBO_RULE_FUNCTION, $combo_rule_function);
    }

    /**
     * @return mixed
     */
    public function getComboRuleSortValue() {
        return $this->_get(self::COMBO_RULE_SORT_VALUE);
    }

    /**
     * @param mixed $combo_rule_sort_value
     *
     * @return mixed
     */
    public function setComboRuleSortValue($combo_rule_sort_value) {
        return $this->setData(self::COMBO_RULE_SORT_VALUE, $combo_rule_sort_value);
    }

    /**
     * @return mixed
     */
    public function getComboRuleStopContinue() {
        return $this->_get(self::COMBO_RULE_STOP_CONTINUE);
    }

    /**
     * @param mixed $combo_rule_stop_continue
     *
     * @return mixed
     */
    public function setComboRuleStopContinue($combo_rule_stop_continue){
        return $this->setData(self::COMBO_RULE_STOP_CONTINUE, $combo_rule_stop_continue);
    }

    /**
     * @return mixed
     */
    public function getComboRuleEnable() {
        return $this->_get(self::COMBO_RULE_ENABLE);
    }

    /**
     * @param mixed $combo_rule_enable
     *
     * @return mixed
     */
    public function setComboRuleEnable($combo_rule_enable) {
        return $this->setData(self::COMBO_RULE_ENABLE, $combo_rule_enable);
    }

    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
