<?php


namespace Elevate\CustomerGallery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ItemsRepositoryInterface
{

    /**
     * Save Items
     * @param \Elevate\CustomerGallery\Api\Data\ItemsInterface $items
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\CustomerGallery\Api\Data\ItemsInterface $items
    );

    /**
     * Retrieve Items
     * @param string $itemsId
     * @return \Elevate\CustomerGallery\Api\Data\ItemsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($itemsId);

    /**
     * Retrieve Items matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\CustomerGallery\Api\Data\ItemsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Items
     * @param \Elevate\CustomerGallery\Api\Data\ItemsInterface $items
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\CustomerGallery\Api\Data\ItemsInterface $items
    );

    /**
     * Delete Items by ID
     * @param string $itemsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($itemsId);
}
