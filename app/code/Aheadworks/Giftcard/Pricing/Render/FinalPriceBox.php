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
namespace Aheadworks\Giftcard\Pricing\Render;

use Magento\Framework\Pricing\Render\PriceBox as BasePriceBox;
use Magento\Framework\Pricing\Amount\AmountInterface;

/**
 * Class FinalPriceBox
 *
 * @method bool getUseLinkForAsLowAs()
 * @method bool getDisplayMinimalPrice()
 * @package Aheadworks\Giftcard\Pricing\Render
 */
class FinalPriceBox extends BasePriceBox
{
    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        return $this->wrapResult(parent::_toHtml());
    }

    /**
     * Wrap with standard required container
     *
     * @param string $html
     * @return string
     */
    protected function wrapResult($html)
    {
        return '<div class="price-box ' . $this->getData('css_classes') . '" ' .
            'data-role="priceBox" ' .
            'data-product-id="' . $this->getSaleableItem()->getId() . '"' . '>' . $html . '</div>';
    }

    /**
     * Retrieve minimal price
     *
     * @return AmountInterface
     */
    public function getMinimalPrice()
    {
        return $this->getPrice()->getMinimalPrice();
    }

    /**
     * Retrieve maximal price
     *
     * @return AmountInterface
     */
    public function getMaximalPrice()
    {
        return $this->getPrice()->getMaximalPrice();
    }

    /**
     * Retrieve amount
     *
     * @return AmountInterface
     */
    public function getAmount()
    {
        return $this->getMinimalPrice() ? $this->getMinimalPrice() : $this->getMaximalPrice();
    }

    /**
     * Check is render from-to
     *
     * @return bool
     */
    public function isRenderFromTo()
    {
        return ($this->getMinimalPrice() && $this->getMaximalPrice()
            && $this->getMinimalPrice()->getValue() != $this->getMaximalPrice()->getValue());
    }

    /**
     * Check is render single
     *
     * @return bool
     */
    public function isRenderSingle()
    {
        return $this->getAmount();
    }
}
