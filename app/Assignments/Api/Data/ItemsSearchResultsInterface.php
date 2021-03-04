<?php


namespace Elevate\Assignments\Api\Data;

interface ItemsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Items list.
     * @return \Elevate\Assignments\Api\Data\ItemsInterface[]
     */
    public function getItems();

    /**
     * Set namejgchange list.
     * @param \Elevate\Assignments\Api\Data\ItemsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
