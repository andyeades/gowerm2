<?php


namespace Mirasvit\Kb\Api\Data;

interface ArticlesubsectionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Articlesubsections list.
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface[]
     */
    public function getItems();

    /**
     * Set Articlesubsections list.
     * @param \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
