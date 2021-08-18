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
namespace Aheadworks\Giftcard\Model\Sales\Totals\Calculator;

use Aheadworks\Giftcard\Model\Product\Type\Giftcard as GiftcardProductType;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Class GiftCardExclude
 *
 * @package Aheadworks\Giftcard\Model\Sales\Totals\Calculator
 */
class GiftCardExclude
{
    /**
     * Exclude gift card row total from grand total
     *
     * @param QuoteItem[]|OrderItem[] $items
     * @param float $baseGrandTotal
     * @param float $grandTotal
     * @return array
     */
    public function calculate($items, $baseGrandTotal, $grandTotal)
    {
        /** @var QuoteItem|OrderItem $item */
        foreach ($items as $item) {
            if ($item->getProductType() == GiftcardProductType::TYPE_CODE) {
                $baseGrandTotal -=
                    $item->getBaseRowTotal()
                    + $item->getBaseTaxAmount()
                    - $item->getBaseDiscountAmount()
                ;
                $grandTotal -=
                    $item->getRowTotal()
                    + $item->getTaxAmount()
                    - $item->getDiscountAmount()
                ;
            }
        }

        return [$baseGrandTotal, $grandTotal];
    }
}
