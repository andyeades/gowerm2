<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesTypeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryRulesType list.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface[]
     */
    public function getItems();

    /**
     * Set deliveryrules_type list.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
