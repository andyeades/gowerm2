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
namespace Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Quote;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Aheadworks\Giftcard\Model\Giftcard\Quote;
use Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Quote as ResourceQuote;

/**
 * Class Collection
 *
 * @package Aheadworks\Giftcard\Model\ResourceModel\Giftcard\Quote
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Quote::class, ResourceQuote::class);
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
                    'giftcard_code' => 'giftcard.code',
                    'giftcard_balance' => 'giftcard.balance',
                    'giftcard_product_id' => 'giftcard.product_id'
                ]
            );
        return $this;
    }
}
