<?php

namespace SecureTrading\Trust\Controller\PaymentPage;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Model\Order;

/**
 * Class NotificationResponse
 *
 * @package SecureTrading\Trust\Controller\PaymentPage
 */
class NotificationResponse extends Response implements CsrfAwareActionInterface
{
	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		$this->logger->debug('--- Notification Response ---');
		try {
			$responseParams = $this->getRequest()->getParams();
			if (!empty($responseParams)) {
				$this->logger->debug('--- Notification Response Params: ', array($responseParams));
				$orderIncrementId = $this->getRequest()->getParam('orderreference', null);
				/** @var Order $order */
				$order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);

				if (empty($order->getId())) {
					$this->messageManager->addError(__("Something went wrong. Please try again later."));
				}
				if ($this->isValid($responseParams)) {
					/** @var Order\Payment $payment */
					$payment = $order->getPayment();
					foreach ($responseParams as $key => $param) {
						$payment->setAdditionalInformation($key, $param);
					}
					$this->logger->debug('--- Notification Response Error Code: ' . $this->getRequest()->getParam('errorcode', null) . '---');
					if ($this->getRequest()->getParam('errorcode', null) === "0") {
						if ($payment->getAdditionalInformation('payment_action') == 'authorize') {
							$this->commandPool->get('authorize')->execute(['order' => $order, 'info' => $responseParams]);
						} elseif ($payment->getAdditionalInformation('payment_action') == 'authorize_capture') {
							$this->commandPool->get('authorize_capture')->execute(['order' => $order, 'info' => $responseParams]);
						}
							$this->sendEmailAfterPayment($order);
					} else {
						$order->cancel();
						$order->save();
					}
				} else {
					$order->cancel();
					$order->addCommentToStatusHistory(__('Invalid response site security.'));
					$order->save();
					$this->logger->debug('--- Notification Response Error: Invalid response site security.');
				}
			}
		} catch (\Exception $exception) {
			$this->logger->debug('--- Notification Response Error Msg: ' . $exception->getMessage() . '---');

			$this->messageManager->addError(__($exception->getMessage()));
		}
		$this->getResponse()->setHttpResponseCode(200);
	}

	/**
	 * @param RequestInterface $request
	 * @return InvalidRequestException|null
	 */
	public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
	{
		return null;
	}

	/**
	 * @param RequestInterface $request
	 * @return bool|null
	 */
	public function validateForCsrf(RequestInterface $request): ?bool
	{
		return true;
	}
}