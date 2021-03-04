<?php


namespace Elevate\Promotions\Api\Data;

interface PromotionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get promotions list.
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface[]
     */
    public function getItems();

    /**
     * Set sitewide_enable list.
     * @param \Elevate\Promotions\Api\Data\PromotionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
