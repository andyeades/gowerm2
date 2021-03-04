<?php


namespace Mirasvit\Kb\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ArticleRepositoryInterface
{

    /**
     * Save Article
     * @param \Mirasvit\Kb\Api\Data\ArticleInterface $article
     * @return \Mirasvit\Kb\Api\Data\ArticleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Mirasvit\Kb\Api\Data\ArticleInterface $article
    );

    /**
     * Retrieve DeliveryMethod
     * @param string $articleid
     * @return \Mirasvit\Kb\Api\Data\ArticleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($articleid);

    /**
     * Retrieve DeliveryMethod matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Mirasvit\Kb\Api\Data\ArticleSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryMethod
     * @param \Mirasvit\Kb\Api\Data\ArticleInterface $article
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Mirasvit\Kb\Api\Data\ArticleInterface $article
    );

    /**
     * Delete DeliveryMethod by ID
     * @param string $articleid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($articleid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticleInterface
     */
    public function create();
}
