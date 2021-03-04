<?php


namespace Elevate\Promotions\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PromotionsRepositoryInterface
{

    /**
     * Save promotions
     * @param \Elevate\Promotions\Api\Data\PromotionsInterface $promotions
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Promotions\Api\Data\PromotionsInterface $promotions
    );

    /**
     * Retrieve promotions
     * @param string $promotionsId
     * @return \Elevate\Promotions\Api\Data\PromotionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($promotionsId);

    /**
     * Retrieve promotions matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Promotions\Api\Data\PromotionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete promotions
     * @param \Elevate\Promotions\Api\Data\PromotionsInterface $promotions
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Promotions\Api\Data\PromotionsInterface $promotions
    );

    /**
     * Delete promotions by ID
     * @param string $promotionsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($promotionsId);
}
