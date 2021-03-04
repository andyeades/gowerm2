<?php

namespace Elevate\Microsite\Api;

/**
 * Interface RuleRepositoryInterface
 * @api
 */
interface RuleRepositoryInterface
{
    /**
     * @param \Elevate\Microsite\Api\Data\RuleInterface $rule
     * @return \Elevate\Microsite\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Elevate\Microsite\Api\Data\RuleInterface $rule);

    /**
     * @param int $ruleId
     * @return \Elevate\Microsite\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($ruleId);

    /**
     * @param \Elevate\Microsite\Api\Data\RuleInterface $rule
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Elevate\Microsite\Api\Data\RuleInterface $rule);

    /**
     * @param int $ruleId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($ruleId);
}
