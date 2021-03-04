<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\ProductIcons\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Elevate\ProductIcons\Controller\Adminhtml\Index
{

  /**
   * Delete action
   *
   * @return \Magento\Backend\Model\View\Result\Redirect
   */
  public function execute()
  {
    $resultRedirect = $this->resultRedirectFactory->create();
    $id = (int)$this->getRequest()->getParam('icon_id');

    $iconitem_id = $this->producticonsRepositoryInterface->getById($id);
    if (!empty($iconitem_id)) {
      try {
        $this->producticonsRepositoryInterface->deleteByEntityId($id);
        $this->messageManager->addSuccessMessage(__('You deleted the producticon item.'));
      } catch (\Exception $exception) {
        $this->messageManager->addErrorMessage($exception->getMessage());
      }
    }

    /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    return $resultRedirect->setPath('producticons/index/index');
  }
}
