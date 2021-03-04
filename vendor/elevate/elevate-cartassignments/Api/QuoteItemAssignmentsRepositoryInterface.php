<?php


namespace Elevate\CartAssignments\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface QuoteItemAssignmentsRepositoryInterface
 *
 * @package Elevate\CartAssignments\Api
 */
interface QuoteItemAssignmentsRepositoryInterface
{

    /**
     * Save QuoteItemAssignments
     * @param \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface $quoteItemAssignments
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface $quoteItemAssignments
    );

    /**
     * Retrieve QuoteItemAssignments
     * @param string $quoteitemassignmentsId
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($quoteitemassignmentsId);

    /**
     * Retrieve QuoteItemAssignments matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete QuoteItemAssignments
     * @param \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface $quoteItemAssignments
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface $quoteItemAssignments
    );

    /**
     * Delete QuoteItemAssignments by ID
     * @param string $quoteitemassignmentsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($quoteitemassignmentsId);
}

