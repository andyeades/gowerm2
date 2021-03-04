<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryRulesRepositoryInterface
{

    /**
     * Save DeliveryRules
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesInterface $deliveryRules
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryRulesInterface $deliveryRules
    );

    /**
     * Retrieve DeliveryRules
     * @param string $deliveryrulesid
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryrulesid);

    /**
     * Retrieve DeliveryRules matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryRules
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesInterface $deliveryRules
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryRulesInterface $deliveryRules
    );

    /**
     * Delete DeliveryRules by ID
     * @param string $deliveryrulesid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryrulesid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesInterface
     */
    public function create();
}
