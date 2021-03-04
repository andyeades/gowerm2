<?php


namespace Elevate\CartAssignments\Api\Data;

/**
 * Interface QuoteItemAssignmentsSearchResultsInterface
 *
 * @package Elevate\CartAssignments\Api\Data
 */
interface QuoteItemAssignmentsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get QuoteItemAssignments list.
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface[]
     */
    public function getItems();

    /**
     * Set linked_quote_item_id list.
     * @param \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

