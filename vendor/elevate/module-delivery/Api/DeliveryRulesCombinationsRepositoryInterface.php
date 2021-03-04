<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryRulesCombinationsRepositoryInterface
{

    /**
     * Save DeliveryRulesCombinations
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface $deliveryRules
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface $deliveryRules
    );

    /**
     * Retrieve DeliveryRulesCombinations
     * @param string $deliveryrulescombinationsid
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryrulescombinationsid);

    /**
     * Retrieve DeliveryRulesCombinations matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryRulesCombinations
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface $deliveryRules
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface $deliveryRules
    );

    /**
     * Delete DeliveryRulesCombinations by ID
     * @param string $deliveryrulescombinationsid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryrulescombinationsid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface
     */
    public function create();
}
