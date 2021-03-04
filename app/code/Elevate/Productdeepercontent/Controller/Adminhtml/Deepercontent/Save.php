<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Controller\Adminhtml\Deepercontent;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Elevate\Productdeepercontent\Model\ImageUploader;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;


    /**
     * @var ImageUploader
     */
    private $imageUploader;


    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        ImageUploader $imageUploader
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->imageUploader = $imageUploader;
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
            $id = $this->getRequest()->getParam('deepercontent_id');

            $model = $this->_objectManager->create(\Elevate\Productdeepercontent\Model\Deepercontent::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Deepercontent no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            if(isset($data['deepercontent_image'])) {
                $fileName = $data['deepercontent_image'][0]['name'];
                $data['deepercontent_image'] = $fileName;
                $this->imageUploader->moveFileFromTmp($fileName);
            } else {
                $data['deepercontent_image'] = '';
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Deepercontent.'));
                $this->dataPersistor->clear('elevate_productdeepercontent_deepercontent');



                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['deepercontent_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Deepercontent.'));
            }

            $this->dataPersistor->set('elevate_productdeepercontent_deepercontent', $data);
            return $resultRedirect->setPath('*/*/edit', ['deepercontent_id' => $this->getRequest()->getParam('deepercontent_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

