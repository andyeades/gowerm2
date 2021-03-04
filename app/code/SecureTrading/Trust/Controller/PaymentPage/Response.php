<?php

namespace SecureTrading\Trust\Controller\PaymentPage;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Sales\Model\Order;
use SecureTrading\Trust\Helper\Logger\Logger;
use SecureTrading\Trust\Helper\Data;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;

/**
 * Class Response
 *
 * @package SecureTrading\Trust\Controller\PaymentPage
 */
class Response extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var ConfigInterface
	 */
	protected $config;

	/**
	 * @var \Magento\Sales\Model\OrderFactory
	 */
	protected $orderFactory;

	/**
	 * @var Session
	 */
	protected $checkoutSession;

	/**
	 * @var CommandPoolInterface
	 */
	protected $commandPool;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * @var OrderSender
	 */
	protected $orderSender;

	/**
	 * Response constructor.
	 *
	 * @param Context $context
	 * @param ConfigInterface $config
	 * @param \Magento\Sales\Model\OrderFactory $orderFactory
	 * @param Session $checkoutSession
	 * @param CommandPoolInterface $commandPool
	 * @param Logger $logger
	 * @param OrderSender $orderSender
	 */
	public function __construct(
		Context $context,
		ConfigInterface $config,
		\Magento\Sales\Model\OrderFactory $orderFactory,
		Session $checkoutSession,
		CommandPoolInterface $commandPool,
		Logger $logger,
		OrderSender $orderSender
	) {
		$this->checkoutSession     = $checkoutSession;
		$this->orderFactory        = $orderFactory;
		$this->config              = $config;
		$this->commandPool         = $commandPool;
		$this->logger              = $logger;
		$this->orderSender         = $orderSender;
		parent::__construct($context);
	}

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		$this->logger->debug('--- Execute Response ---');
		$isUsedIframe = 0;
		try {
			$responseParams = $this->getRequest()->getParams();
			if (isset($responseParams['isusediframe'])) {
				$isUsedIframe = $responseParams['isusediframe'];
			}
			if (!empty($responseParams)) {
				$this->logger->debug('--- Response Params: ', array($responseParams));
				$orderIncrementId = $this->getRequest()->getParam('orderreference', null);
				/** @var Order $order */
				$order = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);

				if (empty($order->getId())) {
					$this->messageManager->addError(__("Something went wrong. Please try again later."));
					return $this->redirect($isUsedIframe, 'checkout/onepage/failure');
				}
				if ($this->isValid($responseParams)) {
					$this->logger->debug('--- Response Error Code: ' . $this->getRequest()->getParam('errorcode', null) . '---');
					if ($this->getRequest()->getParam('errorcode', null) === "0") {
						$this->restoredCheckoutSession($order);
						$this->logger->debug('--- Restored Checkout Session Successfully---');
						return $this->redirect($isUsedIframe, 'checkout/onepage/success');

					} else {
						$order->cancel();
						$order->save();
						return $this->redirect($isUsedIframe, 'checkout/cart');
					}
				}
			}
		} catch (\Exception $exception) {
			$this->logger->debug('--- Notification Response Error Msg: ' . $exception->getMessage() . '---');
			$this->messageManager->addError(__($exception->getMessage()));
			return $this->redirect($isUsedIframe, 'checkout/onepage/failure');
		}
		return $this->redirect($isUsedIframe, 'checkout/onepage/failure');
	}

	/**
	 * @param Order $order
	 */
	private function restoredCheckoutSession(Order $order)
	{
		$this->checkoutSession->setLastOrderId($order->getId());
		$this->checkoutSession->setLastRealOrderId($order->getIncrementId());
		$this->checkoutSession->setLastQuoteId($order->getQuoteId());
		$this->checkoutSession->setLastSuccessQuoteId($order->getQuoteId());
	}

	/**
	 * @param $isUsedIframe
	 * @param $redirectPath
	 * @return \Magento\Framework\Controller\Result\Redirect
	 */
	private function redirect($isUsedIframe, $redirectPath)
	{
		if ($isUsedIframe == 1) {
			return $this->resultRedirectFactory->create()->setPath('securetrading/paymentpage/redirect', ['redirect_path' => urlencode($redirectPath)]);
		} else {
			return $this->resultRedirectFactory->create()->setPath($redirectPath);
		}
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	protected function isValid(array $data)
	{
		$string = '';
		if (empty($data['responsesitesecurity'])) {
			return false;
		}

		$responseSiteSecurity = $data['responsesitesecurity'];
		unset($data['responsesitesecurity']);
		unset($data['notificationreference']);

		ksort($data);
		array_push($data, $this->config->getValue(Data::SITE_PASS));

		foreach ($data as $key => $value) {
			$string .= $value;
		}

		$hash = hash("sha256", $string);

		if ($responseSiteSecurity == $hash) {
			return true;
		}

		return false;
	}

	protected function sendEmailAfterPayment($order){
		$this->orderSender->send($order);
	}
}