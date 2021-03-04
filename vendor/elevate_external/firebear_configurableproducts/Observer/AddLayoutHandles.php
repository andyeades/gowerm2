<?php
namespace Firebear\ConfigurableProducts\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session as CustomerSession;
use \Firebear\ConfigurableProducts\Helper\Data as icpHelper;

class AddLayoutHandles implements ObserverInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var icpHelper
     */
    protected $icpHelper;

    /**
     * AddLayoutHandles constructor.
     * @param CustomerSession $customerSession
     * @param icpHelper $icpHelper
     */
    public function __construct(CustomerSession $customerSession, icpHelper $icpHelper)
    {
        $this->customerSession = $customerSession;
        $this->icpHelper = $icpHelper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->icpHelper->hidePrice()) {
            $layout = $observer->getEvent()->getLayout();
            $layoutUpdate = $layout->getUpdate();
            $layoutUpdate->addHandle('customer_logged_out');
        }
    }
}
