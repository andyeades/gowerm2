<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\ProgressiveWebApp\Controller\Adminhtml\Notification;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Tigren_ProgressiveWebApp::pwa');
        $resultPage->addBreadcrumb(__('Manage Notifications'), __('Manage Notifications'));
        $resultPage->addBreadcrumb(__('Manage Notifications'), __('Manage Notifications'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Notifications'));
        return $resultPage;
    }

    /**
     * Is the user allowed to view the grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_ProgressiveWebApp::notification');
    }

}
