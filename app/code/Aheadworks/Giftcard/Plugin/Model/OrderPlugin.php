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
namespace Aheadworks\Giftcard\Plugin\Model;

use Magento\Sales\Model\Order;
use Aheadworks\Giftcard\Plugin\Model\Order\OrderRepositoryPlugin;

/**
 * Class OrderPlugin
 *
 * @package Aheadworks\Giftcard\Plugin\Model
 */
class OrderPlugin
{
    /**
     * @var OrderRepositoryPlugin
     */
    private $orderRepositoryPlugin;

    /**
     * @param OrderRepositoryPlugin $orderRepositoryPlugin
     */
    public function __construct(
        OrderRepositoryPlugin $orderRepositoryPlugin
    ) {
        $this->orderRepositoryPlugin = $orderRepositoryPlugin;
    }

    /**
     * Add Gift Card data to order object
     *
     * @param Order $subject
     * @param Order $order
     * @return Order
     */
    public function afterLoad($subject, $order)
    {
        return $this->orderRepositoryPlugin->addGiftcardDataToOrder($order);
    }

    /**
     * Set forced canCreditmemo flag
     *
     * @param Order $order
     * @return void
     */
    public function beforeCanCreditmemo($order)
    {
        if (!$order->canUnhold() && !$order->isCanceled() && $order->getState() != Order::STATE_CLOSED
            && $order->getAwGiftcardInvoiced() - $order->getAwGiftcardRefunded() > 0
        ) {
            $order->setForcedCanCreditmemo(true);
        }
    }
}
