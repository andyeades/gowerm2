<?php


namespace Elevate\Support\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SupportRepositoryInterface
{

    /**
     * Save support
     * @param \Elevate\Support\Api\Data\SupportInterface $support
     * @return \Elevate\Support\Api\Data\SupportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Support\Api\Data\SupportInterface $support
    );

    /**
     * Retrieve support
     * @param string $supportId
     * @return \Elevate\Support\Api\Data\SupportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($supportId);

    /**
     * Retrieve support matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Support\Api\Data\SupportSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete support
     * @param \Elevate\Support\Api\Data\SupportInterface $support
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Support\Api\Data\SupportInterface $support
    );

    /**
     * Delete support by ID
     * @param string $supportId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($supportId);
}
