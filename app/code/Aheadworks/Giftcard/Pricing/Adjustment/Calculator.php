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
namespace Aheadworks\Giftcard\Pricing\Adjustment;

use Aheadworks\Giftcard\Model\Config;
use Magento\Framework\Pricing\Adjustment\Calculator as MagentoFrameworkCalculator;
use Magento\Framework\Pricing\Amount\AmountFactory;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * Class Calculator
 * @package Aheadworks\Giftcard\Pricing\Adjustment
 */
class Calculator extends MagentoFrameworkCalculator
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param AmountFactory $amountFactory
     * @param Config $config
     */
    public function __construct(
        AmountFactory $amountFactory,
        Config $config
    ) {
        parent::__construct($amountFactory);
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getAmount($amount, SaleableInterface $saleableItem, $exclude = null, $context = [])
    {
        $baseAmount = $fullAmount = $amount;
        $previousAdjustments = 0;
        $adjustments = [];
        foreach ($saleableItem->getPriceInfo()->getAdjustments() as $adjustment) {
            $code = $adjustment->getAdjustmentCode();
            $toExclude = false;
            if (!is_array($exclude)) {
                if ($exclude === true || ($exclude !== null && $code === $exclude)) {
                    $toExclude = true;
                }
            } else {
                if (in_array($code, $exclude)) {
                    $toExclude = true;
                }
            }
            if ($adjustment->isIncludedInBasePrice() || $this->config->needToIncludeTaxToGiftcardBalance() && $code == 'tax') {
                $adjust = $adjustment->extractAdjustment($baseAmount, $saleableItem, $context);
                $baseAmount -= $adjust;
                $fullAmount = $adjustment->applyAdjustment($fullAmount, $saleableItem, $context);
                $adjust = $fullAmount - $baseAmount - $previousAdjustments;
                if (!$toExclude) {
                    $adjustments[$code] = $adjust;
                }
            } elseif ($adjustment->isIncludedInDisplayPrice($saleableItem)) {
                if ($toExclude) {
                    continue;
                }
                $newAmount = $adjustment->applyAdjustment($fullAmount, $saleableItem, $context);
                $adjust = $newAmount - $fullAmount;
                $adjustments[$code] = $adjust;
                $fullAmount = $newAmount;
                $previousAdjustments += $adjust;
            }
        }

        return $this->amountFactory->create($fullAmount, $adjustments);
    }
}
