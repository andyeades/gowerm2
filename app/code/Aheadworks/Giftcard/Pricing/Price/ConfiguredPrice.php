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

use Aheadworks\Giftcard\Api\Data\OptionInterface;
use Magento\Catalog\Pricing\Price\ConfiguredPrice as CatalogConfiguredPrice;

/**
 * Class ConfiguredPrice
 *
 * @package Aheadworks\Giftcard\Pricing\Price
 */
class ConfiguredPrice extends CatalogConfiguredPrice
{
    /**
     * Calculate configured price
     *
     * @return float
     */
    protected function calculatePrice()
    {
        $value = $this->getProduct()->getPrice();
        if ($this->getProduct()->hasCustomOptions()) {
            /** @var \Magento\Wishlist\Model\Item\Option $customOption */
            $amountOption = $this->getProduct()->getCustomOption(OptionInterface::AMOUNT);
            if ($amountOption) {
                $value = ($amountOption->getValue() ? $amountOption->getValue() : 0.);
            }
        }
        $value += parent::getOptionsValue();
        return $value;
    }

    /**
     * Price value of product with configured options
     *
     * @return bool|float
     */
    public function getValue()
    {
        return $this->item ? $this->calculatePrice() : max(0, $this->getBasePrice()->getValue());
    }
}
