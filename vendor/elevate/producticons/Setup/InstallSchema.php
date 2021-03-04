<?php

namespace Elevate\ProductIcons\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

  public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
  {
    $installer = $setup;
    $installer->startSetup();
    if (!$installer->tableExists('elevate_producticons')) {
      $table = $installer->getConnection()->newTable(
        $installer->getTable('elevate_producticons')
      )
        ->addColumn(
          'icon_id',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          null,
          [
            'identity' => true,
            'nullable' => false,
            'primary'  => true,
            'unsigned' => true,
          ],
          'Icon Id'
        )
        ->addColumn(
          'icon_url',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          255,
          ['nullable => false'],
          'Icon Url'
        )
        ->addColumn(
          'icon_title',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          255,
          ['nullable => false'],
          'Icon Title'
        )
        ->addColumn(
          'icon_blk_position',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          4,
          [],
          'Icon Block Position'
        )
        ->addColumn(
          'icon_blk_short_desc_enabled',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          1,
          [],
          'Short Description Enabled'
        )
        ->addColumn(
          'icon_blk_description_enabled',
          \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
          4,
          [],
          'Description Enabled'
        )
        ->addColumn(
          'icon_start_date',
          \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
          null,
          ['nullable' => false],
          'Menu Start Date'
        )->addColumn(
          'icon_end_date',
          \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
          null,
          ['nullable' => false],
          'Menu End Date')
        ->addColumn(
          'icon_blk_short_description',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          '32k',
          [],
          'Short Description'
        )
        ->addColumn(
          'icon_blk_description',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          '64k',
          [],
          'Description'
        )
        ->addColumn(
        'icon_blk_style',
        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        '2k',
        [],
        'Icon CSS Style'
      )
        ->setComment('Elevate Product Icons');
      $installer->getConnection()->createTable($table);
    }
    $installer->endSetup();
  }
}