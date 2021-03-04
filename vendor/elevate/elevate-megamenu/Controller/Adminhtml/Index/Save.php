<?php
namespace Elevate\Megamenu\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Elevate\Megamenu\Controller\Adminhtml\Index
{

  /**
   * Primary key auto increment flag
   *
   * @var bool
   */
  //protected $_isPkAutoIncrement = false;

  /**
   * Constructor
   *
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\Registry $coreRegistry
   * @param \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository
   * @param \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository,
    \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    parent::__construct($context,$coreRegistry,$megamenuRepository,$megamenuRepositoryInterface,$resultPageFactory);
  }

  /**
   * {@inheritdoc}
   */
  protected function _isAllowed()
  {
    return $this->_authorization->isAllowed('Elevate_Megamenu::save');
  }

  /**
   * Save action
   *
   * @return \Magento\Framework\Controller\ResultInterface
   */
  public function execute()
  {
    $data = $this->getRequest()->getPostValue();
    /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
    $resultRedirect = $this->resultRedirectFactory->create();

    if ($data) {
      $id = $this->getRequest()->getParam('entity_id');
      // Had to Change this to (empty($id)) as it wasn't working otherwise!?
      if (empty($id)) {
        unset($data['entity_id']);
        $model = $this->megamenuRepository->create();
      } else {
        $model = $this->megamenuRepository->getById($id);
      }


      $model->setData($data);
      if (!empty($id)) {
        $model->setId($id);
      }
      try {
        $model->save();
        $this->messageManager->addSuccessMessage(__('You saved the item.'));
      } catch (\Exception $e) {
        $this->messageManager->addErrorMessage($e->getMessage());
        $this->_objectManager->get(\Magento\Backend\Model\Session::class)->setDesignData($data);
        return $resultRedirect->setPath('elevate_megamenu/index/edit', ['entity_id' => $model->getId()]);
      }
    }

    return $resultRedirect->setPath('elevate_megamenu/index/managemegamenu');
  }
}

