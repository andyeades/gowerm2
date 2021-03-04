<?php


namespace Elevate\CartAssignments\Controller\Adminhtml\CartAssignments;

/**
 * Class Delete
 *
 * @package Elevate\CartAssignments\Controller\Adminhtml\CartAssignments
 */
class Delete extends \Elevate\CartAssignments\Controller\Adminhtml\CartAssignments
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
        $id = $this->getRequest()->getParam('cartassignments_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Elevate\CartAssignments\Model\CartAssignments::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Cartassignments.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['cartassignments_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Cartassignments to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

