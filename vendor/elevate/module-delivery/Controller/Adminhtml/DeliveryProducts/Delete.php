<?php


namespace Elevate\Delivery\Controller\Adminhtml\DeliveryProducts;

class Delete extends \Elevate\Delivery\Controller\Adminhtml\DeliveryProducts
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
        $id = $this->getRequest()->getParam('deliveryproducts_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Elevate\Delivery\Model\DeliveryProducts::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Deliveryproducts.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['deliveryproducts_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Deliveryproducts to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
