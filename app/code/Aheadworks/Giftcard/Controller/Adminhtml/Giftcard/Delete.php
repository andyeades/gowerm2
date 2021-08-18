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

use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Delete
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Giftcard
 */
class Delete extends \Magento\Backend\App\Action
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
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($giftcardId = (int)$this->getRequest()->getParam('id')) {
            try {
                $this->giftcardRepository->deleteById($giftcardId);
                $this->messageManager->addSuccessMessage(__('Gift Card Code was successfully deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }
        $this->messageManager->addErrorMessage(__('Gift Card Code could not be deleted'));
        return $resultRedirect->setPath('*/*/');
    }
}
