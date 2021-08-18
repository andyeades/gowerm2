<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Controller\Adminhtml\Giftcard;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Backend\App\Action\Context;
use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Aheadworks\Giftcard\Api\Data\GiftcardInterfaceFactory;
use Aheadworks\Giftcard\Api\GiftcardManagementInterface;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Aheadworks\Giftcard\Model\Source\EmailStatus;
use Aheadworks\Giftcard\Model\Source\Giftcard\EmailTemplate;
use Aheadworks\Giftcard\Model\ResourceModel\Giftcard as ResourceGiftcard;
use Aheadworks\Giftcard\Model\DataProcessor\PostDataProcessorInterface;

/**
 * Class Save
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Giftcard
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Giftcard::giftcard_codes';

    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var GiftcardManagementInterface
     */
    private $giftcardManagement;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var GiftcardInterfaceFactory
     */
    private $giftcardDataFactory;

    /**
     * @var ResourceGiftcard
     */
    private $resourceGiftcard;

    /**
     * @var PostDataProcessorInterface
     */
    private $postDataProcessor;

    /**
     * @param Context $context
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param GiftcardManagementInterface $giftcardManagement
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param GiftcardInterfaceFactory $giftcardDataFactory
     * @param ResourceGiftcard $resourceGiftcard
     * @param PostDataProcessorInterface $postDataProcessor
     */
    public function __construct(
        Context $context,
        GiftcardRepositoryInterface $giftcardRepository,
        GiftcardManagementInterface $giftcardManagement,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        GiftcardInterfaceFactory $giftcardDataFactory,
        ResourceGiftcard $resourceGiftcard,
        PostDataProcessorInterface $postDataProcessor
    ) {
        parent::__construct($context);
        $this->giftcardRepository = $giftcardRepository;
        $this->giftcardManagement = $giftcardManagement;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
        $this->giftcardDataFactory = $giftcardDataFactory;
        $this->resourceGiftcard = $resourceGiftcard;
        $this->postDataProcessor = $postDataProcessor;
    }

    /**
     * Save action
     *
     * @return ResultRedirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            try {
                $this->resourceGiftcard->beginTransaction();
                $data = $this->postDataProcessor->prepareEntityData($data);
                $giftcard = $this->performSave($data);

                $this->dataPersistor->clear('aw_giftcard_giftcard');
                $this->messageManager->addSuccessMessage(__('Gift Card Code was successfully saved'));

                $this->resourceGiftcard->commit();
                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $giftcard->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Gift Card code')
                );
            }
            $this->resourceGiftcard->rollBack();
            $this->dataPersistor->set('aw_giftcard_giftcard', $data);
            $id = isset($data['id']) ? $data['id'] : false;
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $id, '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/new', ['_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Perform save
     *
     * @param array $data
     * @return GiftcardInterface
     * @throws LocalizedException
     */
    private function performSave($data)
    {
        $saveAction = $this->getRequest()->getParam('action');
        $id = isset($data['id']) ? $data['id'] : false;
        $dataObject = $id
            ? $this->giftcardRepository->get($id)
            : $this->giftcardDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $dataObject,
            $data,
            GiftcardInterface::class
        );
        if (!$dataObject->getId()) {
            $dataObject->setId(null);
        }
        $giftcard = $this->giftcardRepository->save($dataObject);
        if ($saveAction == 'save_and_send' && $giftcard->getEmailTemplate() != EmailTemplate::DO_NOT_SEND) {
            $giftcards = $this->giftcardManagement->sendGiftcardByCode($giftcard->getCode(), false);
            $giftcard = count($giftcards) ? array_shift($giftcards) : null;
            if ($giftcard && $giftcard->getEmailSent() == EmailStatus::SENT) {
                $this->messageManager->addSuccessMessage(__('Email was successfully sent'));
            } else {
                $this->messageManager->addErrorMessage(__('Could not send email'));
            }
        }

        return $giftcard;
    }
}
