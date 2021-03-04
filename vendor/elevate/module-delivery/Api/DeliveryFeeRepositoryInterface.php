<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryFeeRepositoryInterface
{

    /**
     * Save DeliveryFee
     * @param \Elevate\Delivery\Api\Data\DeliveryFeeInterface $deliveryFee
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryFeeInterface $deliveryFee
    );

    /**
     * Retrieve DeliveryFee
     * @param string $deliveryfeeId
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryfeeId);

    /**
     * Retrieve DeliveryFee matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryFee
     * @param \Elevate\Delivery\Api\Data\DeliveryFeeInterface $deliveryFee
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryFeeInterface $deliveryFee
    );

    /**
     * Delete DeliveryFee by ID
     * @param string $deliveryfeeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryfeeId);
    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryFeeInterface
     */
    public function create();
}
