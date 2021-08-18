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
namespace Aheadworks\Giftcard\Block\Adminhtml\Order\Create;

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface;
use Magento\Sales\Block\Adminhtml\Order\Create\Totals\DefaultTotals;

/**
 * Class Totals
 *
 * @package Aheadworks\Giftcard\Block\Adminhtml\Order\Create
 */
class Totals extends DefaultTotals
{
    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        $values = [];
        $giftcards = $this->getTotal()->getAwGiftcardCodes();
        /** @var QuoteInterface $giftcard */
        foreach ($giftcards as $giftcard) {
            if ($giftcard->isRemove()) {
                continue;
            }
            $values[] = [
                'code' => $giftcard->getGiftcardCode(),
                'label' => 'Gift Card (%1)',
                'amount' => $giftcard->getGiftcardAmount()
            ];
        }
        return $values;
    }
}
