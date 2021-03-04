<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryAreaRepositoryInterface
{

    /**
     * Save DeliveryArea
     * @param \Elevate\Delivery\Api\Data\DeliveryAreaInterface $deliveryArea
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryAreaInterface $deliveryArea
    );

    /**
     * Retrieve DeliveryArea
     * @param string $deliveryareaId
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryareaId);

    /**
     * Retrieve DeliveryArea matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryArea
     * @param \Elevate\Delivery\Api\Data\DeliveryAreaInterface $deliveryArea
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryAreaInterface $deliveryArea
    );

    /**
     * Delete DeliveryArea by ID
     * @param string $deliveryareaId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryareaId);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryAreaInterface
     */
    public function create();
}
