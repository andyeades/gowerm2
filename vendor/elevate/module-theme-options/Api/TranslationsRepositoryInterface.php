<?php


namespace Elevate\Themeoptions\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TranslationsRepositoryInterface
{

    /**
     * Save Translations
     * @param \Elevate\Themeoptions\Api\Data\TranslationsInterface $Translations
     * @return \Elevate\Themeoptions\Api\Data\TranslationsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Themeoptions\Api\Data\TranslationsInterface $Translations
    );

    /**
     * Retrieve Translations
     * @param string $entityId
     * @return \Elevate\Themeoptions\Api\Data\TranslationsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Translations matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Themeoptions\Api\Data\TranslationsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Translations
     * @param \Elevate\Themeoptions\Api\Data\TranslationsInterface $Translations
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Themeoptions\Api\Data\TranslationsInterface $Translations
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
     * @return \Elevate\Themeoptions\Api\Data\TranslationsInterface
     */
    public function create();
}
