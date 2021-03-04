<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Model\ResourceModel\Product\Option;

class Collection
{
    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Collection constructor.
     *
     * @param \Aitoc\OptionsManagement\Helper\Data $helper
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Aitoc\OptionsManagement\Helper\Data $helper,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->helper = $helper;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $collection
     * @param int $storeId
     * @return array
     */
    public function beforeAddValuesToResult($collection, $storeId = null)
    {
        if (!$this->coreRegistry->registry('option_import_mode')) {
            $this->addTemplateToResult($collection);
        }

        if ($this->helper->isDefaultValueEnabled()) {
            $this->addDefaultTextToResult($collection, $storeId);
        }

        if ($this->helper->isEnabledPerOptionEnabled()) {
            $this->addIsEnableToResult($collection, $storeId);
        }
        return [$storeId];
    }

    /**
     * Add tempolate info to result
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $collection
     */
    protected function addTemplateToResult($collection)
    {
        $collection->getSelect()->joinLeft(
            ['template_product_option' => $collection->getTable('aitoc_optionsmanagement_template_product_option')],
            'template_product_option.product_option_id = main_table.option_id',
            ['template_option_id']
        );

        $collection->getSelect()->joinLeft(
            ['template_option' => $collection->getTable('aitoc_optionsmanagement_template_option')],
            'template_option.option_id = template_product_option.template_option_id',
            ['template_id']
        )->joinLeft(
            ['template' => $collection->getTable('aitoc_optionsmanagement_template')],
            'template.template_id = template_option.template_id',
            ['template_title' => 'title']
        );
    }

    /**
     * Add default text to result
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $collection
     * @param int $storeId
     */
    protected function addDefaultTextToResult($collection, $storeId)
    {
        $productOptionDefaultTextTable = $collection->getTable('catalog_product_option_default');
        $connection = $collection->getConnection();
        $defaultTextExpr = $connection->getCheckSql(
            'store_option_default_text.default_text IS NULL',
            'default_option_default_text.default_text',
            'store_option_default_text.default_text'
        );

        $collection->getSelect()->joinLeft(
            ['default_option_default_text' => $productOptionDefaultTextTable],
            'default_option_default_text.option_id = main_table.option_id AND '
            . 'default_option_default_text.store_id = ' . \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ['default_default_text' => 'default_text']
        )->joinLeft(
            ['store_option_default_text' => $productOptionDefaultTextTable],
            'store_option_default_text.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_default_text.store_id = ?',
                $storeId
            ),
            ['store_default_text' => 'default_text', 'default_text' => $defaultTextExpr]
        );
    }

    /**
     * Add is_enable to result
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $collection
     * @param int $storeId
     */
    protected function addIsEnableToResult($collection, $storeId)
    {
        $productOptionIsEnableTable = $collection->getTable('catalog_product_option_is_enable');
        $connection = $collection->getConnection();
        $isEnableExpr = $connection->getCheckSql(
            'store_option_is_enable.is_enable IS NULL',
            $connection->getCheckSql(
                'default_option_is_enable.is_enable IS NULL',
                '1',
                'default_option_is_enable.is_enable'
            ),
            'store_option_is_enable.is_enable'
        );

        $collection->getSelect()->joinLeft(
            ['default_option_is_enable' => $productOptionIsEnableTable],
            'default_option_is_enable.option_id = main_table.option_id AND '
            . 'default_option_is_enable.store_id = ' . \Magento\Store\Model\Store::DEFAULT_STORE_ID,
            ['default_is_enable' => 'is_enable']
        )->joinLeft(
            ['store_option_is_enable' => $productOptionIsEnableTable],
            'store_option_is_enable.option_id = main_table.option_id AND ' . $connection->quoteInto(
                'store_option_is_enable.store_id = ?',
                $storeId
            ),
            ['store_is_enable' => 'is_enable', 'is_enable' => $isEnableExpr]
        );
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $collection
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $result
     * @return \Magento\Catalog\Model\ResourceModel\Product\Option\Collection
     */
    public function afterAddValuesToResult($collection, $result)
    {
        $this->addTemplateOptionIdToValues($collection);
        return $collection;
    }

    /**
     * Add tempolate option id to values
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\Collection $collection
     */
    protected function addTemplateOptionIdToValues($collection)
    {
        foreach ($collection as $option) {
            if ($option->getTemplateOptionId() && $option->getValues()) {
                foreach($option->getValues() as $value) {
                    $value->setTemplateOptionId($option->getTemplateOptionId());
                }
            }
        }
    }
}
