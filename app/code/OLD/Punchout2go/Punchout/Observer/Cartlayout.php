<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Punchout2go\Punchout\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout as ViewLayout;
use Punchout2go\Punchout\Helper\Data as HelperData;
use Punchout2go\Punchout\Model\Session as PUNSession;

class Cartlayout implements ObserverInterface
{
    /**
     * @var \Punchout2go\Punchout\Helper\Data
     */
    protected $helper;

    /**
     * @var \Punchout2go\Punchout\Model\Session
     */
    protected $punchoutSession;

    /** @var \Magento\Framework\View\Layout */
    protected $layout;

    /**
     * Predispatch constructor.
     *
     * @param \Punchout2go\Punchout\Helper\Data   $dataHelper
     * @param \Punchout2go\Punchout\Model\Session $punchoutSession
     * @param \Magento\Framework\View\Layout      $layout
     */
    public function __construct(
        HelperData $dataHelper,
        PUNSession $punchoutSession,
        ViewLayout $layout
    ) {
        $this->layout = $layout;
        $this->helper = $dataHelper;
        $this->punchoutSession = $punchoutSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(EventObserver $observer)
    {
        $this->helper->debug('Layout observer');
        if ($this->punchoutSession->isPunchoutSession()) {
            $this->layout->getUpdate()->addHandle('punchout_checkout_cart_index');
        }
    }
}
