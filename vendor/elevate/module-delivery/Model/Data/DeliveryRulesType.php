<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface;

class DeliveryRulesType extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryRulesTypeInterface
{
    public function getDeliveryrulesTypeId() {
        return $this->_get(self::DELIVERYRULESTYPE_ID);
    }

    public function setDeliveryrulesTypeId($deliveryrulesTypeId) {
        return $this->setData(self::DELIVERYRULESTYPE_ID, $deliveryrulesTypeId);
    }

    public function getRuleType() {
        return $this->_get(self::RULE_TYPE);
    }

    public function setRuleType($rule_type) {
        return $this->setData(self::RULE_TYPE, $rule_type);
    }

    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesTypeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesTypeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
