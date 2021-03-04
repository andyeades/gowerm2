<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Controller\Adminhtml\Template;

class Index extends \Aitoc\OptionsManagement\Controller\Adminhtml\Template
{
    /**
     * Product list page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        /**
         * Set active menu item
         */
        $resultPage->setActiveMenu('Aitoc_OptionsManagement::templates');
        $resultPage->getConfig()->getTitle()->prepend(__('Option Templates'));

        /**
         * Add breadcrumb item
         */
        $resultPage->addBreadcrumb(__('Catalog'), __('Catalog'));
        $resultPage->addBreadcrumb(__('Option Templates'), __('Option Templates'));

        return $resultPage;
    }
}
