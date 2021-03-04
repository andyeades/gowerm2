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

use Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface;

interface TemplateOptionValueRepositoryInterface
{
    /**
     * Get custom option value from template by ID
     *
     * @param int $valueId
     * @return TemplateOptionValueInterface
     */
    public function getById($valueId);

    /**
     * Delete custom option value from template
     *
     * @param TemplateOptionValueInterface $value
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(TemplateOptionValueInterface $value);

    /**
     * Delete option from template by option value ID.
     *
     * @param int $valueId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($valueId);

    /**
     * Save template custom option value.
     *
     * @param TemplateOptionValueInterface $value
     * @return TemplateOptionValueInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(TemplateOptionValueInterface $value);
}
