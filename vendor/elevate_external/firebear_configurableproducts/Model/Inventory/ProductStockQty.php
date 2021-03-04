<?php
declare(strict_types=1);
/**
 * ProductStockQty
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Model\Inventory;

use Magento\Framework\App\ResourceConnection;

/**
 * Get Product QTY
 * @Todo feature to display each warehouse qty on frontend
 *
 * Class ProductStockQty
 * @package Firebear\ConfigurableProducts\Model\Inventory
 */
class ProductStockQty
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * ProductStockQty constructor.
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param int $productId
     * @param int $stockId
     * @return string
     */
    public function getProductQty(int $productId, int $stockId)
    {
        $select = $this->resourceConnection->getConnection()->select()
            ->from($this->resourceConnection->getTableName('cataloginventory_stock_item'), 'qty')
            ->where('product_id=?', $productId)
            ->where('stock_id', $stockId);
        return $this->resourceConnection->getConnection()->fetchOne($select);
    }
}
