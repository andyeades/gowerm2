<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryRulesFunctionsRepositoryInterface
{

    /**
     * Save DeliveryRules
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface $deliveryRules
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface $deliveryrulesFunctions
    );

    /**
     * Retrieve DeliveryRules
     * @param string $deliveryrulesid
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryrulesfunctionid);

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
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface $deliveryRules
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface $deliveryRulesFunctions
    );

    /**
     * Delete DeliveryRules by ID
     * @param string $deliveryrulesid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryrulesfunctionid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface
     */
    public function create();
}
