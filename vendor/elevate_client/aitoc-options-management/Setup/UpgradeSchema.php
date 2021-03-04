<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $this->addDefaultValue($setup);
        }
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     */
    private function addDefaultValue(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('aitoc_optionsmanagement_template_option_type_value'),
            'default_value',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'length' => '1',
                'default' => 0,
                'comment' => 'Default Value',
                'after' => 'sort_order'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('catalog_product_option_type_value'),
            'default_value',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'length' => '1',
                'default' => 0,
                'comment' => 'Default Value',
                'after' => 'sort_order'
            ]
        );


        /**
         * Create table 'aitoc_optionsmanagement_template_option_default'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_default')
            )
            ->addColumn(
                'option_default_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Default ID'
            )
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'default_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Default Text'
            )
            ->addIndex(
                $setup->getIdxName(
                    'aitoc_optionsmanagement_template_option_default',
                    ['option_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_default', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_default',
                    'option_id',
                    'aitoc_optionsmanagement_template_option',
                    'option_id'
                ),
                'option_id',
                $setup->getTable('aitoc_optionsmanagement_template_option'),
                'option_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('aitoc_optionsmanagement_template_option_default', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Default Table'
            );
        $setup->getConnection()->createTable($table);


        /**
         * Create table 'catalog_product_option_default'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('catalog_product_option_default')
            )
            ->addColumn(
                'option_default_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Default ID'
            )
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'default_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Default Text'
            )
            ->addIndex(
                $setup->getIdxName(
                    'catalog_product_option_default',
                    ['option_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('catalog_product_option_default', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'catalog_product_option_default',
                    'option_id',
                    'catalog_product_option',
                    'option_id'
                ),
                'option_id',
                $setup->getTable('catalog_product_option'),
                'option_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('catalog_product_option_default', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Catalog Option Default Table'
            );
        $setup->getConnection()->createTable($table);


        $setup->getConnection()->addColumn(
            $setup->getTable('aitoc_optionsmanagement_template'),
            'is_replace_product_sku',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'length' => '1',
                'default' => 0,
                'comment' => 'Is Replace Product SKU'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('catalog_product_entity'),
            'is_replace_product_sku',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'length' => '1',
                'default' => 0,
                'comment' => 'Is Replace Product SKU'
            ]
        );


        /**
         * Create table 'aitoc_optionsmanagement_template_option_is_enable'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_is_enable')
            )
            ->addColumn(
                'option_is_enable_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Is Enable ID'
            )
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'is_enable',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                1,
                ['nullable' => false, 'default' => 1],
                'Is Enable'
            )
            ->addIndex(
                $setup->getIdxName(
                    'aitoc_optionsmanagement_template_option_is_enable',
                    ['option_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_is_enable', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_is_enable',
                    'option_id',
                    'aitoc_optionsmanagement_template_option',
                    'option_id'
                ),
                'option_id',
                $setup->getTable('aitoc_optionsmanagement_template_option'),
                'option_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('aitoc_optionsmanagement_template_option_is_enable', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Is Enable Table'
            );
        $setup->getConnection()->createTable($table);


        /**
         * Create table 'catalog_product_option_is_enable'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('catalog_product_option_is_enable')
            )
            ->addColumn(
                'option_is_enable_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Is Enable ID'
            )
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'is_enable',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                1,
                ['nullable' => false, 'default' => 1],
                'Is Enable'
            )
            ->addIndex(
                $setup->getIdxName(
                    'catalog_product_option_is_enable',
                    ['option_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('catalog_product_option_is_enable', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'catalog_product_option_is_enable',
                    'option_id',
                    'catalog_product_option',
                    'option_id'
                ),
                'option_id',
                $setup->getTable('catalog_product_option'),
                'option_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('catalog_product_option_is_enable', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Catalog Option Is Enable Table'
            );
        $setup->getConnection()->createTable($table);
    }
}
