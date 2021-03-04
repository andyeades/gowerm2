<?php


namespace Elevate\CartAssignments\Api\Data;

/**
 * Interface CartAssignmentsSearchResultsInterface
 *
 * @package Elevate\CartAssignments\Api\Data
 */
interface CartAssignmentsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get CartAssignments list.
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface[]
     */
    public function getItems();

    /**
     * Set enable_addon list.
     * @param \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

