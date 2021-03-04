<?php


namespace Elevate\Delivery\Controller\Adminhtml\DeliveryProducts;

class Edit extends \Elevate\Delivery\Controller\Adminhtml\DeliveryProducts
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
        $id = $this->getRequest()->getParam('deliveryproducts_id');
        $model = $this->_objectManager->create(\Elevate\Delivery\Model\DeliveryProducts::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Deliveryproducts no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('elevate_delivery_deliveryproducts', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Deliveryproducts') : __('New Deliveryproducts'),
            $id ? __('Edit Deliveryproducts') : __('New Deliveryproducts')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Deliveryproductss'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Deliveryproducts %1', $model->getId()) : __('New Deliveryproducts'));
        return $resultPage;
    }
}
