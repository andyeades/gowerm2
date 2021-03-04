<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value;

/**
 * Template option values collection
 *
 * @api
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Aitoc\OptionsManagement\Model\Template\Option\Value::class,
            \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Value::class
        );
    }

    /**
     * Add price, title to result
     *
     * @param int $storeId
     * @return $this
     */
    public function getValues($storeId = 0)
    {
        $this->addPriceToResult($storeId)->addTitleToResult($storeId);

        return $this;
    }

    /**
     * Add titles to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addTitlesToResult($storeId)
    {
        $connection = $this->getConnection();
        $optionTypePriceTable = $this->getTable('aitoc_optionsmanagement_template_option_type_price');
        $optionTitleTable = $this->getTable('aitoc_optionsmanagement_template_option_type_title');
        $priceExpr = $connection->getCheckSql(
            'store_value_price.price IS NULL',
            'default_value_price.price',
            'store_value_price.price'
        );
        $priceTypeExpr = $connection->getCheckSql(
            'store_value_price.price_type IS NULL',
            'default_value_price.price_type',
            'store_value_price.price_type'
        );
        $titleExpr = $connection->getCheckSql(
            'store_value_title.title IS NULL',
            'default_value_title.title',
            'store_value_title.title'
        );
        $joinExprDefaultPrice = 'default_value_price.option_type_id = main_table.option_type_id AND ' .
            $connection->quoteInto('default_value_price.store_id = ?', \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        $joinExprStorePrice = 'store_value_price.option_type_id = main_table.option_type_id AND ' .
            $connection->quoteInto('store_value_price.store_id = ?', $storeId);

        $joinExprTitle = 'store_value_title.option_type_id = main_table.option_type_id AND ' . $connection->quoteInto(
            'store_value_title.store_id = ?',
            $storeId
        );

        $this->getSelect()->joinLeft(
            ['default_value_price' => $optionTypePriceTable],
            $joinExprDefaultPrice,
            ['default_price' => 'price', 'default_price_type' => 'price_type']
        )->joinLeft(
            ['store_value_price' => $optionTypePriceTable],
            $joinExprStorePrice,
            [
                'store_price' => 'price',
                'store_price_type' => 'price_type',
                'price' => $priceExpr,
                'price_type' => $priceTypeExpr
            ]
        )->join(
            ['default_value_title' => $optionTitleTable],
            'default_value_title.option_type_id = main_table.option_type_id',
            ['default_title' => 'title']
        )->joinLeft(
            ['store_value_title' => $optionTitleTable],
            $joinExprTitle,
            ['store_title' => 'title', 'title' => $titleExpr]
        )->where(
            'default_value_title.store_id = ?',
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );

        return $this;
    }

    /**
     * Add title result
     *
     * @param int $storeId
     * @return $this
     */
    public function addTitleToResult($storeId)
    {
        $optionTitleTable = $this->getTable('aitoc_optionsmanagement_template_option_type_title');
        $titleExpr = $this->getConnection()->getCheckSql(
            'store_value_title.title IS NULL',
            'default_value_title.title',
            'store_value_title.title'
        );

        $joinExpr = 'store_value_title.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto('store_value_title.store_id = ?', $storeId);
        $this->getSelect()->join(
            ['default_value_title' => $optionTitleTable],
            'default_value_title.option_type_id = main_table.option_type_id',
            ['default_title' => 'title']
        )->joinLeft(
            ['store_value_title' => $optionTitleTable],
            $joinExpr,
            ['store_title' => 'title', 'title' => $titleExpr]
        )->where(
            'default_value_title.store_id = ?',
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );

        return $this;
    }

    /**
     * Add price to result
     *
     * @param int $storeId
     * @return $this
     */
    public function addPriceToResult($storeId)
    {
        $optionTypeTable = $this->getTable('aitoc_optionsmanagement_template_option_type_price');
        $priceExpr = $this->getConnection()->getCheckSql(
            'store_value_price.price IS NULL',
            'default_value_price.price',
            'store_value_price.price'
        );
        $priceTypeExpr = $this->getConnection()->getCheckSql(
            'store_value_price.price_type IS NULL',
            'default_value_price.price_type',
            'store_value_price.price_type'
        );

        $joinExprDefault = 'default_value_price.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto(
                'default_value_price.store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
        $joinExprStore = 'store_value_price.option_type_id = main_table.option_type_id AND ' .
            $this->getConnection()->quoteInto('store_value_price.store_id = ?', $storeId);
        $this->getSelect()->joinLeft(
            ['default_value_price' => $optionTypeTable],
            $joinExprDefault,
            ['default_price' => 'price', 'default_price_type' => 'price_type']
        )->joinLeft(
            ['store_value_price' => $optionTypeTable],
            $joinExprStore,
            [
                'store_price' => 'price',
                'store_price_type' => 'price_type',
                'price' => $priceExpr,
                'price_type' => $priceTypeExpr
            ]
        );

        return $this;
    }

    /**
     * Add option filter
     *
     * @param array $optionIds
     * @param int $storeId
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getValuesByOption($optionIds, $storeId = null)
    {
        if (!is_array($optionIds)) {
            $optionIds = [$optionIds];
        }

        return $this->addFieldToFilter('main_table.option_type_id', ['in' => $optionIds]);
    }

    /**
     * Add option to filter
     *
     * @param array|\Aitoc\OptionsManagement\Model\Template\Option|int $option
     * @return $this
     */
    public function addOptionToFilter($option)
    {
        if (empty($option)) {
            $this->addFieldToFilter('option_id', '');
        } elseif (is_array($option)) {
            $this->addFieldToFilter('option_id', ['in' => $option]);
        } elseif ($option instanceof \Aitoc\OptionsManagement\Model\Template\Option) {
            $this->addFieldToFilter('option_id', $option->getId());
        } else {
            $this->addFieldToFilter('option_id', $option);
        }

        return $this;
    }
}
