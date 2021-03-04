<?php
namespace Elevate\ProductIcons\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
  /**
   * @var \Magento\Framework\View\Result\PageFactory
   */
  protected $resultPageFactory;

  /**
   * Constructor
   *
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    parent::__construct($context);
    $this->resultPageFactory = $resultPageFactory;
  }

  /**
   * Load the page defined
   *
   * @return \Magento\Framework\View\Result\Page
   */
  public function execute()
  {
    /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
    $resultPage = $this->_initAction();
    $resultPage->getConfig()->getTitle()->prepend(__('ProductIcons Items'));


    return $resultPage;
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
}
?>
  