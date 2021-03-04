<?php


namespace Mirasvit\Kb\Api\Data;

interface ArticlesectionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Article list.
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsInterface[]
     */
    public function getItems();

    /**
     * Set Article list.
     * @param \Mirasvit\Kb\Api\Data\ArticlesectionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
