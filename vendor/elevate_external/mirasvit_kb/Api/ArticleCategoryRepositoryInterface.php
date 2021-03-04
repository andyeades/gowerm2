<?php


namespace Mirasvit\Kb\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ArticleCategoryRepositoryInterface
{

    /**
     * Save Article
     * @param \Mirasvit\Kb\Api\Data\ArticleCategoryInterface $articlecategory
     * @return \Mirasvit\Kb\Api\Data\ArticleCategoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Mirasvit\Kb\Api\Data\ArticleCategoryInterface $articlecategory
    );

    /**
     * Retrieve DeliveryMethod
     * @param string $articleCategoryid
     * @return \Mirasvit\Kb\Api\Data\ArticleCategoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($articleCategoryid);

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
     * @param \Mirasvit\Kb\Api\Data\ArticleCategoryInterface $articlecategory
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Mirasvit\Kb\Api\Data\ArticleCategoryInterface $articlecategory
    );

    /**
     * Delete DeliveryMethod by ID
     * @param string $articleCategoryid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($articleCategoryid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticleCategoryInterface
     */
    public function create();
}
