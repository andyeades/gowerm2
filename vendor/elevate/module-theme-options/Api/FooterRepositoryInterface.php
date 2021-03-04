<?php


namespace Elevate\Themeoptions\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FooterRepositoryInterface
{

    /**
     * Save Footer
     * @param \Elevate\Themeoptions\Api\Data\FooterInterface $Footer
     * @return \Elevate\Themeoptions\Api\Data\FooterInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Themeoptions\Api\Data\FooterInterface $Footer
    );

    /**
     * Retrieve Footer
     * @param string $entityId
     * @return \Elevate\Themeoptions\Api\Data\FooterInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Footer matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Themeoptions\Api\Data\FooterSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Footer
     * @param \Elevate\Themeoptions\Api\Data\FooterInterface $Footer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Themeoptions\Api\Data\FooterInterface $Footer
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
     * @return \Elevate\Themeoptions\Api\Data\FooterInterface
     */
    public function create();
}
