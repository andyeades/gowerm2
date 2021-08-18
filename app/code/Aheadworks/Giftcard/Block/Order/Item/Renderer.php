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
namespace Aheadworks\Giftcard\Block\Order\Item;

use Aheadworks\Giftcard\Model\Product\Option\Render as OptionRender;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer;

/**
 * Class Renderer
 *
 * @package Aheadworks\Giftcard\Block\Order\Item
 */
class Renderer extends DefaultRenderer
{
    /**
     * @var OptionRender
     */
    private $optionRender;

    /**
     * @param Context $context
     * @param StringUtils $string
     * @param OptionFactory $productOptionFactory
     * @param OptionRender $optionRender
     * @param [] $data
     */
    public function __construct(
        Context $context,
        StringUtils $string,
        OptionFactory $productOptionFactory,
        OptionRender $optionRender,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $string,
            $productOptionFactory,
            $data
        );
        $this->optionRender = $optionRender;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemOptions()
    {
        return $this->optionRender->render(
            $this->getOrderItem()->getProductOptions(),
            OptionRender::FRONTEND_SECTION
        );
    }
}
