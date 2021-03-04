<?php


namespace Mirasvit\Kb\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ArticlesectionsRepositoryInterface
{

    /**
     * Save Article
     * @param \Mirasvit\Kb\Api\Data\ArticlesectionsInterface $articlesections
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Mirasvit\Kb\Api\Data\ArticlesectionsInterface $articlesections
    );

    /**
     * Retrieve DeliveryMethod
     * @param string $articlesectionsid
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($articlesectionsid);

    /**
     * Retrieve DeliveryMethod matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryMethod
     * @param \Mirasvit\Kb\Api\Data\ArticlesectionsInterface $articlesections
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Mirasvit\Kb\Api\Data\ArticlesectionsInterface $articlesections
    );

    /**
     * Delete DeliveryMethod by ID
     * @param string $articlesectionsid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($articlesectionsid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsInterface
     */
    public function create();
}
