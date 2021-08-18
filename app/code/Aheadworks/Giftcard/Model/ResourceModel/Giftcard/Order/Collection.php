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
namespace Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Order;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Aheadworks\Giftcard\Model\Giftcard\Order;
use Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Order as ResourceOrder;

/**
 * Class Collection
 *
 * @package Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Order
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Order::class, ResourceOrder::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->joinLeft(
                ['giftcard' => $this->getTable('aw_giftcard')],
                'main_table.giftcard_id = giftcard.id',
                [
                    'giftcard_code' => 'giftcard.code'
                ]
            );
        $this->addFilterToMap('order_id', 'main_table.order_id');
        return $this;
    }
}
