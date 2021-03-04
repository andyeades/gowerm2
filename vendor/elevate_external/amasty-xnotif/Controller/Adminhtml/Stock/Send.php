<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Xnotif
 */


namespace Amasty\Xnotif\Controller\Adminhtml\Stock;

use Magento\Backend\App\Action;

/**
 * Class Sendforsku
 */
class Send extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\ProductAlert\Model\StockFactory
     */
    private $stockFactory;

    /**
     * @var \Amasty\Xnotif\Model\Observer
     */
    protected $amastyObserverClass;

    public function __construct(
        Action\Context $context,
        \Magento\ProductAlert\Model\StockFactory $stockFactory,
        \Amasty\Xnotif\Model\Observer $amastyObserverClass
    ) {
        parent::__construct($context);
        $this->stockFactory = $stockFactory;
        $this->amastyObserverClass = $amastyObserverClass;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $product_id = (int)$this->getRequest()->getParam('product_id');

        if (!$product_id) {
            $this->messageManager->addErrorMessage(
                __(
                    'An error occurred while doing something'
                )
            );
        } else {
            $type = 'stock';
            $send_email = $this->amastyObserverClass->sendNotificationForProductId($product_id, $type);


            if ($alert && $alert->getId()) {
                try {
                    $alert->delete();
                    $this->messageManager->addSuccessMessage(
                        __('The item has been deleted from Subscriptions.')
                    );
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage(
                        __(
                            'An error occurred while deleting the item from Subscriptions.'
                        )
                    );
                }
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'Amasty_Xnotif::stock'
        );
    }
}
