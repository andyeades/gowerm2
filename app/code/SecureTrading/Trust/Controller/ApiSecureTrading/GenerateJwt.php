<?php

namespace SecureTrading\Trust\Controller\ApiSecureTrading;

use Firebase\JWT\JWT;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\StoreManagerInterface;
use SecureTrading\Trust\Helper\Logger\Logger;
use SecureTrading\Trust\Helper\SubscriptionHelper;
use Magento\Checkout\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Multishipping\Block\Checkout\Overview;

/**
 * Class GenerateJwt
 *
 * @package SecureTrading\Trust\Controller\ApiSecureTrading
 */
class GenerateJwt extends \Magento\Framework\App\Action\Action
{
    /**
     *
     */
    const TEST_JWT_NAME = "payment/api_secure_trading/test_jwt_name";
    /**
     *
     */
    const JWT_NAME = "payment/api_secure_trading/jwt_name";
    /**
     *
     */
    const TEST_JWT_SECRET_KEY = "payment/api_secure_trading/test_jwt_secret_key";
    /**
     *
     */
    const JWT_SECRET_KEY = "payment/api_secure_trading/jwt_secret_key";
    /**
     *
     */
    const TEST_SITE = "payment/api_secure_trading/test_site_reference";
    /**
     *
     */
    const SITE = "payment/api_secure_trading/site_reference";
    /**
	 * @var \Magento\Framework\Controller\Result\Json
	 */
	protected $jsonFactory;

	/**
	 * @var \Magento\Sales\Model\OrderFactory
	 */
	protected $orderFactory;

	/**
	 * @var JWT
	 */
	protected $jwt;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * @var StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * @var EncryptorInterface
	 */
	protected $enc;

    /**
     * @var SubscriptionHelper
     */
    protected $subscriptionHelper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var Overview
     */
    protected $checkoutOverview;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * GenerateJwt constructor.
     * @param Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\Controller\Result\Json $json
     * @param JWT $jwt
     * @param Logger $logger
     * @param StoreManagerInterface $storeManager
     * @param EncryptorInterface $enc
     * @param SubscriptionHelper $subscriptionHelper
     * @param Session $session
     * @param CartRepositoryInterface $quoteRepository
     * @param Overview $checkoutOverview
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
		Context $context,
		\Magento\Sales\Model\OrderFactory $orderFactory,
		\Magento\Framework\Controller\Result\Json $json,
		JWT $jwt,
		Logger $logger,
		StoreManagerInterface $storeManager,
		EncryptorInterface $enc,
		SubscriptionHelper $subscriptionHelper,
		Session $session,
		CartRepositoryInterface $quoteRepository,
		Overview $checkoutOverview,
		ScopeConfigInterface $scopeConfig
	)
	{
		parent::__construct($context);
		$this->jsonFactory = $json;
		$this->orderFactory = $orderFactory;
		$this->jwt = $jwt;
		$this->logger = $logger;
		$this->storeManager = $storeManager;
		$this->enc = $enc;
		$this->subscriptionHelper = $subscriptionHelper;
		$this->session = $session;
		$this->quoteRepository = $quoteRepository;
		$this->checkoutOverview = $checkoutOverview;
		$this->scopeConfig = $scopeConfig;
	}

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		try {
			$data = $this->getRequest()->getParams();
			$shippingAddress = [];

			$array['amount'] = $data['grandTotal'];
			$accountType = $data['accounttypedescription'];
			$currency = $data['currencyiso3a'];
			$orderreference = $data['orderreference'];
            $order = $this->orderFactory->create()->load($orderreference);
			$mainAmount = $this->subscriptionHelper->_formatMainAmountPayLoad($currency, $array);
			$requestTypes = 'ORDER';
			$skip = 0;
            $isTest = $this->storeManager->getStore()->getConfig('payment/api_secure_trading/is_test');
			$data['jwt_name'] = $isTest ? $this->storeManager->getStore()->getConfig(self::TEST_JWT_NAME) : $this->storeManager->getStore()->getConfig(self::JWT_NAME);
			$data['jwt_secret_key'] = $this->enc->decrypt($isTest ? $this->storeManager->getStore()->getConfig(self::TEST_JWT_SECRET_KEY) : $this->storeManager->getStore()->getConfig(self::JWT_SECRET_KEY));

			if (!isset($data['jwt_secret_key']) || !isset($data['jwt_name'])) {
				$this->logger->addDebug('API Payment fail, Error: Empty jwt_secret_key or jwt_name');
			}

			$iat = time();
			$iss = $data['jwt_name'];
			$secretkey = $data['jwt_secret_key'];

			$payload = array(
				'payload' =>
					array(
						'mainamount' => $mainAmount,
						'sitereference' => $isTest ? $this->storeManager->getStore()->getConfig(self::TEST_SITE) : $this->storeManager->getStore()->getConfig(self::SITE),
						'currencyiso3a' => $currency,
						'accounttypedescription' => $accountType,
						'orderreference' => $order->getIncrementId(),
						'settlestatus' => ($this->scopeConfig->getValue('payment/api_secure_trading/api_payment_action', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == 'authorize') ? 2 : $this->scopeConfig->getValue('payment/api_secure_trading/api_settle_status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
						'settleduedate' => $this->subscriptionHelper->getSettleduedate($this->storeManager->getStore()->getConfig('payment/api_secure_trading/api_settle_due_date')),
					),
				'iat' => $iat,
				'iss' => $iss);

			if(isset($data['parenttransactionreference'])){
                $payload['payload']['parenttransactionreference'] = $data['parenttransactionreference'];
            }

			if ($order->getShippingAddress()) {
				$shippingAddress = $this->setShippingAddress($order);
			}

			if (is_array($subscriptionData = $this->subscriptionHelper->_processSubscriptionInPayLoad($order->getAllItems(), $array['amount'], $currency))) {
				$payload['payload'] = array_merge($payload['payload'], $subscriptionData);
				$requestTypes = ($payload['payload']['subscriptiontype'] === "RECURRING") ? "RECURRING" : "INSTALLMENT";
				$skip = ($payload['payload']['skipthefirstpayment'] === '0') ? 0 : 1;
			}
			//check vault api_secure_trading
			if ($data['is_vault'] ?? false) {
                $payload['payload']['credentialsonfile'] = 2;
            }
			$payload['payload'] = array_merge($payload['payload'], $shippingAddress);

			$data = [];

			$jwt = $this->jwt->encode($payload, $secretkey);
			$this->logger->addDebug('--- JWT : ' . $jwt);

			$data['jwt'] = $jwt;

			//save JWT
            $payment = $order->getPayment();
            $payment->setAdditionalInformation('jwt', $jwt);
            $payment->save();

			$data['requesttypes'] = $requestTypes;
			$data['skip'] = $skip;
			$data['mainamount'] = $mainAmount;

			$this->jsonFactory->setData($data);
			return $this->jsonFactory;
		} catch (\Exception $e) {
			$this->logger->addDebug('API Payment fail, Error:' . $e->getMessage());
			$this->messageManager->addErrorMessage("Something went wrong");
		}
	}

    /**
     * @param $order
     * @return array
     */
    public function setShippingAddress($order)
	{
		$shippingAddress =
			[
				'billingprefixname' => $order->getBillingAddress()->getPrefix(),
				'billingfirstname' => $order->getBillingAddress()->getFirstname(),
				'billingmiddlename' => $order->getBillingAddress()->getMiddlename(),
				'billinglastname' => $order->getBillingAddress()->getLastname(),
				'billingstreet' => $order->getBillingAddress()->getStreetLine1(),
				'billingtown' => $order->getBillingAddress()->getCity(),
				'billingcounty' => $order->getBillingAddress()->getRegionCode(),
				'billingpostcode' => $order->getBillingAddress()->getPostcode(),
				'billingcountryiso2a' => $order->getBillingAddress()->getCountryId(),
				'billingemail' => $order->getBillingAddress()->getEmail(),
				'billingtelephone' => $order->getBillingAddress()->getTelephone(),

				'customerprefixname' => $order->getShippingAddress()->getPrefix(),
				'customerfirstname' => $order->getShippingAddress()->getFirstname(),
				'customermiddlename' => $order->getShippingAddress()->getMiddlename(),
				'customerlastname' => $order->getShippingAddress()->getLastname(),
				'customerstreet' => $order->getShippingAddress()->getStreetLine1(),
				'customertown' => $order->getShippingAddress()->getCity(),
				'customercounty' => $order->getShippingAddress()->getRegionCode(),
				'customerpostcode' => $order->getShippingAddress()->getPostcode(),
				'customercountryiso2a' => $order->getShippingAddress()->getCountryId(),
				'customeremail' => $order->getShippingAddress()->getEmail(),
				'customertelephone' => $order->getShippingAddress()->getTelephone(),

				'customfield5' => $this->subscriptionHelper->getVersionInformation(),
			];
		return $shippingAddress;
	}
}
