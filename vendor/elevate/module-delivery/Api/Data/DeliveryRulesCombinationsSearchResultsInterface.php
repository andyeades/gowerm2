<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesCombinationsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryRules list.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface[]
     */
    public function getItems();

    /**
     * Set deliverymethod_id list.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
