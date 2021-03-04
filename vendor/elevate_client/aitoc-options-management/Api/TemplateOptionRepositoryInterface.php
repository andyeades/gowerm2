<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Api;

use Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface;

interface TemplateOptionRepositoryInterface
{
    /**
     * Get custom option from template by option ID
     *
     * @param int $optionId
     * @return TemplateOptionInterface
     */
    public function getById($optionId);

    /**
     * Retrieve the list of custom options for a specific template matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateOptionSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete custom option from template
     *
     * @param TemplateOptionInterface $option
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(TemplateOptionInterface $option);

    /**
     * Delete option from template by option ID.
     *
     * @param int $optionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($optionId);

    /**
     * Save template custom option.
     *
     * @param TemplateOptionInterface $option
     * @return TemplateOptionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(TemplateOptionInterface $option);
}
