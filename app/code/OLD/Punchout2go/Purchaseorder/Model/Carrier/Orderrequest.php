<?php
namespace Punchout2go\Purchaseorder\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

/**
 * http://inchoo.net/magento-2/creating-a-shipping-method-in-magento-2/
 * Class Purchaseorder
 * @package Punchout2go\Purchaseorder\Model\Carrier
 */
class Orderrequest extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'orderrequest';

    /** @var \Magento\Framework\App\State $appState */
    protected $appState;

    /** @var \Magento\Framework\Registry $registry */
    protected $registry;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    /** @var \Punchout2go\Purchaseorder\Helper\Data  */
    protected $_helper;

    /** @var RateRequest */
    protected $_current_request = null;

    /**
     * Purchaseorder constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Registry $registry
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Registry $registry,
        \Punchout2go\Purchaseorder\Helper\Data $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->logger = $logger;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->appState = $appState;
        $this->registry = $registry;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->_helper->debug('load procurement carrier');
    }

    /**
     * Checks if user is logged in as admin
     *
     * @return bool
     */
    protected function isAdmin()
    {
        return ($this->appState->getAreaCode() === \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE);
    }

    protected function isFrontend()
    {
        return ($this->appState->getAreaCode() === \Magento\Framework\App\Area::AREA_FRONTEND);
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['example' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        $this->_current_request = $request;
        if (!$this->isAdmin() && !$this->registry->registry('is_po2go_purchase_order')) {
            $this->_helper->debug("frontend session");
            return false;
        } elseif (!$this->getConfigFlag('active')) {
            $this->_helper->debug("order request shipping inactive");
            return false;
        } else {
            $this->_helper->debug('allowing method : '. $request->getStoreId());
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('orderrequest');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('orderrequest');
        $method->setMethodTitle($this->getConfigData('name'));

        /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
        $amount = $this->getConfigData('price');

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);

        return $result;
    }

    public function getStore ()
    {
        $store = parent::getStore();
        if (empty($store)
            && !empty($this->_current_request)) {
            $store = $this->_current_request->getStoreId();
        }
        //$this->_helper->debug("store :". var_dump($store));
        return $store;
    }

}