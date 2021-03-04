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

interface TemplateInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TEMPLATE_ID = 'template_id';
    const TITLE = 'title';
    const IS_REPLACE_PRODUCT_SKU = 'is_replace_product_sku';
    const SORT_ORDER = 'sort_order';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**#@-*/

    /**
     * @return int|null
     */
    public function getTemplateId();

    /**
     * @param int $id
     * @return $this
     */
    public function setTemplateId($id);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return int
     */
    public function getIsReplaceProductSku();

    /**
     * @param int $flag
     * @return $this
     */
    public function setIsReplaceProductSku($flag);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param int $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getUpdateddAt();

    /**
     * @param int $updateddAt
     * @return $this
     */
    public function setUpdateddAt($updateddAt);
}
