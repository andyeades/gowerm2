<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template\Option\Validator;

use Aitoc\OptionsManagement\Model\Template\Option;

class Select extends DefaultValidator
{
    /**
     * Check if all values are marked for removal
     *
     * @param array $values
     * @return bool
     */
    protected function checkAllValuesRemoved($values)
    {
        foreach ($values as $value) {
            if (!array_key_exists('is_delete', $value) || $value['is_delete'] != 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate option type fields
     *
     * @param Option $option
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function validateOptionValue(Option $option)
    {
        $values = $option->getData('values');
        if (!is_array($values) || $this->isEmpty($values)) {
            return false;
        }

        //forbid removal of last value for option
        if ($this->checkAllValuesRemoved($values)) {
            return false;
        }

        $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        foreach ($values as $value) {
            if (isset($value['is_delete']) && (bool)$value['is_delete']) {
                continue;
            }
            $type = isset($value['price_type']) ? $value['price_type'] : null;
            $price = isset($value['price']) ? $value['price'] : null;
            $title = isset($value['title']) ? $value['title'] : null;
            if (!$this->isValidOptionPrice($type, $price, $storeId)
                || !$this->isValidOptionTitle($title, $storeId)
            ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate option price
     *
     * @param string $priceType
     * @param int $price
     * @param int $storeId
     * @return bool
     */
    protected function isValidOptionPrice($priceType, $price, $storeId)
    {
        // we should be able to remove website values for default store fallback
        if ($storeId > \Magento\Store\Model\Store::DEFAULT_STORE_ID && $priceType === null && $price === null) {
            return true;
        }
        if (!$this->isInRange($priceType, $this->priceTypes) || $this->isNegative($price)) {
            return false;
        }

        return true;
    }
}
