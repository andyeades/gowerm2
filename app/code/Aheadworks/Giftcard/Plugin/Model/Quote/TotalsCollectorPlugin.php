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
namespace Aheadworks\Giftcard\Plugin\Model\Quote;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\TotalsCollector;
use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;

/**
 * Class TotalsCollectorPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model\Quote
 */
class TotalsCollectorPlugin
{
    /**
     * Reset quote gift card data
     *
     * @param TotalsCollector $subject
     * @param Quote $quote
     */
    public function beforeCollect(TotalsCollector $subject, Quote $quote)
    {
        $quote
            ->setBaseAwGiftcardAmount(0)
            ->setAwGiftcardAmount(0);
        if ($quote->getExtensionAttributes() && $quote->getExtensionAttributes()->getAwGiftcardCodes()) {
            $giftcards = $quote->getExtensionAttributes()->getAwGiftcardCodes();
            /** @var $giftcard GiftcardQuoteInterface */
            foreach ($giftcards as $giftcard) {
                $giftcard
                    ->setBaseGiftcardBalanceUsed(0)
                    ->setGiftcardBalanceUsed(0);
            }
        }
    }
}
