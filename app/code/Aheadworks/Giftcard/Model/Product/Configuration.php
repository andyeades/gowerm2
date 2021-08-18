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
namespace Aheadworks\Giftcard\Model\Product;

use Magento\Catalog\Helper\Product\Configuration\ConfigurationInterface;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Quote\Model\Quote\Item\Option as ItemOption;
use Magento\Quote\Model\Quote\Address\Item as AddressItem;
use Aheadworks\Giftcard\Model\Product\Option\Render as OptionRender;

/**
 * Class Configuration
 *
 * @package Aheadworks\Giftcard\Model\Product
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var OptionRender
     */
    private $optionRender;

    /**
     * @param OptionRender $optionRender
     */
    public function __construct(
        OptionRender $optionRender
    ) {
        $this->optionRender = $optionRender;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(ItemInterface $item)
    {
        $options = [];

        if ($item instanceof AddressItem) {
            $item = $item->getQuoteItem();
        }

        /** @var ItemOption $option */
        foreach ($item->getOptionsByCode() as $option) {
            $options[$option->getCode()] = $option->getValue();
        }
        return $this->optionRender->render($options, OptionRender::FRONTEND_SECTION);
    }
}
