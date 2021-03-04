<?php

namespace SecureTrading\Trust\Cron\PaymentPage;

use SecureTrading\Trust\Helper\Logger\Logger;
use Magento\Sales\Model\Order;
use SecureTrading\Trust\Model\Ui\ConfigProvider;
use SecureTrading\Trust\Helper\Data;

/**
 * Class CancelPendingOrder
 *
 * @package SecureTrading\Trust\Cron\PaymentPage
 */
class CancelPendingOrder
{
	/**
	 * @var \Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory
	 */
	protected $paymentCollectionFactory;

	/**
	 * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
	 */
	protected $orderCollectionFactory;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * CancelPendingOrder constructor.
	 *
	 * @param \Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $paymentCollectionFactory
	 * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
	 * @param Logger $logger
	 */
	public function __construct(\Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $paymentCollectionFactory,
								   \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
								   Logger $logger)
	{
		$this->paymentCollectionFactory = $paymentCollectionFactory;
		$this->orderCollectionFactory   = $orderCollectionFactory;
		$this->logger                   = $logger;
	}

	/**
	 * @throws \Exception
	 */
	public function execute()
	{
		//Checkout Sessions expire 3 hours after creation
		$interval = '3';
		$date     = new \DateTime('now');
		$date->sub(new \DateInterval('PT' . $interval . 'H'));
		$time = $date->format('Y-m-d H:i:s');
		$this->logger->debug($time);

		/** @var \Magento\Sales\Model\ResourceModel\Order\Payment\Collection $paymentCollection */
		$paymentCollection = $this->paymentCollectionFactory->create();
		$paymentCollection->addFieldToFilter('method', ConfigProvider::CODE);
		$orderIds = $paymentCollection->getColumnValues('parent_id');

		/** @var \Magento\Sales\Model\ResourceModel\Order\Collection $orderCollection */
		$orderCollection = $this->orderCollectionFactory->create();
		$orderCollection->addFieldToFilter('entity_id', ['in' => $orderIds])
			->addFieldToFilter('created_at', ['lt' => $time])
			->addFieldToFilter('status', ['eq' => Data::ORDER_STATUS]);

		$i = 0;
		/** @var Order $order */
		foreach ($orderCollection as $order) {
			$isComplete = $order->getpayment()->getAdditionalInformation('is_complete') === true;
			$this->logger->debug($order->getIncrementId());
			if (!$isComplete && $order->canCancel()) {
				$order->cancel();
				$order->addStatusHistoryComment(__("Auto cancel pending order after %1 hours (STPP)", $interval));
				$order->save();
				$i++;
			}
		}
		$this->logger->debug(__("%1 orders have been canceled due to timeout.", $i));
	}
}
