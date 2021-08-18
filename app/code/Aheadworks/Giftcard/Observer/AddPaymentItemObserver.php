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
namespace Aheadworks\Giftcard\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddPaymentItemObserver
 *
 * @package Aheadworks\Giftcard\Observer
 */
class AddPaymentItemObserver implements ObserverInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Payment\Model\Cart $cart */
        $cart = $observer->getEvent()->getCart();
        /** @var \Magento\Payment\Model\Cart\SalesModel\SalesModelInterface $salesModel */
        $salesModel = $cart->getSalesModel();
        $baseGiftCardAmount = $salesModel->getDataUsingMethod('base_aw_giftcard_amount');
        if ($baseGiftCardAmount > 0) {
            $cart->addDiscount((double)abs($baseGiftCardAmount));
        }
    }
}
