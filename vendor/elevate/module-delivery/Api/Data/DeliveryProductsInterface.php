<?php

namespace Elevate\Delivery\Api\Data;

interface DeliveryProductsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const DELIVERYPRODUCTS_ID = 'deliveryproducts_id';
    const DELIVERYMETHOD_ID = 'deliverymethod_id';
    const PRODUCT_ID = 'product_id';
    const PRODUCT_SKU = 'product_sku';

    /**
     * Get deliveryproducts_id
     *
     * @return string|null
     */
    public function getDeliveryproductsId();

    /**
     * Set deliveryproducts_id
     *
     * @param string $deliverproductsId
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     */
    public function setDeliveryproductsId($deliveryproductsId);
    
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
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     */
    public function setDeliverymethodId($deliverymethodId);

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
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Delivery\Api\Data\DeliveryProductsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Delivery\Api\Data\DeliveryProductsExtensionInterface $extensionAttributes
    );
}
