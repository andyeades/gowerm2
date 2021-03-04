<?php


namespace Elevate\Delivery\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DeliveryRulesTypeRepositoryInterface
{

    /**
     * Save DeliveryRulesType
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface $deliveryRulesType
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface $deliveryRulesType
    );

    /**
     * Retrieve DeliveryRulesType
     * @param string $deliveryrulestypeid
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($deliveryrulestypeid);

    /**
     * Retrieve DeliveryRulesType matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DeliveryRulesType
     * @param \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface $deliveryRulesType
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface $deliveryRulesType
    );

    /**
     * Delete DeliveryRulesType by ID
     * @param string $deliveryrulestypeid
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($deliveryrulestypeid);

    /**
     * Creates new Transaction instance.
     *
     * @return \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface
     */
    public function create();
}
