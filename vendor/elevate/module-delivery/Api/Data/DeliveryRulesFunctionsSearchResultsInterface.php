<?php


namespace Elevate\Delivery\Api\Data;

interface DeliveryRulesFunctionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get DeliveryRules Functions list.
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface[]
     */
    public function getItems();

    /**
     * Set Delivery Rules Functions list.
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
