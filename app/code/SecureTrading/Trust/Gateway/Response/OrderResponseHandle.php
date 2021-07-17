<?php

namespace SecureTrading\Trust\Gateway\Response;

use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;

/**
 * Class OrderResponseHandle
 *
 * @package SecureTrading\Trust\Gateway\Response
 */
class OrderResponseHandle implements HandlerInterface
{
	/**
	 * @param array $handlingSubject
	 * @param array $response
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function handle(array $handlingSubject, array $response)
	{
		$paymentDO = SubjectReader::readPayment($handlingSubject);

		/** @var Payment $payment */
		$payment = $paymentDO->getPayment();
		$order = $payment->getOrder();

		if (!empty($response)) {
			$payment->setAdditionalInformation('secure_trading_endpoint', $response['url']);
			unset($response['url']);
			$payment->setAdditionalInformation('secure_trading_data', $response);
			if (!empty($response['issubscription'])) {
				$payment->setAdditionalInformation('payment_action', 'authorize_capture');
			} else {
				//set action authorize_capture for subscription
				$payment->setAdditionalInformation('payment_action', $response['settlestatus'] == 2 ? 'authorize' : 'authorize_capture');
			}
		}
		$order->setState(Order::STATE_NEW);
		$order->setStatus('pending_secure_trading_payment');
		$order->setCanSendNewEmailFlag(false);
		$order->save();
	}
}
