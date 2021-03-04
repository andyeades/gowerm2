<?php
namespace Elevate\Megamenu\Controller\Adminhtml\Index;

class ManageProducticons extends \Magento\Backend\App\Action
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

    $resultPage = $this->resultPageFactory->create();
    $resultPage->getConfig()->getTitle()->prepend((__('Product Icons')));
    return  $resultPage;
  }
}
?>
