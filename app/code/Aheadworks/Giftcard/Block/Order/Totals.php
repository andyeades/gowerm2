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
namespace Aheadworks\Giftcard\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\DataObject\Factory;
use Aheadworks\Giftcard\Api\Data\Giftcard\OrderInterface as GiftcardOrderInterface;
use Magento\Sales\Model\Order;

/**
 * Class Totals
 *
 * @package Aheadworks\Giftcard\Block\Order
 */
class Totals extends Template
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Context $context
     * @param Factory $factory
     * @param [] $data
     */
    public function __construct(
        Context $context,
        Factory $factory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->factory = $factory;
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $source = $this->getSource();
        if (!$source || !$source->getExtensionAttributes()
            || ($source->getExtensionAttributes() && !$source->getExtensionAttributes()->getAwGiftcardCodes())
        ) {
            return $this;
        }

        $giftcards = $source->getExtensionAttributes()->getAwGiftcardCodes();
        /** @var GiftcardOrderInterface $giftcard */
        foreach ($giftcards as $giftcard) {
            $this->getParentBlock()->addTotal(
                $this->factory->create(
                    [
                        'code'       => 'aw_giftcard_' . $giftcard->getGiftcardId(),
                        'strong'     => false,
                        'label'      => __('Gift Card (%1)', $giftcard->getGiftcardCode()),
                        'value'      => -$giftcard->getGiftcardAmount(),
                        'base_value' => -$giftcard->getBaseGiftcardAmount(),
                    ]
                )
            );
        }
        return $this;
    }

    /**
     * Retrieve totals source object
     *
     * @return Order|null
     */
    private function getSource()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock) {
            return $parentBlock->getSource();
        }
        return null;
    }
}
