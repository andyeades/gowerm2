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
namespace Aheadworks\Giftcard\Pricing\Price;

use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Catalog\Pricing\Price\ConfiguredPriceInterface;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Wishlist\Model\Item\Option as ItemOption;
use Aheadworks\Giftcard\Api\Data\OptionInterface;

/**
 * Class ConfiguredRegularPrice
 *
 * @package Aheadworks\Giftcard\Pricing\Price
 */
class ConfiguredRegularPrice extends AbstractPrice implements ConfiguredPriceInterface
{
    /**
     * Price type configured
     */
    const PRICE_CODE = ConfiguredPriceInterface::CONFIGURED_REGULAR_PRICE_CODE;

    /**
     * @var null|ItemInterface
     */
    private $item;

    /**
     * @param ItemInterface $item
     * @return $this
     */
    public function setItem(ItemInterface $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Calculate configured regular price
     *
     * @return float
     */
    protected function calculatePrice()
    {
        $value = $this->getProduct()->getPrice();
        if ($this->getProduct()->hasCustomOptions()) {
            /** @var ItemOption $amountOption */
            $amountOption = $this->getProduct()->getCustomOption(OptionInterface::AMOUNT);
            if ($amountOption) {
                $value = $amountOption->getValue() ?? 0;
            }
        }

        return $value;
    }

    /**
     * Price value of product with configured options
     *
     * @return float
     */
    public function getValue()
    {
        return $this->item ? $this->calculatePrice() : max(0, $this->getProduct()->getPrice());
    }
}
