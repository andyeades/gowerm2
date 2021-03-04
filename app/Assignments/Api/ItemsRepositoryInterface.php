<?php


namespace Elevate\Assignments\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ItemsRepositoryInterface
{

    /**
     * Save Items
     * @param \Elevate\Assignments\Api\Data\ItemsInterface $items
     * @return \Elevate\Assignments\Api\Data\ItemsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Assignments\Api\Data\ItemsInterface $items
    );

    /**
     * Retrieve Items
     * @param string $itemsId
     * @return \Elevate\Assignments\Api\Data\ItemsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($itemsId);

    /**
     * Retrieve Items matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Assignments\Api\Data\ItemsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Items
     * @param \Elevate\Assignments\Api\Data\ItemsInterface $items
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Assignments\Api\Data\ItemsInterface $items
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
