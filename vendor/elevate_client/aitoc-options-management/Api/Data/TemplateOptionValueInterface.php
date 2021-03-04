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

interface TemplateOptionValueInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const OPTION_TYPE_ID = 'option_type_id';
    const SKU = 'sku';
    const TITLE = 'title';
    const SORT_ORDER = 'sort_order';
    const PRICE = 'price';
    const PRICE_TYPE = 'price_type';
    /**#@-*/

    /**
     * @return int|null
     */
    public function getOptionTypeId();

    /**
     * @param int $optionTypeId
     * @return $this
     */
    public function setOptionTypeId($optionTypeId);

    /**
     * @return string|null
     */
    public function getSku();

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * @return string|null
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return int|null
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * @return float|null
     */
    public function getPrice();

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * @return string|null
     */
    public function getPriceType();

    /**
     * @param string $priceType
     * @return $this
     */
    public function setPriceType($priceType);
}
