<?php


namespace Elevate\PrintLabels\Api\Data;

interface HolidaydatesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Holidaydates list.
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesInterface[]
     */
    public function getItems();

    /**
     * Set deliveryarea_id list.
     * @param \Elevate\PrintLabels\Api\Data\HolidaydatesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
