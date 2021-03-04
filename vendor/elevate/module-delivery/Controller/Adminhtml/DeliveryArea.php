<?php


namespace Elevate\Delivery\Controller\Adminhtml;

abstract class DeliveryArea extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Elevate_Delivery::top_level';
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Elevate'), __('Elevate'))
            ->addBreadcrumb(__('Deliveryarea'), __('Deliveryarea'));
        return $resultPage;
    }
}
