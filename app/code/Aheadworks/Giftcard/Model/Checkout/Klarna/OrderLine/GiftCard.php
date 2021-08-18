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
namespace Aheadworks\GiftCard\Model\Checkout\Klarna\OrderLine;

use Klarna\Core\Api\BuilderInterface;
use Magento\Quote\Model\Quote;
use Klarna\Core\Model\Checkout\Orderline\AbstractLine;

/**
 * Class GiftCard
 * @package Aheadworks\GiftCard\Model\Checkout\Klarna\OrderLine
 */
class GiftCard extends AbstractLine
{
    /**
     * Checkout item type
     */
    const ITEM_TYPE_AW_GIFT_CARD = 'gift_card';

    /**
     * Discount is a line item collector
     *
     * @var bool
     */
    protected $isTotalCollector = false;

    /**
     * Collect totals process
     *
     * @param BuilderInterface $checkout
     * @return $this
     */
    public function collect(BuilderInterface $checkout)
    {
        /** @var Quote $quote */
        $quote = $checkout->getObject();
        $totals = $quote->getTotals();
        
        if (!is_array($totals) || !isset($totals['aw_giftcard'])) {
            return $this;
        }

        $total = $totals['aw_giftcard'];
        $amount = $total->getValue();
        $value = $this->helper->toApiFloat($amount);
        $checkout->addData([
            'aw_giftcard_unit_price'   => $value,
            'aw_giftcard_tax_rate'     => 0,
            'aw_giftcard_total_amount' => $value,
            'aw_giftcard_tax_amount'   => 0,
            'aw_giftcard_title'        => $total->getTitle(),
            'aw_giftcard_reference'    => $total->getCode()
        ]);
        
        return $this;
    }

    /**
     * Add order details to checkout request
     *
     * @param BuilderInterface $checkout
     * @return $this
     */
    public function fetch(BuilderInterface $checkout)
    {
        if ($checkout->getAwGiftcardTotalAmount()) {
            $checkout->addOrderLine([
                'type'             => self::ITEM_TYPE_AW_GIFT_CARD,
                'reference'        => $checkout->getAwGiftcardReference(),
                'name'             => $checkout->getAwGiftcardTitle(),
                'quantity'         => 1,
                'unit_price'       => $checkout->getAwGiftcardUnitPrice(),
                'tax_rate'         => $checkout->getAwGiftcardTaxRate(),
                'total_amount'     => $checkout->getAwGiftcardTotalAmount(),
                'total_tax_amount' => $checkout->getAwGiftcardTaxAmount(),
            ]);
        }

        return $this;
    }
}
