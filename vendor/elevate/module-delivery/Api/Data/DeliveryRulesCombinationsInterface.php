<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesCombinationsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYRULESCOMBINATIONS_ID = 'deliveryrulescombinations_id';
    const DELIVERYRULES_IDS = 'deliveryrules_ids';
    const DELIVERYRULESFUNCTION_ID = 'deliveryrulesfunction_id';
    const COMBO_AREA_ID = 'combo_area_id';
    const COMBO_RULE_FUNCTION = 'combo_rule_function';
    const COMBO_RULE_SORT_VALUE = 'combo_rule_sort_value';
    const COMBO_RULE_STOP_CONTINUE = 'combo_rule_stop_continue';
    const COMBO_RULE_ENABLE = 'combo_rule_enable';




    /**
     * Get deliveryrulescombinations_id
     *
     * @return string|null
     */
    public function getDeliveryRulesCombinationsId();

    /**
     * Set deliveryrulescombinations_id
     *
     * @param string $deliveryrulescombinations_id
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface
     */
    public function setDeliveryRulesCombinationsId($deliveryrulescombinations_id);

    /**
     * Get deliveryrules_ids
     *
     * @return string|null
     */
    public function getDeliveryRulesIds();

    /**
     * Set deliveryrules_ids
     *
     * @param string $deliveryrules_ids
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface
     */
    public function setDeliveryRulesIds($deliveryrules_ids);


    /**
     * @return mixed
     */
    public function getComboAreaId();

    /**
     * @param mixed $combo_area_id
     */
    public function setComboAreaId($combo_area_id);

    /**
     * @return mixed
     */
    public function getComboRuleFunction();

    /**
     * @param mixed $combo_rule_function
     */
    public function setComboRuleFunction($combo_rule_function);

    /**
     * @return mixed
     */
    public function getComboRuleSortValue();

    /**
     * @param mixed $combo_rule_sort_value
     */
    public function setComboRuleSortValue($combo_rule_sort_value);

    /**
     * @return mixed
     */
    public function getComboRuleStopContinue();

    /**
     * @param mixed $combo_rule_stop_continue
     */
    public function setComboRuleStopContinue($combo_rule_stop_continue);
    /**
     * @return mixed
     */
    public function getComboRuleEnable();

    /**
     * @param mixed $combo_rule_enable
     */
    public function setComboRuleEnable($combo_rule_enable);



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
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsExtensionInterface $extensionAttributes
    );
}
