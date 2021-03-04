<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Controller\Adminhtml\Deepercontent;

class Delete extends \Elevate\Productdeepercontent\Controller\Adminhtml\Deepercontent
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('deepercontent_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Elevate\Productdeepercontent\Model\Deepercontent::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Deepercontent.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['deepercontent_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Deepercontent to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

