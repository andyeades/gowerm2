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

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect as ResultRedirect;
use Magento\Backend\Model\View\Result\Page as ResultPage;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;

/**
 * Class Edit
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Giftcard
 */
class Edit extends Action
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
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        GiftcardRepositoryInterface $giftcardRepository,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->giftcardRepository = $giftcardRepository;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $giftcardId = (int)$this->getRequest()->getParam('id');
        if ($giftcardId) {
            try {
                $this->giftcardRepository->get($giftcardId);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This gift card no longer exists.'));
                /** @var ResultRedirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/');

                return $resultRedirect;
            }
        }
        /** @var ResultPage $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Aheadworks_Giftcard::giftcard_codes')
            ->getConfig()->getTitle()->prepend(
                $giftcardId ? __('Edit Gift Card Code') : __('New Gift Card Code')
            );

        return $resultPage;
    }
}
