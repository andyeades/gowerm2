<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryRulesProducts list.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface[]
     */
    public function getItems();

    /**
     * Set deliverymethod_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
