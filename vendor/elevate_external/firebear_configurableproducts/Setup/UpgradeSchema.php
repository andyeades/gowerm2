<?php
/**
 * Copyright © 2016 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$context->getVersion() || version_compare($context->getVersion(), '1.3.0') < 0) {
            /**
             * Create table 'catalog_product_super_link'
             */
            $table = $installer->getConnection()
                ->newTable($installer->getTable('icp_catalog_product_default_super_link'))
                ->addColumn(
                    'link_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Link ID'
                )
                ->addColumn(
                    'product_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Product ID'
                )
                ->addColumn(
                    'parent_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                    'Parent ID'
                )
                ->addIndex(
                    $installer->getIdxName('catalog_product_super_link', ['parent_id']),
                    ['parent_id']
                )
                ->addIndex(
                    $installer->getIdxName(
                        'icp_catalog_product_default_super_link',
                        ['product_id', 'parent_id'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['product_id', 'parent_id'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'icp_catalog_product_default_super_link',
                        'product_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'icp_catalog_product_default_super_link',
                        'parent_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'parent_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Firebear ICP Product Default Super Link Table');

            $installer->getConnection()->createTable($table);
        }
        if (!$context->getVersion() || version_compare($context->getVersion(), '1.4.3') < 0) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('icp_product_attributes'))
                ->addColumn(
                    'item_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Link ID'
                )->addColumn(
                    'product_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Product ID'
                )->addColumn(
                    'color_code',
                    Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => true],
                    'Color Code'
                )->addColumn(
                    'size_code',
                    Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => true],
                    'Size Code'
                )
                ->setComment('Firebear ICP Product Custom Options Table');
            $installer->getConnection()->createTable($table);
        }
        if (!$context->getVersion() || version_compare($context->getVersion(), '1.4.6') < 0) {
            $installer->getConnection()
                ->addColumn(
                    $installer->getTable('icp_product_attributes'),
                    'linked_attributes',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'default' => '0',
                        'comment' => 'Linked attributes ids'
                    ]
                );
        }

        if (!$context->getVersion() || version_compare($context->getVersion(), '1.4.7') < 0) {
            $installer->getConnection()
                ->addColumn(
                    $installer->getTable('icp_product_attributes'),
                    'display_matrix',
                    [
                        'type' => Table::TYPE_INTEGER,
                        'length' => 2,
                        'nullable' => true,
                        'default' => '0',
                        'comment' => 'Use matrix or swatch flag'
                    ]
                );
            if ($installer->getConnection()
                ->tableColumnExists(
                    $installer->getTable('icp_product_attributes'),
                    'color_code'
                )) {
                $installer->getConnection()
                    ->changeColumn(
                        $installer->getTable('icp_product_attributes'),
                        'color_code',
                        'x_axis',
                        [
                            'type' => Table::TYPE_TEXT,
                            'length' => 255,
                            'comment' => 'Attribute code on the x-axis'
                        ]
                    );
            }

            if ($installer->getConnection()
                ->tableColumnExists(
                    $installer->getTable('icp_product_attributes'),
                    'size_code'
                )) {
                $installer->getConnection()
                    ->changeColumn(
                        $installer->getTable('icp_product_attributes'),
                        'size_code',
                        'y_axis',
                        [
                            'type' => Table::TYPE_TEXT,
                            'length' => 255,
                            'comment' => 'Attribute code on the x-axis'
                        ]
                    );
            }
        }
        $installer->endSetup();
    }
}
