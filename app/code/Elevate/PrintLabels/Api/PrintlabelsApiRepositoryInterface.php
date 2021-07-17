<?php


namespace Elevate\PrintLabels\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PrintlabelsApiRepositoryInterface
{

    /**
     * Save PrintlabelsApi
     * @param \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface $printlabelsApi
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface $printlabelsApi
    );

    /**
     * Retrieve PrintlabelsApi
     * @param string $printlabelsApiId
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($printlabelsApiId);

    /**
     * Retrieve PrintlabelsApi matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete PrintlabelsApi
     * @param \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface $printlabelsApi
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface $printlabelsApi
    );

    /**
     * Delete PrintlabelsApi by ID
     * @param string $printlabelsApiId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($printlabelsApiId);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface
     */
    public function create();
}
