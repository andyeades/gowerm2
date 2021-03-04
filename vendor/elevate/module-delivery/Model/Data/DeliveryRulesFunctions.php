<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface;

class DeliveryRulesFunctions extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryRulesFunctionsInterface
{

    /**
     * Get deliveryrulesfunction_id
     * @return string|null
     */
    public function getDeliveryrulesFunctionId()
    {
        return $this->_get(self::DELIVERYRULESFUNCTION_ID);
    }

    /**
     * Set deliveryrulesfunction_id
     * @param string $deliveryrulesfunctionId
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface
     */
    public function setDeliveryrulesFunctionId($deliveryrulesfunctionId)
    {
        return $this->setData(self::DELIVERYRULESFUNCTION_ID, $deliveryrulesfunctionId);
    }
    /**
     * @return string
     */
    public function getDeliveryrulesFunction() {
        return $this->_get(self::DELIVERYRULES_FUNCTION);
    }
    /**
     * @param string $deliveryrulesFunction
     * @return void
     */
    public function setDeliveryrulesFunction($deliveryrulesFunction) {
        return $this->setData(self::DELIVERYRULES_FUNCTION, $deliveryrulesFunction);
    }

    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
