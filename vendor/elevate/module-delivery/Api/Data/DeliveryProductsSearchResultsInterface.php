<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryProducts list.
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface[]
     */
    public function getItems();

    /**
     * Set deliverymethod_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
