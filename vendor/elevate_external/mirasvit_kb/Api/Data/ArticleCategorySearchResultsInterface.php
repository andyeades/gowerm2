<?php


namespace Mirasvit\Kb\Api\Data;

interface ArticleCategorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Article list.
     * @return \Mirasvit\Kb\Api\Data\ArticleCategoryInterface[]
     */
    public function getItems();

    /**
     * Set Article list.
     * @param \Mirasvit\Kb\Api\Data\ArticleCategoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
