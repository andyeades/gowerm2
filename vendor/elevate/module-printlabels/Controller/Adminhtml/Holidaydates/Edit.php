<?php


namespace Elevate\PrintLabels\Controller\Adminhtml\Holidaydates;

class Edit extends \Elevate\PrintLabels\Controller\Adminhtml\Holidaydates
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
        $id = $this->getRequest()->getParam('printlabelsholidaydates_id');
        $model = $this->_objectManager->create(\Elevate\PrintLabels\Model\Holidaydates::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Holiday Date no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('elevate_printlabels_holidaydates', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Holiday Date') : __('New Holiday Date'),
            $id ? __('Edit Holiday Date') : __('New Holiday Date')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Holiday Date'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Holiday Date %1', $model->getId()) : __('New Holiday Date'));
        return $resultPage;
    }
}
