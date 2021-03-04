<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */

namespace Mirasvit\Kb\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class Upgrade_1_0_10 implements UpgradeInterface {
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function upgrade(
        SchemaSetupInterface $installer,
        ModuleContextInterface $context
    ) {
        $installer->getConnection()->addColumn(
            $installer->getTable('mst_kb_article'), 'article_header_image', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '64K',
            'comment'  => 'Article Header Image',
        ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('mst_kb_article'), 'article_template_option', [
            'unsigned' => true,
            'nullable' => true,
            'size'     => 6,
            'default'  => 1,
            'type'     => Table::TYPE_SMALLINT,
            'comment'  => 'Article Template Option'
        ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('mst_kb_article'), 'related_article_ids', [
            'type'     => Table::TYPE_TEXT,
            'nullable' => true,
            'length'   => '32K',
            'comment'  => 'Related Articles ids',
        ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('mst_kb_article'), 'show_author', [
            'unsigned' => true,
            'nullable' => true,
            'size'     => 6,
            'default'  => 0,
            'type'     => Table::TYPE_SMALLINT,
            'comment'  => 'Show Authoer'
        ]
        );

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_kb_articlesections')
        )->addColumn('articlesection_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, [
                                       'unsigned' => false,
                                       'nullable' => false,
                                       'identity' => true,
                                       'primary'  => true
                                   ], 'Article Section'
            )->addColumn('parentarticle_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, [
                                      'unsigned' => false,
                                      'nullable' => false
                                  ], 'Parent Article Id'
            )->addColumn('asec_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 512, ['nullable' => false], 'Article Name'
            )->addColumn('asec_value', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,  null, [
                                'nullable' => false,
                                'length'   => '32K'
                            ], 'Article Value'
            )->addColumn('asec_position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, [
                                   'unsigned' => false,
                                   'nullable' => false
                               ], 'Asec Position'
            )->addColumn('asec_created_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, NULL, ['nullable' => true], 'Created At'
            )->addColumn('asec_updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, NULL, ['nullable' => true], 'Updated At'
            )->addColumn('asec_is_active', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                                    'unsigned' => false,
                                    'nullable' => false
                                ], 'Is Active'
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_kb_articlesubsections')
        )->addColumn('articlesubsection_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, [
                                          'unsigned' => false,
                                          'nullable' => false,
                                          'identity' => true,
                                          'primary'  => true
                                      ], 'Article Sub Section'
            )->addColumn('parentarticlesection_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, [
                                             'unsigned' => false,
                                             'nullable' => false
                                         ], 'Parent Article Section Id'
            )->addColumn('asecsub_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 512, ['nullable' => false], 'Article Sub Section Name'
            )->addColumn('asecsub_value', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, NULL, [
                                   'nullable' => false,
                                   'length'   => '32K'
                               ], 'Article Sub Section Value'
            )->addColumn('asecsub_position', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, [
                                      'unsigned' => false,
                                      'nullable' => false
                                  ], 'Asec Sub Position'
            )->addColumn('asecsub_created_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, NULL, ['nullable' => true], 'Created At'
            )->addColumn('asecsub_updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, NULL, ['nullable' => true], 'Updated At'
            )->addColumn('asecsub_is_active', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1, [
                                       'unsigned' => false,
                                       'nullable' => false
                                   ], 'Is Active'
            );
        $installer->getConnection()->createTable($table);
    }
}
