<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\ResourceModel\Product;

use Aheadworks\Giftcard\Model\Product\Type\Giftcard as ProductGiftcard;
use Magento\Store\Model\Store;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * Class Collection
 *
 */
class Collection extends ProductCollection
{
    /**
     * @var []
     */
    private $statisticsFields = [
        'purchased_qty',
        'purchased_amount',
        'used_qty',
        'used_amount'
    ];

    /**
     * @var []
     */
    private $linkageTableNames = [];

    /**
     * @var []
     */
    private $storeIds = [];

    /**
     * @var int
     */
    private $websiteId;

    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (in_array($field, $this->statisticsFields) || $field == 'aw_gc_email_template') {
            $this->addFilter($field, $condition, 'public');
            return $this;
        }
        if ($field == 'website_id') {
            $this->websiteId = $condition['eq'];
            $website = $this->_storeManager->getWebsite($this->websiteId);
            // Add default store id, for retrieval used_qty
            $this->storeIds = array_merge($website->getStoreIds(), [Store::DEFAULT_STORE_ID]);
            $this->addWebsiteFilter($website);
            return $this;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * {@inheritdoc}
     */
    public function addAttributeToSort($attribute, $dir = self::SORT_ORDER_ASC)
    {
        if (in_array($attribute, $this->statisticsFields)) {
            $this->joinLinkageTable(
                'aw_giftcard_statistics',
                'entity_id',
                'product_id',
                $attribute,
                $attribute,
                $this->getStatisticsQuery()
            );
            $this->getSelect()->order($attribute . ' ' . $dir);
            return $this;
        }
        return parent::addAttributeToSort($attribute, $dir);
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addFieldToFilter('type_id', ['eq' => ProductGiftcard::TYPE_CODE]);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad()
    {
        foreach ($this->statisticsFields as $field) {
            $this->attachRelationTable(
                'aw_giftcard_statistics',
                'entity_id',
                'product_id',
                $field,
                $field,
                $this->getAggregateStatisticsColumns(),
                ['product_id'],
                true
            );
        }
        $this->attachRelationTable(
            'aw_giftcard_product_entity_templates',
            'entity_id',
            'entity_id',
            'value',
            'aw_gc_email_templates',
            "*",
            null,
            true

        );
        if ($this->websiteId) {
            foreach ($this as $product) {
                $product->setData('website_id', $this->websiteId);
            }
        }
        return parent::_afterLoad();
    }

    /**
     * {@inheritdoc}
     */
    protected function _renderFiltersBefore()
    {
        foreach ($this->statisticsFields as $field) {
            if ($this->getFilter($field)) {
                $this->joinLinkageTable(
                    'aw_giftcard_statistics',
                    'entity_id',
                    'product_id',
                    $field,
                    $field,
                    $this->getStatisticsQuery()
                );
            }
        }

        if ($this->getFilter('aw_gc_email_template')) {
            $this->joinLinkageTable(
                'aw_giftcard_product_entity_templates',
                'entity_id',
                'entity_id',
                'aw_gc_email_template',
                'value',
                null,
                true
            );
        }
        parent::_renderFiltersBefore();
    }

    /**
     * Attach entity table data to collection items
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string $columnNameRelationTable
     * @param string $fieldName
     * @param string $cols
     * @param []|null $groupBy
     * @param bool $useStoreFilter
     * @return void
     */
    private function attachRelationTable(
        $tableName,
        $columnName,
        $linkageColumnName,
        $columnNameRelationTable,
        $fieldName,
        $cols = '*',
        $groupBy = null,
        $useStoreFilter = false
    ) {
        $ids = $this->getColumnValues($columnName);
        if (count($ids)) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from([$tableName . '_table' => $this->getTable($tableName)], $cols)
                ->where($tableName . '_table.' . $linkageColumnName . ' IN (?)', $ids);

            if ($useStoreFilter && $this->storeIds) {
                $select->where($tableName . '_table.store_id IN (?)', $this->storeIds);
            }
            if (is_array($groupBy) && $groupBy) {
                $select->group($groupBy);
            }

            /** @var \Magento\Framework\DataObject $item */
            foreach ($this as $item) {
                $result = '';
                $id = $item->getData($columnName);
                $templates = [];
                $fetchedData = $connection->fetchAll($select);
                foreach ($fetchedData as $dataRow) {
                    $result = $this->processDataRow(
                        $fieldName,
                        $dataRow,
                        $linkageColumnName,
                        $id,
                        $columnNameRelationTable,
                        $result,
                        $templates
                    );
                }
                $item->setData($fieldName, $result);
            }
        }
    }

    /**
     * Join to linkage table if filter is applied
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $linkageColumnName
     * @param string $columnFilter
     * @param string $fieldName
     * @param \Magento\Framework\Db\Select|null $subQuery
     * @param bool $addStore
     * @return void
     */
    private function joinLinkageTable(
        $tableName,
        $columnName,
        $linkageColumnName,
        $columnFilter,
        $fieldName,
        $subQuery = null,
        $addStore = false
    ) {
        $linkageTableName = $tableName . '_table';
        if (!in_array($linkageTableName, $this->linkageTableNames)) {
            $this->linkageTableNames[] = $linkageTableName;
            $table = $subQuery
                ? new \Zend_Db_Expr('(' . $subQuery . ')')
                : $this->getTable($tableName);

            $this->getSelect()->joinLeft(
                [$linkageTableName => $table],
                'e.' . $columnName . ' = ' . $linkageTableName . '.' . $linkageColumnName,
                []
            );

            if ($addStore && $this->storeIds) {
                $this->getSelect()->where('store_id IN (?)', $this->storeIds);
            }
        }
        $this->addFilterToMap($columnFilter, $linkageTableName . '.' . $fieldName);
    }

    /**
     * Retrieve statistics columns with aggregate functions
     *
     * @return []
     */
    private function getAggregateStatisticsColumns()
    {
        $columns = ['product_id' => 'product_id'];
        foreach ($this->statisticsFields as $field) {
            $columns[$field] = 'SUM(' . $field . ')';
        }
        return $columns;
    }

    /**
     * Retrieve statistics query
     *
     * @return \Magento\Framework\Db\Select
     */
    private function getStatisticsQuery()
    {
        $select = $this->getConnection()->select()
            ->from(
                ['awgc_stat_table' => $this->getTable('aw_giftcard_statistics')],
                $this->getAggregateStatisticsColumns()
            )->group(['product_id']);
        if ($this->storeIds) {
            $select->where('store_id IN (?)', $this->storeIds);
        }
        return $select;
    }

    /**
     * Process data row to attach
     *
     * @param string $fieldName
     * @param array $dataRow
     * @param string $linkageColumnName
     * @param int $id
     * @param string $columnNameRelationTable
     * @param string $result
     * @param array $templates
     * @return array|string
     */
    private function processDataRow(
        $fieldName,
        $dataRow,
        $linkageColumnName,
        $id,
        $columnNameRelationTable,
        $result,
        &$templates
    ) {
        if ($dataRow[$linkageColumnName] == $id) {
            switch ($fieldName) {
                case 'aw_gc_email_templates':
                    $templates[] = $dataRow[$columnNameRelationTable];
                    $result = $templates;
                    break;
                default:
                    $result = $dataRow[$columnNameRelationTable];
            }
        }
        return $result;
    }
}
