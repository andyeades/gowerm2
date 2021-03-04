<?php


namespace Elevate\Themeoptions\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface OptionsRepositoryInterface
{

    /**
     * Save Options
     * @param \Elevate\Themeoptions\Api\Data\OptionsInterface $Options
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Themeoptions\Api\Data\OptionsInterface $Options
    );

    /**
     * Retrieve Options
     * @param string $entityId
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Options matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Themeoptions\Api\Data\OptionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Options
     * @param \Elevate\Themeoptions\Api\Data\OptionsInterface $Options
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Themeoptions\Api\Data\OptionsInterface $Options
    );

    /**
     * Delete by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     */
    public function create();
}
