<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryRules list.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface[]
     */
    public function getItems();

    /**
     * Set deliverymethod_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
