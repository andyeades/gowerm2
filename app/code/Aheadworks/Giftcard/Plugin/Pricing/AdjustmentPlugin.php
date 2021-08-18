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
namespace Aheadworks\Giftcard\Plugin\Pricing;

use Aheadworks\Giftcard\Model\Config;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Tax\Pricing\Adjustment;
use Magento\Catalog\Helper\Data as CatalogHelper;

/**
 * Class AdjustmentPlugin
 * @package Aheadworks\Giftcard\Plugin\Pricing
 */
class AdjustmentPlugin
{
    /**
     * @var CatalogHelper
     */
    private $catalogHelper;

    /**
     * @var bool
     */
    private $needToInclPrice;

    /**
     * @param CatalogHelper $catalogHelper
     * @param Config $config
     */
    public function __construct(
        CatalogHelper $catalogHelper,
        Config $config
    ) {
        $this->catalogHelper = $catalogHelper;
        $this->needToInclPrice = $config->needToIncludeTaxToGiftcardBalance();
    }

    /**
     * Extract adjustment amount from the given amount value
     *
     * @param Adjustment $adjustment
     * @param float $result
     * @param float $amount
     * @param SaleableInterface $saleableItem
     * @param null|array $context
     * @return float
     */
    public function afterExtractAdjustment(
        Adjustment $adjustment,
        $result,
        $amount,
        SaleableInterface $saleableItem,
        $context = []
    ) {
        if ($saleableItem->getTypeId() == Giftcard::TYPE_CODE) {
            $adjustedAmount = $this->catalogHelper->getTaxPrice(
                $saleableItem,
                $amount,
                false,
                null,
                null,
                null,
                null,
                $this->needToInclPrice ? $this->needToInclPrice : null,
                false
            );
            $result = $amount - $adjustedAmount;
        }
        return $result;
    }

    /**
     * Apply adjustment amount and return result value
     *
     * @param Adjustment $adjustment
     * @param float $result
     * @param float $amount
     * @param SaleableInterface $saleableItem
     * @param null|array $context
     * @return float
     */
    public function afterApplyAdjustment(
        Adjustment $adjustment,
        $result,
        $amount,
        SaleableInterface $saleableItem,
        $context = []
    ) {
        if ($saleableItem->getTypeId() == Giftcard::TYPE_CODE) {
            $result = $this->catalogHelper->getTaxPrice(
                $saleableItem,
                $amount,
                true,
                null,
                null,
                null,
                null,
                $this->needToInclPrice ? $this->needToInclPrice : null,
                false
            );
        }
        return $result;
    }
}
