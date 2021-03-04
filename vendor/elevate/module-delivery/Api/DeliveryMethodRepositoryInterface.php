<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryMethodRepositoryInterface
{

    /**
     * Save DeliveryMethod
     * @param \Elevate\Delivery\Api\Data\DeliveryMethodInterface $deliveryMethod
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryMethodInterface $deliveryMethod
    );

    /**
     * Retrieve DeliveryMethod
     * @param string $deliverymethodId
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliverymethodId);

    /**
     * Retrieve DeliveryMethod matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryMethod
     * @param \Elevate\Delivery\Api\Data\DeliveryMethodInterface $deliveryMethod
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryMethodInterface $deliveryMethod
    );

    /**
     * Delete DeliveryMethod by ID
     * @param string $deliverymethodId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliverymethodId);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryMethodInterface
     */
    public function create();
}
