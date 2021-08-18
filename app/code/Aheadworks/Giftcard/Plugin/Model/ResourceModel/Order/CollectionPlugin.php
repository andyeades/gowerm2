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
namespace Aheadworks\Giftcard\Plugin\Model\ResourceModel\Order;

use Aheadworks\Giftcard\Plugin\Model\Order\OrderRepositoryPlugin;
use Magento\Sales\Model\ResourceModel\Order\Collection;

/**
 * Class CollectionPlugin
 * @package Aheadworks\Giftcard\Plugin\Model\ResourceModel\Order
 */
class CollectionPlugin
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
     * Add Gift Card data to order objects
     *
     * @param Collection $subject
     * @param $orders
     * @return mixed
     */
    public function afterGetItems(Collection $subject, $orders)
    {
        foreach ($orders as $order) {
            $this->orderRepositoryPlugin->addGiftcardDataToOrder($order);
        }
        return $orders;
    }
}
