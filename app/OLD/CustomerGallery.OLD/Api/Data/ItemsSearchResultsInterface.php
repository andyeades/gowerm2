<?php


namespace Elevate\CustomerGallery\Api\Data;

interface ItemsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Items list.
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface[]
     */
    public function getItems();

    /**
     * Set namejgchange list.
     * @param \Elevate\CustomerGallery\Api\Data\ItemsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
