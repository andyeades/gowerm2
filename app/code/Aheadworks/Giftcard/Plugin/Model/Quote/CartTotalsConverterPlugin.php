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

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Magento\Quote\Api\Data\TotalSegmentExtensionFactory;
use Magento\Quote\Model\Cart\TotalsConverter;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Api\Data\TotalSegmentInterface;

/**
 * Class CartTotalsConverterPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model\Quote
 */
class CartTotalsConverterPlugin
{
    /**
     * @var string
     */
    private $code = 'aw_giftcard';

    /**
     * @var TotalSegmentExtensionFactory
     */
    private $totalSegmentExtensionFactory;

    /**
     * @param TotalSegmentExtensionFactory $totalSegmentExtensionFactory
     */
    public function __construct(
        TotalSegmentExtensionFactory $totalSegmentExtensionFactory
    ) {
        $this->totalSegmentExtensionFactory = $totalSegmentExtensionFactory;
    }

    /**
     * Add Gift Card codes to totals
     *
     * @param TotalsConverter $subject
     * @param \Closure $proceed
     * @param Total[] $addressTotals
     * @return TotalSegmentInterface[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundProcess(
        TotalsConverter $subject,
        \Closure $proceed,
        $addressTotals = []
    ) {
        /** @var $totalSegments TotalSegmentInterface[] */
        $totalSegments = $proceed($addressTotals);
        if (!isset($addressTotals[$this->code])) {
            return $totalSegments;
        }

        /** @var \Magento\Quote\Api\Data\TotalSegmentExtensionInterface $totalSegmentExtension */
        $extensionAttributes = $this->totalSegmentExtensionFactory->create();
        $giftcards = [];
        /** @var GiftcardQuoteInterface $giftcard */
        foreach ($addressTotals[$this->code]->getAwGiftcardCodes() as $giftcard) {
            if ($giftcard->isRemove()) {
                continue;
            }
            $giftcards[] = [
                'title' => __('Gift Card (%1)', $giftcard->getGiftcardCode()),
                'value' => -$giftcard->getGiftcardAmount(),
                'giftcard_code' => $giftcard->getGiftcardCode()
            ];
        }
        $extensionAttributes->setAwGiftcardCodes($giftcards);
        $totalSegments[$this->code]->setExtensionAttributes($extensionAttributes);

        return $totalSegments;
    }
}
