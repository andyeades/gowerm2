<?php


namespace Elevate\LandingPages\Controller\Adminhtml\Index;

class Edit extends \Elevate\LandingPages\Controller\Adminhtml\Index
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
        $id = $this->getRequest()->getParam('landingpage_id');

        $model = $this->_objectManager->create(\Elevate\LandingPages\Model\LandingPage::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Items no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('elevate_landingpages', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Items') : __('New Items'),
            $id ? __('Edit Items') : __('New Items')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Itemss'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Items %1', $model->getId()) : __('New Items'));
        return $resultPage;
    }
}
