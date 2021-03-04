<?php

namespace Elevate\Delivery\Controller\Adminhtml\DeliveryProductImport;

use Magento\Framework\Controller\ResultFactory;

class Importdata extends \Magento\Backend\App\Action
{
    private $coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {

        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $rowData = $this->_objectManager->create('Elevate\Model\Locator');
        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Import Locator Data'));
        return $resultPage;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_Delivery::DeliveryProductImport');
    }
}
