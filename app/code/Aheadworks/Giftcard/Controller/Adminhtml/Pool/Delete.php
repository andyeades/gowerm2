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
namespace Aheadworks\Giftcard\Controller\Adminhtml\Pool;

use Aheadworks\Giftcard\Api\PoolRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

/**
 * Class Delete
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Pool
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Giftcard::giftcard_pools';

    /**
     * @var PoolRepositoryInterface
     */
    private $poolRepository;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PoolRepositoryInterface $poolRepository
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PoolRepositoryInterface $poolRepository,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->poolRepository = $poolRepository;
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
        if ($poolId = (int)$this->getRequest()->getParam('id')) {
            try {
                $this->poolRepository->deleteById($poolId);
                $this->messageManager->addSuccessMessage(__('Code pool was successfully deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }
        $this->messageManager->addErrorMessage(__('Code pool could not be deleted'));
        return $resultRedirect->setPath('*/*/');
    }
}
