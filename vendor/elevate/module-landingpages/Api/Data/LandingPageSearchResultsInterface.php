<?php


namespace Elevate\LandingPages\Api\Data;

interface LandingPageSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get LandingPage list.
     * @return \Elevate\LandingPages\Api\Data\LandingPageInterface[]
     */
    public function getItems();

    /**
     * Set test list.
     * @param \Elevate\LandingPages\Api\Data\LandingPageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
