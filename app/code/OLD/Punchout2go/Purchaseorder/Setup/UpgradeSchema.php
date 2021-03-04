<?php

namespace Punchout2go\Purchaseorder\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.0.2') < 0) {
            $tableColumnRelationships = $this->getTableColumnRelationships();

            $connection = $setup->getConnection();
            foreach ($tableColumnRelationships as $tableColumnRelationship) {
                $eavTable = $setup->getTable($tableColumnRelationship['table']);

                if (false === $connection->isTableExists($eavTable)) {
                    throw new \Exception("Table {$tableColumnRelationship['table']} does not exist");
                }

                foreach ($tableColumnRelationship['columns'] as $name => $definition) {
                    $connection->addColumn($eavTable, $name, $definition);
                }
            }
        }

        $setup->endSetup();
    }

    /**
     * @return array
     */
    protected function getTableColumnRelationships()
    {
        return [
            [
                'table' => 'quote_item',
                'columns' => [
                    'line_number' => [
                        'type' => Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'PO Line Number',
                    ],
                ]
            ],
            [
                'table' => 'sales_order_item',
                'columns' => [
                    'line_number' => [
                        'type' => Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'PO Line Number',
                    ],
                ]
            ],
            [
                'table' => 'quote',
                'columns' => [
                    'order_request_id' => [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Order Request ID from PunchOut2Go',
                    ],
                ]
            ],
            [
                'table' => 'sales_order',
                'columns' => [
                    'order_request_id' => [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Order Request ID from PunchOut2Go',
                    ],
                ]
            ],
        ];
    }
}