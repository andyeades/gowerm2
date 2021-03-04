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

interface TemplateOptionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const OPTION_ID = 'option_id';
    const TEMPLATE_ID = 'template_id';
    const TYPE = 'type';
    const TITLE = 'title';
    const IS_REQUIRE = 'is_require';
    const SKU = 'sku';
    const MAX_CHARACTERS = 'max_characters';
    const FILE_EXTENSION = 'file_extension';
    const IMAGE_SIZE_Y = 'image_size_y';
    const IMAGE_SIZE_X = 'image_size_x';
    const SORT_ORDER = 'sort_order';
    const PRICE = 'price';
    const PRICE_TYPE = 'price_type';
    /**#@-*/

    /**
     * @return int|null
     */
    public function getOptionId();

    /**
     * @param int $optionId
     * @return $this
     */
    public function setOptionId($optionId);

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
     * @return string|null
     */
    public function getType();

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type);

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
     * @return bool|null
     */
    public function getIsRequire();

    /**
     * @param bool $isRequired
     * @return $this
     */
    public function setIsRequire($isRequired);

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
    public function getFileExtension();

    /**
     * @param string $fileExtension
     * @return $this
     */
    public function setFileExtension($fileExtension);

    /**
     * @return int|null
     */
    public function getMaxCharacters();

    /**
     * @param int $maxCharacters
     * @return $this
     */
    public function setMaxCharacters($maxCharacters);

    /**
     * @return int|null
     */
    public function getImageSizeX();

    /**
     * @param int $imageSizeX
     * @return $this
     */
    public function setImageSizeX($imageSizeX);

    /**
     * @return int|null
     */
    public function getImageSizeY();

    /**
     * @param int $imageSizeY
     * @return $this
     */
    public function setImageSizeY($imageSizeY);

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

    /**
     * @return \Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface[]|null
     */
    public function getValues();

    /**
     * @param \Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface[] $values
     * @return $this
     */
    public function setValues(array $values = null);
}
