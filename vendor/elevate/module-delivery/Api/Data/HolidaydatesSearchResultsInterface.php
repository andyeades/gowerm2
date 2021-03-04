<?php


namespace Elevate\Delivery\Api\Data;

interface HolidaydatesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Holidaydates list.
     * @return \Elevate\Delivery\Api\Data\HolidaydatesInterface[]
     */
    public function getItems();

    /**
     * Set deliveryarea_id list.
     * @param \Elevate\Delivery\Api\Data\HolidaydatesInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
