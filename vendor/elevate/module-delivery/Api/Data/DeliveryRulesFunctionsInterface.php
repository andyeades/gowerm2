<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesFunctionsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYRULESFUNCTION_ID = 'deliveryrulesfunction_id';
    const DELIVERYRULES_FUNCTION = 'deliveryrules_function';

    /**
     * Get deliveryrulesfunction_id
     *
     * @return string|null
     */
    public function getDeliveryrulesfunctionId();

    /**
     * Set deliveryrulesfunction_id
     *
     * @param string $deliveryrulesfunctionId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface
     */
    public function setDeliveryrulesfunctionId($deliveryrulesfunctionId);

    /**
     * Get deliveryrules_function
     *
     * @return string|null
     */
    public function getDeliveryrulesFunction();

    /**
     * Set deliveryrules_function
     *
     * @param string $deliveryrulesFunction
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface
     */
    public function setDeliveryrulesFunction($deliveryrulesFunction);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsExtensionInterface $extensionAttributes
    );
}
