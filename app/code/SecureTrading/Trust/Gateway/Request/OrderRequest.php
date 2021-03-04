<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SecureTrading\Trust\Gateway\Request;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Store\Model\StoreManagerInterface;
use SecureTrading\Trust\Helper\Data;
use SecureTrading\Trust\Helper\Logger\Logger;
use Magento\Framework\App\Area;

/**
 * Class OrderRequest
 *
 * @package SecureTrading\Trust\Gateway\Request
 */
class OrderRequest implements BuilderInterface
{
	/**
	 * @var ConfigInterface
	 */
	private $config;

	/**
	 * @var StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * @var \Magento\Framework\Encryption\EncryptorInterface
	 */
	protected $enc;

	/**
	 * @var \SecureTrading\Trust\Helper\Logger\Logger
	 */
	protected $logger;

	/**
	 * @var \Magento\Framework\App\State
	 */
	protected $state;

	/**
	 * @var TimezoneInterface
	 */
	protected $timezone;

	/**
	 * OrderRequest constructor.
	 *
	 * @param TimezoneInterface $timezone
	 * @param ConfigInterface $config
	 * @param StoreManagerInterface $storeManager
	 * @param \Magento\Framework\Encryption\EncryptorInterface $enc
	 * @param Logger $logger
	 * @param \Magento\Framework\App\State $state
	 */
	public function __construct(
		TimezoneInterface $timezone,
		ConfigInterface $config,
		StoreManagerInterface $storeManager,
		\Magento\Framework\Encryption\EncryptorInterface $enc,
		Logger $logger,
		\Magento\Framework\App\State $state
	) {
		$this->enc          = $enc;
		$this->storeManager = $storeManager;
		$this->config       = $config;
		$this->logger       = $logger;
		$this->state 		= $state;
		$this->timezone     = $timezone;
	}

	/**
	 * @param array $buildSubject
	 * @return array
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	public function build(array $buildSubject)
	{
		if (!isset($buildSubject['payment'])
			|| !$buildSubject['payment'] instanceof PaymentDataObjectInterface
		) {
			throw new \InvalidArgumentException('Payment data object should be provided');
		}

		/** @var PaymentDataObjectInterface $paymentDO */
		$paymentDO = $buildSubject['payment'];

		$order = $paymentDO->getOrder();

		$payment = $paymentDO->getPayment();

		if (!$payment instanceof OrderPaymentInterface) {
			throw new \LogicException('Order payment should be provided.');
		}
		$url = $this->config->getValue(
			Data::DETAILS_PAGE
		);

		if (!$this->config->getValue(Data::SKIP_CHOICE_PAGE)) {
			$url = $this->config->getValue(
				Data::CHOICE_PAGE
			);
		}
		$data = [
			'sitereference' => $this->config->getValue(Data::SITE_REFERENCE),
			'currencyiso3a' => $this->_getCurrencyCode($payment->getOrder()),
			'mainamount'    => $this->_formatMainAmount($payment->getOrder(), $buildSubject),
//			'mainamount'    => strval(600.10), //todo: why round ?
			'version'       => $this->config->getValue(Data::VERSION),
			'stprofile'     => $this->config->getValue(Data::ST_PROFILE),

			//Customer will be offered a choice between the delivery address entered on
			//your website and addresses on their PayPal account.
			'paypaladdressoverride' => 0,

			'billingprefixname'   => $order->getBillingAddress()->getPrefix(),
			'billingfirstname'    => $order->getBillingAddress()->getFirstname(),
			'billingmiddlename'   => $order->getBillingAddress()->getMiddlename(),
			'billinglastname'     => $order->getBillingAddress()->getLastname(),
			'billingstreet'       => $order->getBillingAddress()->getStreetLine1(),
			'billingtown'         => $order->getBillingAddress()->getCity(),
			'billingcounty'       => $order->getBillingAddress()->getRegionCode(),
			'billingpostcode'     => $order->getBillingAddress()->getPostcode(),
			'billingcountryiso2a' => $order->getBillingAddress()->getCountryId(),
			'billingemail'        => $order->getBillingAddress()->getEmail(),
			'billingtelephone'    => $order->getBillingAddress()->getTelephone(),

			'settleduedate' => $this->getSettleduedate($this->config->getValue(Data::SETTLE_DUE_DATE)),
			'settlestatus'  => $this->config->getValue(Data::PAYMENT_ACTION) == 'authorize' ? 2 : $this->config->getValue(Data::SETTLE_STATUS),

			'orderreference'            => $order->getOrderIncrementId(),
			'successfulurlredirect'     => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/response',
			'declinedurlredirect'       => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/response',
			'successfulurlnotification' => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/notificationresponse',
			'declinedurlnotification'   => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/notificationdecline',
			'allurlnotification'        => $this->storeManager->getStore()->getBaseUrl() . 'securetrading/paymentpage/notificationall',
			'ruleidentifiers'           =>
				[
					'STR-6',
					'STR-7',
					'STR-8',
					'STR-9',
					'STR-10'
				],
			'url'                       => $url,
			'accounttypedescription'    => $this->state->getAreaCode() == Area::AREA_ADMINHTML ? 'MOTO' : 'ECOM',

			'customfield4' => $this->_getCartInformation(),
			'customfield5' => $this->config->getVersionInformation(),
			'isusediframe' => $this->config->getValue(Data::USE_IFRAME),

			'stextraurlredirectfields' => 'isusediframe',
			'stextraurlnotifyfields'   =>
				[
					'accounttypedescription',
					'enrolled',
					'status',
					'maskedpan',
					'authcode',
					'securityresponsepostcode',
					'securityresponseaddress',
					'securityresponsesecuritycode'
				],
//			'sitesecuritytimestamp'    => date('Y-m-d h:i:s', time()),
			'sitesecuritytimestamp'    => $this->_formatTimeStamp(),
		];

		$shippingAddress = [];

		if ($order->getShippingAddress()) {
			$shippingAddress =
				[
					'customerprefixname'   => $order->getShippingAddress()->getPrefix(),
					'customerfirstname'    => $order->getShippingAddress()->getFirstname(),
					'customermiddlename'   => $order->getShippingAddress()->getMiddlename(),
					'customerlastname'     => $order->getShippingAddress()->getLastname(),
					'customerstreet'       => $order->getShippingAddress()->getStreetLine1(),
					'customertown'         => $order->getShippingAddress()->getCity(),
					'customercounty'       => $order->getShippingAddress()->getRegionCode(),
					'customerpostcode'     => $order->getShippingAddress()->getPostcode(),
					'customercountryiso2a' => $order->getShippingAddress()->getCountryId(),
					'customeremail'        => $order->getShippingAddress()->getEmail(),
					'customertelephone'    => $order->getShippingAddress()->getTelephone()
				];
		}

		$data = array_merge($data, $shippingAddress);

		$this->logger->debug('--- ORDER INCREMENT ID: ' . $order->getOrderIncrementId() . '---');
		$this->logger->debug('--- PREPARE DATA:', array($data));

		$sitesecurity         = $this->config->getSiteSecurity($data);
		$data['sitesecurity'] = $sitesecurity;

		return $data;
	}

	/**
	 * @param $settleDueDate
	 * @return false|string
	 */
	protected function getSettleduedate($settleDueDate)
	{
		$settleDueDate = (int)$settleDueDate;
		$daysToAdd     = '+ ' . $settleDueDate . ' days';
		//todo: need to handle return = false
		return $formattedSettleDueDate = date('Y-m-d', strtotime($daysToAdd));
	}

	protected function _getCartInformation()
	{
		return 'MAGENTO';
	}

	protected function _getCurrencyCode($order)
	{
		return $order->getBaseCurrencyCode();
	}

	protected function _formatMainAmount($order, array $buildSubject)
	{
		if ($this->_getCurrencyCode($order) == 'JPY') {
			return strval(number_format(SubjectReader::readAmount($buildSubject), 0, '', ''));
		}
		return strval(number_format(SubjectReader::readAmount($buildSubject), 2, '.', ''));
	}

	protected function _formatTimeStamp()
	{
		$date = $this->timezone->date();
		$formattedDate  = $this->timezone->convertConfigTimeToUtc($date);
		return $formattedDate;
	}
}
