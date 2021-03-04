<?php


namespace Elevate\Support\Api\Data;

interface SupportSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get support list.
     * @return \Elevate\Support\Api\Data\SupportInterface[]
     */
    public function getItems();

    /**
     * Set operating_system list.
     * @param \Elevate\Support\Api\Data\SupportInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
