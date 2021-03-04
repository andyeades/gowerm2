<?php


namespace Elevate\CustomerGallery\Controller\Adminhtml\Items;

use Magento\Framework\Exception\LocalizedException;

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
            $id = $this->getRequest()->getParam('items_id');
        
            $model = $this->_objectManager->create(\Elevate\CustomerGallery\Model\Items::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Items no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $data = $this->filterImageData($data);
            
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Items.'));
                $this->dataPersistor->clear('elevate_customergallery_items');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['items_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Items.'));
            }
        
            $this->dataPersistor->set('elevate_customergallery_items', $data);
            return $resultRedirect->setPath('*/*/edit', ['items_id' => $this->getRequest()->getParam('items_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    
      /**
   * @param array $data
   *
   * @return array
   */
  protected function filterImageData(array $data)
  {
    $final_data = $data;
    if (isset($final_data['image'][0]['name'])) {
      $final_data['image'] = $final_data['image'][0]['name'];
    } else {
      $final_data['image'] = null;
    }
    return $final_data;
  }
    
}
