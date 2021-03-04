<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Api\Data;

/**
 * Interface for template option search results.
 * @api
 */
interface TemplateOptionSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get template options list.
     *
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface[]
     */
    public function getItems();

    /**
     * Set template options list.
     *
     * @param \Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
