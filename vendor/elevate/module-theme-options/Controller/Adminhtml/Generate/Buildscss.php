<?php

namespace Elevate\Themeoptions\Controller\Adminhtml\Generate;

class Buildscss extends \Magento\Backend\App\Action
{

    protected $generateScssHelper;

    protected $resultPageFactory;

    protected $coreRegistry;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     *
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Elevate\Themeoptions\Helper\GenerateScss $generateScssHelper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->generateScssHelper = $generateScssHelper;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\Elevate\Themeoptions\Model\Options::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This set of Options no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('elevatethemeoptions/generate/buildscss');
            }
        }
        $this->coreRegistry->register('elevate_themeoptions_options', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Options') : __('New Options'),
            $id ? __('Edit Options') : __('New Options')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Options'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Options %1', $model->getId()) : __('New Options'));
        return $resultPage;
    }
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
                   ->addBreadcrumb(__('Elevate'), __('Elevate'))
                   ->addBreadcrumb(__('Test'), __('DTest'));
        return $resultPage;
    }
}
