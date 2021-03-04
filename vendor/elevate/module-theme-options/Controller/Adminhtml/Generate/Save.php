<?php


namespace Elevate\Themeoptions\Controller\Adminhtml\Generate;

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
            $id = $this->getRequest()->getParam('entity_id');

            if (empty($id)) {
                $model = $this->_objectManager->create(\Elevate\Themeoptions\Model\Options::class);
                $model->setData($data);
            } else {
                $model = $this->_objectManager->create(\Elevate\Themeoptions\Model\Options::class)->load($id);
                if (!$model->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This Options no longer exists.'));
                    return $resultRedirect->setPath('elevatethemeoptions/generate/buildscss');
                }

                $model->setData($data);
            }



            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Options.'));
                $this->dataPersistor->clear('elevate_themeoptions_options');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('elevatethemeoptions/generate/buildscss', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('elevatethemeoptions/generate/buildscss');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Options set.'));
            }

            $this->dataPersistor->set('elevate_themeoptions_options', $data);
            return $resultRedirect->setPath('elevatethemeoptions/generate/buildscss', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('elevatethemeoptions/generate/buildscss');
    }
}
