<?php


namespace Elevate\Delivery\Controller\Adminhtml\DeliveryFee;

class Edit extends \Elevate\Delivery\Controller\Adminhtml\DeliveryFee
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('deliveryfee_id');
        $model = $this->_objectManager->create(\Elevate\Delivery\Model\DeliveryFee::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Deliveryfee no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('elevate_delivery_deliveryfee', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Deliveryfee') : __('New Deliveryfee'),
            $id ? __('Edit Deliveryfee') : __('New Deliveryfee')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Deliveryfees'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Deliveryfee %1', $model->getId()) : __('New Deliveryfee'));
        return $resultPage;
    }
}
