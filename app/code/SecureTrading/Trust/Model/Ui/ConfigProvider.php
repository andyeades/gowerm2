<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SecureTrading\Trust\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use SecureTrading\Trust\Helper\Data;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
	/**
	 *
	 */
	const CODE = 'secure_trading';

	/**
	 * @var ConfigInterface
	 */
	protected $config;

	/**
	 * @var StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * ConfigProvider constructor.
	 *
	 * @param StoreManagerInterface $storeManager
	 * @param ConfigInterface $config
	 */
	public function __construct(
		StoreManagerInterface $storeManager,
		ConfigInterFace $config
	) {
		$this->config       = $config;
		$this->storeManager = $storeManager;
	}

	/**
	 * Retrieve assoc array of checkout configuration
	 *
	 * @return array
	 */
	public function getConfig()
	{
		return [
			'payment' => [
				self::CODE => [
					'isActive'    => $this->config->getValue('active'),
					'isIframe'    => $this->config->getValue(Data::USE_IFRAME),
					'startUrl'    => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/start',
					'iframeUrl'   => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/iframe',
					'instruction' => $this->config->getValue(Data::DESCRIPTION),
				]
			]
		];
	}
}