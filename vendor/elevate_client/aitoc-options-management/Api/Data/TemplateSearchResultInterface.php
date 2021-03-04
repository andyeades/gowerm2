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
 * Interface for template search results.
 * @api
 */
interface TemplateSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get templates list.
     *
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateInterface[]
     */
    public function getItems();

    /**
     * Set templates list.
     *
     * @param \Aitoc\OptionsManagement\Api\Data\TemplateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
