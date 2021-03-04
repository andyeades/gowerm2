<?php


namespace Elevate\Themeoptions\Api\Data;

interface TranslationsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Footer list.
     * @return \Elevate\Themeoptions\Api\Data\TranslationsInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Elevate\Themeoptions\Api\Data\TranslationsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);

}
