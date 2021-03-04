<?php

namespace Elevate\BundleAdvanced\Observer;

use Elevate\BundleAdvanced\Api\BundleProductManagementInterface;
use Elevate\BundleAdvanced\Plugin\Block\Adminhtml\Catalog\Product\Edit\ButtonSavePlugin;
use Magento\Catalog\Controller\Adminhtml\Product\Save;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Response\RedirectInterface as RedirectFactory;
use Magento\Framework\Registry;

/**
 * Class DuplicateAsSbpAfterProductSave
 * @package Elevate\BundleAdvanced\Observer
 */
class DuplicateAsSbpAfterProductSave implements ObserverInterface
{
    /**
     * @var string
     */
    const AW_SBP_DUPLICATE_PRODUCT = 'elevate_bundleadvanced_duplicate_product';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var BundleProductManagementInterface
     */
    private $bundleProductManagement;

    /**
     * @param Registry $registry
     * @param ManagerInterface $messageManager
     * @param BundleProductManagementInterface $bundleProductManagement
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        Registry $registry,
        ManagerInterface $messageManager,
        RedirectFactory $resultRedirectFactory,
        BundleProductManagementInterface $bundleProductManagement
    ) {
        $this->registry = $registry;
        $this->messageManager = $messageManager;
        $this->bundleProductManagement = $bundleProductManagement;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    /**
     * Duplicate as Simple Bundle Product after product save
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getProduct();
        /** @var Save $controller */
        $controller = $observer->getEvent()->getController();
        $redirectBack = $controller->getRequest()->getParam('back', false);
        if ($redirectBack === ButtonSavePlugin::BACK_ACTION) {
            $duplicateProduct = $this->bundleProductManagement->duplicateAsSimpleBundle($product);
            $this->registry->register(self::AW_SBP_DUPLICATE_PRODUCT, $duplicateProduct);
            $this->messageManager->addSuccessMessage(__('You duplicated the product as Simple Bundle Product.'));
        }
    }
}
