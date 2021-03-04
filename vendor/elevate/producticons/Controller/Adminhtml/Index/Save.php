<?php
namespace Elevate\ProductIcons\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Elevate\ProductIcons\Controller\Adminhtml\Index
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
   * @param \Elevate\ProductIcons\Model\ProducticonsRepository $producticonsRepository
   * @param \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\ProductIcons\Model\ProducticonsRepository $producticonsRepository,
    \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    parent::__construct($context,$coreRegistry,$producticonsRepository,$producticonsRepositoryInterface,$resultPageFactory);
  }

  /**
   * {@inheritdoc}
   */
  protected function _isAllowed()
  {
    return $this->_authorization->isAllowed('Elevate_ProductIcons::save');
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
      $id = $this->getRequest()->getParam('icon_id');
      // Had to Change this to (empty($id)) as it wasn't working otherwise!?
      if (empty($id)) {
        unset($data['icon_id']);
        $model = $this->producticonsRepository->create();
      } else {
        $model = $this->producticonsRepository->getById($id);
      }

      $data = $this->filterImageData($data);

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
        return $resultRedirect->setPath('producticons/index/edit', ['icon_id' => $model->getId()]);
      }
    }

    return $resultRedirect->setPath('producticons/index/index');
  }

  /**
   * @param array $data
   *
   * @return array
   */
  protected function filterImageData(array $data)
  {
    $final_data = $data;
    if (isset($final_data['icon_url'][0]['name'])) {
      $final_data['icon_url'] = $final_data['icon_url'][0]['name'];
    } else {
      $final_data['icon_url'] = null;
    }
    return $final_data;
  }
}

