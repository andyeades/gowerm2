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

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Create table 'aitoc_optionsmanagement_template'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('aitoc_optionsmanagement_template')
        )->addColumn(
            'template_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Template ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => true, 'default' => null],
            'Title'
        )->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Sort Order'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Created At'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Updated At'
        )->setComment(
            'Options Template Table'
        );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_option'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option')
            )
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option ID'
            )
            ->addColumn(
                'template_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Template ID'
            )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => true, 'default' => null],
                'Type'
            )
            ->addColumn(
                'is_require',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Is Required'
            )
            ->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                [],
                'SKU'
            )
            ->addColumn(
                'max_characters',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Max Characters'
            )
            ->addColumn(
                'file_extension',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                [],
                'File Extension'
            )
            ->addColumn(
                'image_size_x',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Image Size X'
            )
            ->addColumn(
                'image_size_y',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true],
                'Image Size Y'
            )
            ->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Sort Order'
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option', ['template_id']),
                ['template_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option',
                    'template_id',
                    'aitoc_optionsmanagement_template',
                    'template_id'
                ),
                'template_id',
                $setup->getTable('aitoc_optionsmanagement_template'),
                'template_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Table'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_option_price'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_price')
            )
            ->addColumn(
                'option_price_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Price ID'
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
                'price',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Price'
            )
            ->addColumn(
                'price_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                7,
                ['nullable' => false, 'default' => 'fixed'],
                'Price Type'
            )
            ->addIndex(
                $setup->getIdxName(
                    'aitoc_optionsmanagement_template_option_price',
                    ['option_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_price', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_price',
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
                $setup->getFkName('aitoc_optionsmanagement_template_option_price', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Price Table'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_option_title'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_title')
            )
            ->addColumn(
                'option_title_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Title ID'
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
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Title'
            )
            ->addIndex(
                $setup->getIdxName(
                    'aitoc_optionsmanagement_template_option_title',
                    ['option_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_title', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_title',
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
                $setup->getFkName('aitoc_optionsmanagement_template_option_title', 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Title Table'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_option_type_value'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_type_value')
            )
            ->addColumn(
                'option_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Type ID'
            )
            ->addColumn(
                'option_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option ID'
            )
            ->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                [],
                'SKU'
            )
            ->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Sort Order'
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_type_value', ['option_id']),
                ['option_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_type_value',
                    'option_id',
                    'aitoc_optionsmanagement_template_option',
                    'option_id'
                ),
                'option_id',
                $setup->getTable('aitoc_optionsmanagement_template_option'),
                'option_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Type Value Table'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_option_type_price'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_type_price')
            )
            ->addColumn(
                'option_type_price_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Type Price ID'
            )
            ->addColumn(
                'option_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option Type ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'price',
                \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'default' => '0.0000'],
                'Price'
            )
            ->addColumn(
                'price_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                7,
                ['nullable' => false, 'default' => 'fixed'],
                'Price Type'
            )
            ->addIndex(
                $setup->getIdxName(
                    'aitoc_optionsmanagement_template_option_type_price',
                    ['option_type_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_type_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_type_price', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_type_price',
                    'option_type_id',
                    'aitoc_optionsmanagement_template_option_type_value',
                    'option_type_id'
                ),
                'option_type_id',
                $setup->getTable('aitoc_optionsmanagement_template_option_type_value'),
                'option_type_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_type_value', 'store_id',
                    'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Type Price Table'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_option_type_title'
         */
        $table = $setup->getConnection()
            ->newTable(
                $setup->getTable('aitoc_optionsmanagement_template_option_type_title')
            )
            ->addColumn(
                'option_type_title_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Option Type Title ID'
            )
            ->addColumn(
                'option_type_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Option Type ID'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Title'
            )
            ->addIndex(
                $setup->getIdxName(
                    'aitoc_optionsmanagement_template_option_type_title',
                    ['option_type_id', 'store_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['option_type_id', 'store_id'],
                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->addIndex(
                $setup->getIdxName('aitoc_optionsmanagement_template_option_type_title', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_type_title',
                    'option_type_id',
                    'aitoc_optionsmanagement_template_option_type_value',
                    'option_type_id'
                ),
                'option_type_id',
                $setup->getTable('aitoc_optionsmanagement_template_option_type_value'),
                'option_type_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName(
                    'aitoc_optionsmanagement_template_option_type_title', 'store_id',
                    'store', 'store_id'
                ),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment(
                'Template Option Type Title Table'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_product'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('aitoc_optionsmanagement_template_product')
        )->addColumn(
            'template_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Template ID'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Product ID'
        )->addIndex(
            $setup->getIdxName('aitoc_optionsmanagement_template_product', ['product_id']),
            ['product_id']
        )->addForeignKey(
            $setup->getFkName(
                'aitoc_optionsmanagement_template_product', 'product_id',
                'catalog_product_entity', 'entity_id'
            ),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName(
                'aitoc_optionsmanagement_template_product', 'template_id',
                'aitoc_optionsmanagement_template', 'template_id'
            ),
            'template_id',
            $setup->getTable('aitoc_optionsmanagement_template'),
            'template_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product To Template Linkage Table'
        );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_product_option'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('aitoc_optionsmanagement_template_product_option')
        )->addColumn(
            'template_option_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Template Option ID'
        )->addColumn(
            'product_option_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Product Option ID'
        )->addIndex(
            $setup->getIdxName('aitoc_optionsmanagement_template_product_option', ['product_option_id']),
            ['product_option_id']
        )->addForeignKey(
            $setup->getFkName(
                'aitoc_optionsmanagement_template_product_option', 'template_option_id',
                'aitoc_optionsmanagement_template_option', 'option_id'
            ),
            'template_option_id',
            $setup->getTable('aitoc_optionsmanagement_template_option'),
            'option_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName(
                'aitoc_optionsmanagement_template_product_option', 'product_option_id',
                'catalog_product_option', 'option_id'
            ),
            'product_option_id',
            $setup->getTable('catalog_product_option'),
            'option_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product Option To Template Option Linkage Table'
        );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'aitoc_optionsmanagement_template_product_option_type_value'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('aitoc_optionsmanagement_template_product_option_type_value')
        )->addColumn(
            'template_option_type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Template Option ID'
        )->addColumn(
            'product_option_type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Product Option ID'
        )->addIndex(
            $setup->getIdxName('aitoc_optionsmanagement_template_product_option_type_value', ['product_option_type_id']),
            ['product_option_type_id']
        )->addForeignKey(
            $setup->getFkName(
                'aitoc_optionsmanagement_template_product_option_type_value', 'template_option_type_id',
                'aitoc_optionsmanagement_template_option_type_value', 'option_type_id'
            ),
            'template_option_type_id',
            $setup->getTable('aitoc_optionsmanagement_template_option_type_value'),
            'option_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $setup->getFkName(
                'aitoc_optionsmanagement_template_product_option_type_value', 'product_option_type_id',
                'catalog_product_option_type_value', 'option_type_id'
            ),
            'product_option_type_id',
            $setup->getTable('catalog_product_option_type_value'),
            'option_type_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Product Option Type Value To Template Option Type Value Linkage Table'
        );
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
