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

use Aitoc\OptionsManagement\Api\Data\TemplateInterface;

interface TemplateRepositoryInterface
{
    /**
     * Retrieve template.
     *
     * @param int $templateId
     * @param int $storeId
     * @return TemplateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($templateId, $storeId = 0);

    /**
     * Retrieve empty template.
     *
     * @param int $storeId
     * @return TemplateInterface
     */
    public function getEmpty($storeId = 0);

    /**
     * Retrieve templates matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete template.
     *
     * @param TemplateInterface $template
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(TemplateInterface $template);

    /**
     * Delete template by ID.
     *
     * @param int $templateId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($templateId);

    /**
     * Save template.
     *
     * @param TemplateInterface $template
     * @return TemplateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(TemplateInterface $template);

    /**
     * Save template.
     *
     * @param TemplateInterface $template
     * @return TemplateInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function duplicate(TemplateInterface $template);

    /**
     * Assign options to product.
     *
     * @param TemplateInterface $template
     * @param int $productId
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function assignOptionsToProduct(TemplateInterface $template, $productId);

    /**
     * Update options to product.
     *
     * @param TemplateInterface $template
     * @param int $productId
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function updateOptionsToProduct(TemplateInterface $template, $productId);

    /**
     * Update options store data to product.
     *
     * @param TemplateInterface $template
     * @param int $productId
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function updateStoreOptionsToProduct(TemplateInterface $template, $productId);

    /**
     * Remove options from product.
     *
     * @param TemplateInterface $template
     * @param int $productId
     * @param int $keepOptionsOnUnlink
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function removeOptionsFromProduct(TemplateInterface $template, $productId, $keepOptionsOnUnlink = 0);
}
