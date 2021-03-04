<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Controller\Adminhtml\Product\Initialization;

class Helper
{
    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * @var array
     */
    protected $overwriteOptions = [];

    /**
     * Helper constructor.
     *
     * @param \Aitoc\OptionsManagement\Helper\Data $helper
     */
    public function __construct(\Aitoc\OptionsManagement\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param  \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper
     * @param array $productOptions
     * @param array $overwriteOptions
     * @return array
     */
    public function beforeMergeProductOptions($initializationHelper, $productOptions, $overwriteOptions)
    {
        $this->overwriteOptions = $overwriteOptions;
        return [$productOptions, $overwriteOptions];
    }

    /**
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper
     * @param array $result
     * @return array
     */
    public function afterMergeProductOptions($initializationHelper, $result)
    {
        if (!$result || !$this->overwriteOptions || !is_array($this->overwriteOptions)) {
            return $result;
        }

        // fix for m2.1.x
        if ($this->helper->isMagento21x()) {
            foreach ($result as $optionIndex => $option) {
                $optionId = $option['option_id'];
                $result[$optionIndex] = $this->overwritTitleValueForM210($optionId, $option, $this->overwriteOptions);

                if (isset($option['values']) && isset($this->overwriteOptions[$optionId]['values'])) {
                    foreach ($option['values'] as $valueIndex => $value) {
                        if (isset($value['option_type_id'])) {
                            $valueId = $value['option_type_id'];
                            $result[$optionIndex]['values'][$valueIndex] = $this->overwritTitleValueForM210(
                                $valueId,
                                $value,
                                $this->overwriteOptions[$optionId]['values']
                            );
                        }
                    }
                }
            }
        }


        if ($this->helper->isDefaultValueEnabled()) {
            foreach ($result as $optionIndex => $option) {
                $optionId = $option['option_id'];
                $result[$optionIndex] = $this->overwriteDefaultTextValue($optionId, $option, $this->overwriteOptions);
            }
        }

        if ($this->helper->isEnabledPerOptionEnabled()) {
            foreach ($result as $optionIndex => $option) {
                $optionId = $option['option_id'];
                $result[$optionIndex] = $this->overwriteIsEnableValue($optionId, $option, $this->overwriteOptions);
            }
        }

        return $result;
    }

    /**
     * Fix for magento 2.1.x
     *
     * @param int $optionId
     * @param array $option
     * @param array $overwriteOptions
     * @return array
     */
    private function overwritTitleValueForM210($optionId, $option, $overwriteOptions)
    {
        if (isset($overwriteOptions[$optionId]['title'])) {
            $overwrite = $overwriteOptions[$optionId]['title'];
            if ($overwrite && isset($option['title']) && isset($option['default_title'])) {
                $option['is_delete_store_title'] = 1;
            }
        }
        return $option;
    }

    /**
     * Overwrite values of fields to default, if there are option id and field name in array overwriteOptions
     *
     * @param int $optionId
     * @param array $option
     * @param array $overwriteOptions
     * @return array
     */
    private function overwriteDefaultTextValue($optionId, $option, $overwriteOptions)
    {
        if (isset($overwriteOptions[$optionId])) {

            foreach ($overwriteOptions[$optionId] as $fieldName => $overwrite) {
                if ($fieldName == 'default_text_area') {
                    $fieldName = 'default_text';
                }
                if ($overwrite && isset($option[$fieldName]) && isset($option['default_' . $fieldName])) {
                    $option[$fieldName] = $option['default_' . $fieldName];
                    if ('default_text' == $fieldName) {
                        $option['is_delete_store_default_text'] = 1;
                    }
                }
            }
        }

        return $option;
    }

    /**
     * Overwrite values of fields to enable, if there are option id and field name in array overwriteOptions
     *
     * @param int $optionId
     * @param array $option
     * @param array $overwriteOptions
     * @return array
     */
    private function overwriteIsEnableValue($optionId, $option, $overwriteOptions)
    {
        if (isset($overwriteOptions[$optionId])) {
            foreach ($overwriteOptions[$optionId] as $fieldName => $overwrite) {
                if ($overwrite && isset($option[$fieldName]) && isset($option['default_' . $fieldName])) {
                    $option[$fieldName] = $option['default_' . $fieldName];
                    if ('is_enable' == $fieldName) {
                        $option['is_delete_store_is_enable'] = 1;
                    }
                }
            }
        }

        return $option;
    }
}
