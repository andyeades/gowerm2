<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Statistics
 *
 * @package Aheadworks\Giftcard\Model\ResourceModel
 */
class Statistics extends AbstractDb
{
    /**
     * @var int|null
     */
    private $storeId;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('aw_giftcard_statistics', 'id');
    }

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    /**
     * Check whether exists statistics with given $productId and $storeId
     *
     * @param int $productId
     * @param int $storeId
     * @return bool
     */
    public function existsStatistics($productId, $storeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'id')
            ->where('product_id = ?', $productId)
            ->where('store_id = ?', $storeId);

        if ($connection->fetchOne($select) === false) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($this->storeId !== null) {
            $select->where('store_id = ?', $this->storeId);
        }
        $this->storeId = null;
        return $select;
    }
}
