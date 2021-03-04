<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesProductsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYRULESPRODUCTSID = 'deliveryrulesproducts_id';
    const DELIVERYRULES_ID = 'deliveryrules_id';
    const PRODUCT_ID = 'product_id';
    const PRODUCT_SKU = 'product_sku';
    const ATTRIBUTE_SET_ID = 'attribute_set_id';

    /**
     * Get deliveryrulesproducts_id
     *
     * @return string|null
     */
    public function getDeliveryrulesproductsId();

    /**
     * Set deliveryrulesproducts_id
     *
     * @param string $deliveryrulesproductsId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface
     */
    public function setDeliveryrulesproductsId($deliveryrulesproductsId);

    /**
     * @return mixed
     */
    public function getDeliveryrulesId();

    /**
     * @param mixed $deliveryrules_id
     */
    public function setDeliveryrulesId($deliveryrules_id);

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @param mixed $product_id
     */
    public function setProductId($product_id);

    /**
     * @return mixed
     */
    public function getProductSku();

    /**
     * @param mixed $product_sku
     */
    public function setProductSku($product_sku);

    /**
     * @return mixed
     */
    public function getAttributeSetId();

    /**
     * @param mixed $attribute_set_id
     */
    public function setAttributeSetId($attribute_set_id);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesProductsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryRulesProductsExtensionInterface $extensionAttributes
    );
}
