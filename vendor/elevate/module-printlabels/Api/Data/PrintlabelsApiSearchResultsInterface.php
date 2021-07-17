<?php


namespace Elevate\PrintLabels\Api\Data;

interface PrintlabelsApiSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get PrintLabelsApi list.
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface[]
     */
    public function getItems();

    /**
     * Set PrintlabelsApiId list.
     * @param \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
