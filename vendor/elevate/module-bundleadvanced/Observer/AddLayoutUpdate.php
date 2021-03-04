<?php

namespace Elevate\BundleAdvanced\Observer;

use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ObserverInterface;
use Elevate\BundleAdvanced\Model\Product\Layout\Handle\Resolver as HandleResolver;

/**
 * Class AddLayoutUpdate
 * @package Elevate\BundleAdvanced\Observer
 */
class AddLayoutUpdate implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @var HandleResolver
     */
    private $handleResolver;

    /**
     * @param RequestInterface $request
     * @param ProductChecker $productChecker
     * @param HandleResolver $handleResolver
     */
    public function __construct(
        RequestInterface $request,
        ProductChecker $productChecker,
        HandleResolver $handleResolver
    ) {
        $this->request = $request;
        $this->productChecker = $productChecker;
        $this->handleResolver = $handleResolver;
    }

    /**
     * Make layout load additional handler when in private sales mode
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $handler = $this->handleResolver->resolve($this->request->getFullActionName());
        if ($handler && $this->productChecker->isSimpleBundleProduct()) {
            /** @var \Magento\Framework\View\LayoutInterface $layout */
            $layout = $observer->getEvent()->getLayout();
            $layout->getUpdate()->addHandle($handler);
        }
    }
}
