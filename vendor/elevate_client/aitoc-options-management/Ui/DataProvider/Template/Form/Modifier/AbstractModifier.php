<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Ui\DataProvider\Template\Form\Modifier;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class AbstractModifier
 *
 * @api
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class AbstractModifier implements ModifierInterface
{
    const FORM_NAME = 'product_form';
    const DATA_SCOPE = 'data';

    /**
     * Default general panel order
     */
    const GENERAL_PANEL_ORDER = 10;

    /**
     * Container fieldset prefix
     */
    const CONTAINER_PREFIX = 'container_';

    /**
     * Meta config path
     */
    const META_CONFIG_PATH = '/arguments/data/config';

    /**
     * Retrieve next group sort order
     *
     * @param array $meta
     * @param array|string $groupCodes
     * @param int $defaultSortOrder
     * @param int $iteration
     * @return int
     */
    protected function getNextGroupSortOrder(array $meta, $groupCodes, $defaultSortOrder, $iteration = 1)
    {
        $groupCodes = (array)$groupCodes;

        foreach ($groupCodes as $groupCode) {
            if (isset($meta[$groupCode]['arguments']['data']['config']['sortOrder'])) {
                return $meta[$groupCode]['arguments']['data']['config']['sortOrder'] + $iteration;
            }
        }

        return $defaultSortOrder;
    }

    /**
     * Retrieve next attribute sort order
     *
     * @param array $meta
     * @param array|string $attributeCodes
     * @param int $defaultSortOrder
     * @param int $iteration
     * @return int
     */
    protected function getNextAttributeSortOrder(array $meta, $attributeCodes, $defaultSortOrder, $iteration = 1)
    {
        $attributeCodes = (array)$attributeCodes;

        foreach ($meta as $groupMeta) {
            $defaultSortOrder = $this->_getNextAttributeSortOrder(
                $groupMeta,
                $attributeCodes,
                $defaultSortOrder,
                $iteration
            );
        }

        return $defaultSortOrder;
    }

    /**
     * Retrieve next attribute sort order
     *
     * @param array $meta
     * @param array $attributeCodes
     * @param int $defaultSortOrder
     * @param int $iteration
     * @return mixed
     */
    private function _getNextAttributeSortOrder(array $meta, $attributeCodes, $defaultSortOrder, $iteration = 1)
    {
        if (isset($meta['children'])) {
            foreach ($meta['children'] as $attributeCode => $attributeMeta) {
                if ($this->startsWith($attributeCode, self::CONTAINER_PREFIX)) {
                    $defaultSortOrder = $this->_getNextAttributeSortOrder(
                        $attributeMeta,
                        $attributeCodes,
                        $defaultSortOrder,
                        $iteration
                    );
                } elseif (in_array($attributeCode, $attributeCodes)
                    && isset($attributeMeta['arguments']['data']['config']['sortOrder'])
                ) {
                    $defaultSortOrder = $attributeMeta['arguments']['data']['config']['sortOrder'] + $iteration;
                }
            }
        }

        return $defaultSortOrder;
    }

    /**
     * Search backwards starting from haystack length characters from the end
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    protected function startsWith($haystack, $needle)
    {
        return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * Retrieve first panel name
     *
     * @param array $meta
     * @return string|null
     */
    protected function getFirstPanelCode(array $meta)
    {
        $min = null;
        $name = null;

        foreach ($meta as $fieldSetName => $fieldSetMeta) {
            if (isset($fieldSetMeta['arguments']['data']['config']['sortOrder'])
                && (null === $min || $fieldSetMeta['arguments']['data']['config']['sortOrder'] <= $min)
            ) {
                $min = $fieldSetMeta['arguments']['data']['config']['sortOrder'];
                $name = $fieldSetName;
            }
        }

        return $name;
    }

    /**
     * Get group code by field
     *
     * @param array $meta
     * @param string $field
     * @return string|bool
     */
    protected function getGroupCodeByField(array $meta, $field)
    {
        foreach ($meta as $groupCode => $groupData) {
            if (isset($groupData['children'][$field])
                || isset($groupData['children'][static::CONTAINER_PREFIX . $field])
            ) {
                return $groupCode;
            }
        }

        return false;
    }

    /**
     * Format price to have only two decimals after delimiter
     *
     * @param mixed $value
     * @return string
     */
    protected function formatPrice($value)
    {
        return $value !== null ? number_format((float)$value, PriceCurrencyInterface::DEFAULT_PRECISION, '.', '') : '';
    }

    /**
     * Strip excessive decimal digits from weight number
     *
     * @param mixed $value
     * @return string
     */
    protected function formatWeight($value)
    {
        return (float)$value;
    }
}
