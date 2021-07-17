<?php


namespace Elevate\PrintLabels\Controller\Adminhtml\Holidaydates;

class Delete extends \Elevate\PrintLabels\Controller\Adminhtml\Holidaydates
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
        $id = $this->getRequest()->getParam('printlabelsholidaydates_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Elevate\PrintLabels\Model\Holidaydates::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the PrintLabels holidaydates.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['printlabelsholidaydates_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a PrintLabels holidaydates to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
