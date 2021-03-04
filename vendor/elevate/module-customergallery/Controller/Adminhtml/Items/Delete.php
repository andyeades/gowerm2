<?php


namespace Elevate\CustomerGallery\Controller\Adminhtml\Items;

class Delete extends \Elevate\CustomerGallery\Controller\Adminhtml\Items
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
        $id = $this->getRequest()->getParam('items_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Elevate\CustomerGallery\Model\Items::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Items.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['items_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Items to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
