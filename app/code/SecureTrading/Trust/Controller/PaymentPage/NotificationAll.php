<?php

namespace SecureTrading\Trust\Controller\PaymentPage;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order;

class NotificationAll extends Response implements CsrfAwareActionInterface
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		try {
			$responseParams = $this->getRequest()->getParams();
			if (!empty($responseParams)) {

				$orderIncrementId = $this->getRequest()->getParam('orderreference', null);
				/** @var Order $order */
				$order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);

				if (empty($order->getId())) {
					$this->messageManager->addError(__("Something went wrong. Please try again later."));
				}

				if($this->getRequest()->getParam('errorcode', null) !== 0){

//					TODO: Waiting for checking
//					$order->cancel();
//					$order->addStatusHistoryComment(__('Cancelled order because transaction has been declined. Error code: %1', $errorCode));
//					$order->save();

				}

			}
		} catch (\Exception $exception) {
			$this->logger->debug('--- Notification Decline Error Msg: ' . $exception->getMessage() . '---');

			$this->messageManager->addError(__($exception->getMessage()));
		}
		$this->getResponse()->setHttpResponseCode(200);
	}

	public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
	{
		return null;
	}

	public function validateForCsrf(RequestInterface $request): ?bool
	{
		return true;
	}
}