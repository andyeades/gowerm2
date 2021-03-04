<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Megamenu\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Elevate\Megamenu\Controller\Adminhtml\Index
{

  /**
   * Delete action
   *
   * @return \Magento\Backend\Model\View\Result\Redirect
   */
  public function execute()
  {
    $resultRedirect = $this->resultRedirectFactory->create();
    $id = (int)$this->getRequest()->getParam('entity_id');

    $menuitem_id = $this->megamenuRepositoryInterface->getById($id);
    if (!empty($menuitem_id)) {
      try {
        $this->megamenuRepositoryInterface->deleteByEntityId($id);
        $this->messageManager->addSuccessMessage(__('You deleted the megamenu item.'));
      } catch (\Exception $exception) {
        $this->messageManager->addErrorMessage($exception->getMessage());
      }
    }

    /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    return $resultRedirect->setPath('elevate_megamenu/index/managemegamenu');
  }
}
