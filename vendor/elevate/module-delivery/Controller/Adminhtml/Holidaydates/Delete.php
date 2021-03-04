<?php


namespace Elevate\Delivery\Controller\Adminhtml\Holidaydates;

class Delete extends \Elevate\Delivery\Controller\Adminhtml\Holidaydates
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
        $id = $this->getRequest()->getParam('deliveryholidaydates_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Elevate\Delivery\Model\Holidaydates::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Deliveryholidaydates.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['deliveryholidaydates_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Deliveryholidaydates to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
