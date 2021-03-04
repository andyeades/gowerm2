<?php


namespace Mirasvit\Kb\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ArticlesubsectionsRepositoryInterface
{

    /**
     * Save Article
     * @param \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface $articlesubsections
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface $articlesubsections
    );

    /**
     * Retrieve DeliveryMethod
     * @param string $articlesubsection_id
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($articlesubsection_id);

    /**
     * Retrieve DeliveryMethod matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsextionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryMethod
     * @param \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface $articlesubsections
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface $articlesubsections
    );

    /**
     * Delete DeliveryMethod by ID
     * @param string $articlesubsection_id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($articlesubsection_id);

    /**
     * Creates new Transaction instance.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface
     */
    public function create();
}
