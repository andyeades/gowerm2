<?php


namespace Elevate\PrintLabels\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface HolidaydatesRepositoryInterface
{

    /**
     * Save Holidaydates
     * @param \Elevate\PrintLabels\Api\Data\HolidaydatesInterface $deliveryHolidaydates
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\PrintLabels\Api\Data\HolidaydatesInterface $deliveryHolidaydates
    );

    /**
     * Retrieve Holidaydates
     * @param string $deliveryholidaydatesId
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryholidaydatesId);

    /**
     * Retrieve Holidaydates matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Holidaydates
     * @param \Elevate\PrintLabels\Api\Data\HolidaydatesInterface $deliveryHolidaydates
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\PrintLabels\Api\Data\HolidaydatesInterface $deliveryHolidaydates
    );

    /**
     * Delete Holidaydates by ID
     * @param string $deliveryholidaydatesId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryholidaydatesId);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\PrintLabels\Api\Data\HolidaydatesInterface
     */
    public function create();
}
