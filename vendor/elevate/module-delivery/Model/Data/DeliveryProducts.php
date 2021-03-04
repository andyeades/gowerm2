<?php

namespace Elevate\Delivery\Model\Data;

use Elevate\Delivery\Api\Data\DeliveryProductsInterface;

class DeliveryProducts extends \Magento\Framework\Api\AbstractExtensibleObject implements DeliveryProductsInterface
{

    /**
     * Get deliveryproducts_id
     * @return string|null
     */
    public function getDeliveryproductsId()
    {
        return $this->_get(self::DELIVERYPRODUCTS_ID);
    }

    /**
     * Set deliveryproducts_id
     *
     * @param string $deliverproductsId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     */
    public function setDeliveryproductsId($deliveryproductsId) {
        return $this->setData(self::DELIVERYPRODUCTS_ID, $deliveryproductsId);
    }


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
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     */
    public function setDeliverymethodId($deliverymethodId)
    {
        return $this->setData(self::DELIVERYMETHOD_ID, $deliverymethodId);
    }

    /**
     * @return mixed
     */
    public function getProductId() {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * @param mixed $product_id
     */
    public function setProductId($product_id) {
        return $this->setData(self::PRODUCT_ID, $product_id);
    }

    /**
     * @return mixed
     */
    public function getProductSku() {
        return $this->_get(self::PRODUCT_SKU);
    }

    /**
     * @param mixed $product_sku
     */
    public function setProductSku($product_sku) {
        return $this->setData(self::PRODUCT_SKU, $product_sku);
    }

    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Elevate\Delivery\Api\Data\DeliveryProductsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryProductsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
