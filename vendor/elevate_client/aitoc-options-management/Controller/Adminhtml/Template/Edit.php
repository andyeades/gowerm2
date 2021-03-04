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

class Edit extends \Aitoc\OptionsManagement\Controller\Adminhtml\Template
{
    /**
     * Edit template page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $model = $this->initCurrentTemplate();

        if ($model->getId()) {
            $title = __('Edit Template "%1"', $model->getTitle());
        } else {
            $title = __('New Template');
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Aitoc_OptionsManagement::templates');
        $resultPage->getConfig()->getTitle()->prepend(__('Option Templates'));
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb(__('Manage Option Templates'), __('Manage Templates'));

        return $resultPage;
    }
}
