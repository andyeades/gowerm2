<?php


namespace Mirasvit\Kb\Api\Data;

interface ArticleSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Article list.
     * @return \Mirasvit\Kb\Api\Data\ArticleInterface[]
     */
    public function getItems();

    /**
     * Set Article list.
     * @param \Mirasvit\Kb\Api\Data\ArticleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
