<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryAreaSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryArea list.
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaInterface[]
     */
    public function getItems();

    /**
     * Set deliveryarea_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryAreaInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
