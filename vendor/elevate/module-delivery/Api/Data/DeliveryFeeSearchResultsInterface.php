<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryFeeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryFee list.
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeInterface[]
     */
    public function getItems();

    /**
     * Set deliveryfee_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryFeeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
