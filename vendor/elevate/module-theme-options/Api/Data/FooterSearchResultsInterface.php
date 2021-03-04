<?php


namespace Elevate\Themeoptions\Api\Data;

interface FooterSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Footer list.
     * @return \Elevate\Themeoptions\Api\Data\FooterInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Elevate\Themeoptions\Api\Data\FooterInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
