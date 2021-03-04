<?php

namespace SecureTrading\Trust\Block\PaymentPage;

use Magento\Framework\View\Element\Template;
use Magento\Payment\Gateway\ConfigInterface;
use SecureTrading\Trust\Helper\Data;

class Iframe extends Template
{
	/**
	 * @var ConfigInterface
	 */
	protected $config;

	protected $orderFactory;

	/**
	 * Iframe constructor.
	 *
	 * @param Template\Context $context
	 * @param ConfigInterface $config
	 * @param array $data
	 */
	public function __construct(
		Template\Context $context,
		ConfigInterface $config,
		\Magento\Sales\Model\OrderFactory $orderFactory,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->orderFactory = $orderFactory;
		$this->config       = $config;
	}

	public function gePaymentUrl()
	{
		return $this->getUrl('securetrading/paymentpage/raw', ['orderId' => $this->getRequest()->getParam('orderId')]);
	}

	public function getWidth()
	{
		return $this->config->getValue(DATA::IFRAME_WIDTH);
	}

	public function getHeight()
	{
		return $this->config->getValue(DATA::IFRAME_HEIGHT);
	}
}