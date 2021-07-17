<?php


namespace Elevate\CartAssignments\Controller\Adminhtml\CartAssignments;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 *
 * @package Elevate\CartAssignments\Controller\Adminhtml\CartAssignments
 */
class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('cartassignments_id');
        
            $model = $this->_objectManager->create(\Elevate\CartAssignments\Model\CartAssignments::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Cartassignments no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
         
  if(!empty($data['store_ids'])) {
   $store_view = implode(',',$data['store_ids']);
   $data['store_ids'] = $store_view;
}    
         
  if(!empty($data['category_ids'])) {
   $store_view = implode(',',$data['category_ids']);
   $data['assigned_categories'] = $store_view;
}    

     $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Cartassignments.'));
                $this->dataPersistor->clear('elevate_cartassignments_cartassignments');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['cartassignments_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Cartassignments.'));
            }
        
            $this->dataPersistor->set('elevate_cartassignments_cartassignments', $data);
            return $resultRedirect->setPath('*/*/edit', ['cartassignments_id' => $this->getRequest()->getParam('cartassignments_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

