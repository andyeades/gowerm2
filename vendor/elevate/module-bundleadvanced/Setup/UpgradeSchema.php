<?php

namespace Elevate\BundleAdvanced\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('catalog_product_bundle_option'),
                'min_qty',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length'    => null,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'Minimum Qty'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('catalog_product_bundle_option'),
                'max_qty',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length'    => null,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => null,
                    'comment' => 'Maximum Qty'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('catalog_product_bundle_option'),
                'is_lease_machine',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length'    => 1,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'Is Lease Machine'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('catalog_product_bundle_option'),
                'default_option_text',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'    => 255,
                    'nullable' => true,
                    'comment' => 'Default Option Text'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('catalog_product_bundle_option'),
                'option_tooltip',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'    => '2M',
                    'nullable' => true,
                    'comment' => 'Option Tooltip'
                ]
            );


        }

        $installer->endSetup();
    }
}