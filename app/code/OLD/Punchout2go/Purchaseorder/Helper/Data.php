<?php
namespace Punchout2go\Purchaseorder\Helper;

//use Symfony\Component\Config\Definition\Exception\Exception;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var \Punchout2go\Purchaseorder\Logger\Handler\Debug  */
    protected $punchout2goLogger;

    /** @var \Magento\Directory\Model\RegionFactory */
    protected $_regionFactory;

    protected $moduleListInterface;

    /**
     * Data constructor.
     *
     * @param Magento\Framework\App\Helper\Context $context
     * @param Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Magento\Catalog\Model\Product $product
     * @param Magento\Framework\Data\Form\FormKey $formKey,
     * @param Punchout2go\Purchaseorder\Model\Quote $quoteFactory,
     * @param Magento\Customer\Model\CustomerFactory $customerFactory,
     * @param Magento\Sales\Model\Service\OrderService $orderService,
     * @param Magento\Directory\Model\RegionFactory $regionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Punchout2go\Purchaseorder\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Punchout2go\Purchaseorder\Logger\Handler\Debug $punchout2go_logger,
        \Magento\Framework\Module\ModuleListInterface $moduleListInterface
    ){
        $this->storeManager = $storeManager;
        $this->product = $product;
        $this->formKey = $formKey;
        $this->quoteFactory = $quoteFactory;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
        $this->_regionFactory = $regionFactory;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->punchout2goLogger = $punchout2go_logger;
        $this->moduleListInterface = $moduleListInterface;
        parent::__construct($context);
    }

    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfigFlag($config_path)
    {
        return $this->scopeConfig->isSetFlag(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $params the entire order stdObj
     * @return bool
     */
    public function isAuthenticatedByApiKey($apiKey)
    {
        $expectedApiKey = $this->getConfig('punchout2go_purchaseorder/authentication/api_key');
        return $expectedApiKey === base64_decode($apiKey);
    }

    /**
     * Currently not used
     * @param $params the entire order stdObj
     * @return bool
     */
    public function isAuthenticatedByHeaderData($params)
    {
        $expectedFromIdentity = $this->getConfig('punchout2go_purchaseorder/authentication/from_identity');
        $expectedToIdentity = $this->getConfig('punchout2go_purchaseorder/authentication/to_identity');
        $expectedSharedSecret = $this->getConfig('punchout2go_purchaseorder/authentication/shared_secret');
        $providedFromIdentity = $params->header->from_identity;
        $providedToIdentity = $params->header->to_identity;
        $providedSharedSecret = $params->header->shared_secret;

        return ($expectedFromIdentity == $providedFromIdentity &&
            $expectedToIdentity == $providedToIdentity &&
            $expectedSharedSecret == $providedSharedSecret);
    }

    /**
     * @param $regionCode
     * @param $countryId
     */
    public function getRegionIdFromCode($regionCode, $countryId)
    {
        $directory = $this->_regionFactory->create();
        $directory->loadByCode($regionCode, $countryId);
        if (!is_numeric($directory->getId())) {
            $collection = $directory->getCollection();
            $collection->addFieldToFilter('country_id', $countryId);
            $collection->addFieldToFilter('default_name', $regionCode);
            if ($collection->count() == 1) {
                return $collection->getFirstItem()->getId();
            }
            return $directory->getId();
        }
        return false;
    }

    /**
     * @param string $fullName
     * return array
     */
    public function getNameData($fullName)
    {
        if (is_string($fullName)) {
            if (preg_match('/^([^,]+),(.+)$/',$fullName,$s)) {
                $nameArray = array (
                    trim($s[2]),
                    trim($s[1])
                );
            } else {
                $nameArray = explode(' ',$fullName);
            }
        }
        $first = array_shift($nameArray);
        $returnName = array();
        $returnName[0] = $first;
        $returnName[1] = implode(" ", $nameArray);
        return $returnName;
    }

    /**
     * Grabs this modules version number for display in the configuration for Purchase Order.
     *
     * @return string
     */
    public function getModuleVersion()
    {
        $data = $this->moduleListInterface->getOne('Punchout2go_Purchaseorder');
        if (isset($data['setup_version'])) {
            $version = $data['setup_version'];
        } else {
            $version = "";
            //$version = null; // unknown
        }
        return $version; // string like "2.0.0"
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param \Punchout2go\Purchaseorder\Model\Quote $quote
     * @return int
     */
    public function setNewPurchaseOrderStatus($order, $quote)
    {
        if ($quote->getIsPurchaseOrderRequest()) {
            $this->debug("Checking for POR custom status.");
            // pull status from config
            $status = $this->getConfig(
                'punchout2go_purchaseorder/orders/successful_order_status',
                $quote->getStoreId());
            if (!empty($status)) {
                if ($status == $order->getStatus()) {
                    $this->debug("Status already set to {$order->getStatus()}");
                } else {
                    $this->debug("Default status : {$status}");
                    // only set a status if one is defined, otherwise it uses the store default.
                    $order->setState($status)->setStatus($status);
                    $this->debug("order state : {$order->getState()}; order status : {$order->getStatus()}");
                    $order->save();
                    return true;
                }
            } else {
                $this->debug("No custom value");
            }
        }
        return false;
    }

    /**
     * log data.
     *
     * @param string $string the string you want to log.
     * @param array  $context
     *
     * @internal param bool $force force the logging regardless of setting.
     */
    public function debug($string, array $context = array())
    {
        if ($this->getConfigFlag('punchout2go_purchaseorder/system/logging')) {
            $this->punchout2goLogger->simple_log($string, $context);
        }
    }

    /**
     * @param $string
     */
    public function throwException($string)
    {
        $this->debug($string);
        throw new \Exception($string, 100);
    }
}