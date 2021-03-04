<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryProductsRepositoryInterface
{

    /**
     * Save DeliveryProducts
     * @param \Elevate\Delivery\Api\Data\DeliveryProductsInterface $deliveryProducts
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryProductsInterface $deliveryProducts
    );

    /**
     * Retrieve DeliveryProducts
     * @param string $deliveryproductsid
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryproductsid);

    /**
     * Retrieve DeliveryProducts matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryProducts
     * @param \Elevate\Delivery\Api\Data\DeliveryProductsInterface $deliveryProducts
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryProductsInterface $deliveryProducts
    );

    /**
     * Delete DeliveryProducts by ID
     * @param string $deliveryproductsid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryproductsid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryProductsInterface
     */
    public function create();
}
