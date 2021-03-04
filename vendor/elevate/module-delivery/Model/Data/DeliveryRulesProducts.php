<?php


namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface;

/**
 * Class DeliveryRulesProducts
 *
 * @category Elevate
 * @package  Elevate\Delivery\Model\Data
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class DeliveryRulesProducts extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryRulesProductsInterface
{
    /**
     * @return mixed|string|null
     */
    public function getDeliveryrulesproductsId() {
        return $this->_get(self::DELIVERYRULESPRODUCTSID);
    }

    /**
     * @param string $deliveryrulesproductsId
     *
     * @return DeliveryRulesProductsInterface|DeliveryRulesProducts
     */
    public function setDeliveryrulesproductsId($deliveryrulesproductsId) {
        return $this->setData(self::DELIVERYRULESPRODUCTSID, $deliveryrulesproductsId);
    }

    /**
     * @return mixed|null
     */
    public function getDeliveryrulesId() {
        return $this->_get(self::DELIVERYRULES_ID);
    }

    /**
     * @param mixed $deliveryrules_id
     *
     * @return DeliveryRulesProducts
     */
    public function setDeliveryrulesId($deliveryrules_id) {
        return $this->setData(self::DELIVERYRULES_ID, $deliveryrules_id);
    }

    /**
     * @return mixed|null
     */
    public function getProductId() {
        return $this->_get(self::DELIVERYRULES_ID);
    }

    /**
     * @param mixed $product_id
     *
     * @return DeliveryRulesProducts
     */
    public function setProductId($product_id) {
        return $this->setData(self::PRODUCT_ID, $product_id);
    }

    /**
     * @return mixed|null
     */
    public function getProductSku() {
        return $this->_get(self::PRODUCT_SKU);
    }

    /**
     * @param mixed $product_sku
     *
     * @return DeliveryRulesProducts
     */
    public function setProductSku($product_sku) {
        return $this->setData(self::PRODUCT_SKU, $product_sku);
    }

    /**
     * @return mixed|null
     */
    public function getAttributeSetId() {
        return $this->_get(self::ATTRIBUTE_SET_ID);
    }

    /**
     * @param mixed $attribute_set_id
     *
     * @return DeliveryRulesProducts
     */
    public function setAttributeSetId($attribute_set_id) {
        return $this->setData(self::ATTRIBUTE_SET_ID, $attribute_set_id);
    }

    /**
     * @return array
     */
    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesProductsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
