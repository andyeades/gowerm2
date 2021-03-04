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
 * @package   mirasvit/module-search
 * @version   1.0.150
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Search\Setup\UpgradeSchema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema1010 implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer  = $setup;
        $connection = $setup->getConnection();
        $tableName = $installer->getTable('catalog_product_entity');

        if ($connection->tableColumnExists($tableName, 'mst_search_weight') === false) {
            $connection->changeColumn(
                $setup->getTable($tableName),
                'search_weight',
                'mst_search_weight',
                [
                    'type'     => Table::TYPE_INTEGER,
                    'length'   => 11,
                    'nullable' => false,
                    'default'  => 0,
                    'comment'  => 'Search Weight',
                ]
            );
        }
    }
}