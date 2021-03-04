<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface HolidaydatesRepositoryInterface
{

    /**
     * Save Holidaydates
     * @param \Elevate\Delivery\Api\Data\HolidaydatesInterface $deliveryHolidaydates
     * @return \Elevate\Delivery\Api\Data\HolidaydatesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\HolidaydatesInterface $deliveryHolidaydates
    );

    /**
     * Retrieve Holidaydates
     * @param string $deliveryholidaydatesId
     * @return \Elevate\Delivery\Api\Data\HolidaydatesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryholidaydatesId);

    /**
     * Retrieve Holidaydates matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\HolidaydatesSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Holidaydates
     * @param \Elevate\Delivery\Api\Data\HolidaydatesInterface $deliveryHolidaydates
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\HolidaydatesInterface $deliveryHolidaydates
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
     * @return \Elevate\Delivery\Api\Data\HolidaydatesInterface
     */
    public function create();
}
