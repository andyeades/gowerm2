<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryRulesProductsRepositoryInterface
{

    /**
     * Save DeliveryRulesProducts
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface $deliveryRulesProducts
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface $deliveryRulesProducts
    );

    /**
     * Retrieve DeliveryRulesProducts
     * @param string $deliveryrulesproductsid
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryrulesproductsid);

    /**
     * Retrieve DeliveryRulesProducts matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryRulesProducts
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface $deliveryRulesProducts
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface $deliveryRulesProducts
    );

    /**
     * Delete DeliveryRulesProducts by ID
     * @param string $deliveryrulesproductsid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryrulesproductsid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface
     */
    public function create();
}
