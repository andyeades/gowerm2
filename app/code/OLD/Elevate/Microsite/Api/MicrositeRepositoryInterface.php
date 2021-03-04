<?php


namespace Elevate\Microsite\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface MicrositeRepositoryInterface
{

    /**
     * Save microsite
     * @param \Elevate\Microsite\Api\Data\MicrositeInterface $microsite
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Microsite\Api\Data\MicrositeInterface $microsite
    );

    /**
     * Retrieve microsite
     * @param string $micrositeId
     * @return \Elevate\Microsite\Api\Data\MicrositeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($micrositeId);

    /**
     * Retrieve microsite matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Microsite\Api\Data\MicrositeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete microsite
     * @param \Elevate\Microsite\Api\Data\MicrositeInterface $microsite
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Microsite\Api\Data\MicrositeInterface $microsite
    );

    /**
     * Delete microsite by ID
     * @param string $micrositeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($micrositeId);
}
