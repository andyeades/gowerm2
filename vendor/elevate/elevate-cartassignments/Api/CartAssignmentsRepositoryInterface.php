<?php


namespace Elevate\CartAssignments\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface CartAssignmentsRepositoryInterface
 *
 * @package Elevate\CartAssignments\Api
 */
interface CartAssignmentsRepositoryInterface
{

    /**
     * Save CartAssignments
     * @param \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface $cartAssignments
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface $cartAssignments
    );

    /**
     * Retrieve CartAssignments
     * @param string $cartassignmentsId
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($cartassignmentsId);

    /**
     * Retrieve CartAssignments matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\CartAssignments\Api\Data\CartAssignmentsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete CartAssignments
     * @param \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface $cartAssignments
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface $cartAssignments
    );

    /**
     * Delete CartAssignments by ID
     * @param string $cartassignmentsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($cartassignmentsId);
}

