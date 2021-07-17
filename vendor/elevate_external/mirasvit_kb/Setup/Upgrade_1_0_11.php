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

class Upgrade_1_0_11 implements UpgradeInterface {
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

        $installer->getConnection()
                  ->modifyColumn($installer->getTable('mst_kb_articlesections'), 'asec_created_at', [
                      'type'     => Table::TYPE_TIMESTAMP,
                      'unsigned' => false,
                      'nullable' => false,
                      'default'  => Table::TIMESTAMP_INIT,
                  ]);

        $installer->getConnection()
                  ->modifyColumn($installer->getTable('mst_kb_articlesections'), 'asec_updated_at', [
                      'type'     => Table::TYPE_TIMESTAMP,
                      'unsigned' => false,
                      'nullable' => false,
                      'default'  => Table::TIMESTAMP_INIT,
                  ]);

        $installer->getConnection()
                  ->modifyColumn($installer->getTable('mst_kb_articlesubsections'), 'asecsub_created_at', [
                      'type'     => Table::TYPE_TIMESTAMP,
                      'unsigned' => false,
                      'nullable' => false,
                      'default'  => Table::TIMESTAMP_INIT,
                  ]);

        $installer->getConnection()
                  ->modifyColumn($installer->getTable('mst_kb_articlesubsections'), 'asecsub_updated_at', [
                      'type'     => Table::TYPE_TIMESTAMP,
                      'unsigned' => false,
                      'nullable' => false,
                      'default'  => Table::TIMESTAMP_INIT,
                  ]);
    }
}
