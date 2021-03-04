<?php


namespace Elevate\Themeoptions\Api\Data;

interface OptionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Options list.
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Elevate\Themeoptions\Api\Data\OptionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
