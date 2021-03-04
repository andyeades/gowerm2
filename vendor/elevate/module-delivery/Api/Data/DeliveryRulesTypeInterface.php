<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesTypeInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYRULESTYPE_ID = 'deliveryrules_type_id';
    const RULE_TYPE = 'rule_type';

    /**
     * Get deliveryrulesType_id
     *
     * @return string|null
     */
    public function getDeliveryrulesTypeId();

    /**
     * Set deliveryrulestype_id
     *
     * @param string $deliveryruletypeId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface
     */
    public function setDeliveryrulesTypeId($deliveryrulesTypeId);

    /**
     * @return mixed
     */
    public function getRuleType();

    /**
     * @param mixed $rule_type
     */
    public function setRuleType($rule_type);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesTypeExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesTypeExtensionInterface $extensionAttributes
    );
}
