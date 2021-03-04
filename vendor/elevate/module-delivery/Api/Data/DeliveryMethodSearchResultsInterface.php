<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryMethodSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryMethod list.
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodInterface[]
     */
    public function getItems();

    /**
     * Set deliverymethod_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryMethodInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
