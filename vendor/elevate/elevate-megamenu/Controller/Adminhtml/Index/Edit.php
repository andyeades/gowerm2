<?php
namespace Elevate\Megamenu\Controller\Adminhtml\Index;

class Edit extends \Elevate\Megamenu\Controller\Adminhtml\Index
  {
  /**
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\Registry $coreRegistry
   * @param \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository
   * @param \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
   * @codeCoverageIgnore
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository,
    \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    parent::__construct($context,
      $coreRegistry,
      $megamenuRepository,
      $megamenuRepositoryInterface,
      $resultPageFactory);
  }
  /**
   * {@inheritdoc}
   */
  protected function _isAllowed()
  {
    return $this->_authorization->isAllowed('Elevate_Megamenu::save');
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
    $resultPage->setActiveMenu('Elevate_Megamenu::megamenu')
      ->addBreadcrumb(__('Test'), __('Test'))
      ->addBreadcrumb(__('Manage Test'), __('Manage Test'));
    return $resultPage;
  }

  /**
   * Edit Megamenu Item
   *
   * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  public function execute()
  {
    $id = $this->getRequest()->getParam('entity_id');
    $model = $this->_objectManager->create('Elevate\Megamenu\Model\Megamenu');

    if ($id) {
      $model->load($id);
      if (!$model->getId()) {
        $this->messageManager->addErrorMessage(__('This menu item no longer exists.'));
        /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath('elevate_megamenu/index/managemegamenu');
      }
    }

    $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
    if (!empty($data)) {
      $model->setData($data);
    }

    $this->coreRegistry->register('megamenu', $model);

    /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
    $resultPage = $this->_initAction();
    $resultPage->addBreadcrumb(
      $id ? __('Edit Megamenu Item') : __('New Megamenu Item'),
      $id ? __('Edit Megamenu Item') : __('New Megamenu Item')
    );
    $resultPage->getConfig()->getTitle()->prepend(__('Megamenu Items'));
    $resultPage->getConfig()->getTitle()
      ->prepend($model->getId() ? $model->getTitle() : __('New Megamenu Item'));

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
      $params = ['entity_id' => $this->getRequest()->getParam('entity_id')];
    }
    return $this->getUrl('elevate_megamenu/index/save', $params);
  }

}