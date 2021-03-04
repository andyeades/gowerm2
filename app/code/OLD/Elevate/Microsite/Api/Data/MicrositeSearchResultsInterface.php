<?php


namespace Elevate\Microsite\Api\Data;

interface MicrositeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get microsite list.
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \Elevate\Microsite\Api\Data\MicrositeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
