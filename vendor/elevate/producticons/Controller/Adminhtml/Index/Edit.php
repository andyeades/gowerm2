<?php
namespace Elevate\ProductIcons\Controller\Adminhtml\Index;

class Edit extends \Elevate\ProductIcons\Controller\Adminhtml\Index
  {
  /**
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\Registry $coreRegistry
   * @param \Elevate\ProductIcons\Model\ProducticonsRepository $producticonsRepository
   * @param \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
   * @codeCoverageIgnore
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\ProductIcons\Model\ProducticonsRepository $producticonsRepository,
    \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    parent::__construct($context,
      $coreRegistry,
      $producticonsRepository,
      $producticonsRepositoryInterface,
      $resultPageFactory);
  }
  /**
   * {@inheritdoc}
   */
  protected function _isAllowed()
  {
    return $this->_authorization->isAllowed('Elevate_ProductIcons::save');
  }

  /**
   * Init actions
   *
   * @return \Magento\Backend\Model\View\Result\Page
   */
  protected function _initAction()
  {
    // load layout, set active menu and breadcrumbs
    /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
    $resultPage = $this->resultPageFactory->create();
    $resultPage->setActiveMenu('Elevate_ProductIcons::index')
      ->addBreadcrumb(__('Test'), __('Test'))
      ->addBreadcrumb(__('Manage Test'), __('Manage Test'));
    return $resultPage;
  }

  /**
   * Edit ProductIcons Item
   *
   * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  public function execute()
  {
    $id = $this->getRequest()->getParam('icon_id');
    $model = $this->_objectManager->create('Elevate\ProductIcons\Model\Producticons');

    if ($id) {
      $model->load($id);
      if (!$model->getIconId()) {
        $this->messageManager->addErrorMessage(__('This menu item no longer exists.'));
        /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('producticons/index/index');
      }
    }

    $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
    if (!empty($data)) {
      $model->setData($data);
    }

    $this->coreRegistry->register('producticons', $model);

    /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
    $resultPage = $this->_initAction();
    $resultPage->addBreadcrumb(
      $id ? __('Edit ProductIcons Item') : __('New ProductIcons Item'),
      $id ? __('Edit ProductIcons Item') : __('New ProductIcons Item')
    );
    $resultPage->getConfig()->getTitle()->prepend(__('ProductIcons Items'));
    $resultPage->getConfig()->getTitle()
      ->prepend($model->getId() ? $model->getTitle() : __('New ProductIcons Item'));

    return $resultPage;
  }

  /**
   * Get the url for save
   *
   * @return string
   */
  public function getSaveUrl()
  {
    if ($this->getTemplateId()) {
      $params = ['template_id' => $this->getTemplateId()];
    } else {
      $params = ['icon_id' => $this->getRequest()->getParam('icon_id')];
    }
    return $this->getUrl('producticons/index/save', $params);
  }

}