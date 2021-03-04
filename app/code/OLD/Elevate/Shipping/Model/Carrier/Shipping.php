<?php


namespace Elevate\Shipping\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Config;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Psr\Log\LoggerInterface;

class Shipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = 'evship';

    /**
     * Whether this carrier has fixed rates calculation
     *
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }
   protected function toshiShippingAllowed($country, $postcode)
    {
  //  return false;
        // Are we in the uk?
        if($country != 'GB')
        {
            return false;
        }
        $z12_postcodes = array(
            "E1",
            "EC1A",
            "EC1M",
            "EC1N",
            "EC1P",
            "EC1R",
            "EC1V",
            "EC1Y",
            "EC2A",
            "EC2M",
            "EC2N",
            "EC2P",
            "EC2R",
            "EC2V",
            "EC2Y",
            "EC3A",
            "EC3M",
            "EC3N",
            "EC3P",
            "EC3R",
            "EC3V",
            "EC4A",
            "EC4M",
            "EC4N",
            "EC4P",
            "EC4R",
            "EC4V",
            "EC4Y",
            "N1",
            "NW1",
            "SE1",
            "SW1",
            "SW7",
            "W1",
            "W2",
            "W11",
            "WC1",
            "WC2",
            "E1",
            "E2",
            "E3",
            "E5",
            "E8",
            "E14",
            "SE2",
            "SE4",
            "SE5",
            "SE8",
            "SE11",
            "SE14",
            "SE15",
            "SE16",
            "SE17",
            "SE22",
            "SW2",
            "SW3",
            "SW4",
            "SW5",
            "SW6",
            "SW8",
            "SW9",
            "SW10",
            "SW11",
            "W1",
            "W2",
            "W6",
            "W8",
            "W9",
            "W10",
            "W12",
            "W14",
            "N1",
            "N4",
            "N5",
            "N7",
            "N19",
            "NW3",
            "NW5",
            "NW6",
            "NW7",
            "NW8",
            "NW10"          );

        $z3_postcodes = array(
            "N22",
            "E16",
            "N8",
            "SW12",
            "SW16",
            "NW11",
            "NW11",
            "W5",
            "W4",
            "SE27",
            "SW14",
            "SW18",
            "N2",
            "NW10",
            "SW14",
            "N2",
            "SW13",
            "SE18",
            "SW18",
            "N17",
            "SE10",
            "NW4",
            "NW11",
            "NW11",
            "SW17"
        );

        $all_postcodes = array_merge($z12_postcodes, $z3_postcodes);
        $postcode = strtoupper($postcode);
        foreach($all_postcodes as $p)
        {
            if($this->startsWith($postcode, $p))
                return true;
        }

        return false;
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    /**
     * Generates list of allowed carrier`s shipping methods
     * Displays on cart price rules page
     *
     * @return array
     * @api
     */
    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => __($this->getConfigData('name'))];
    }

    /**
     * Collect and get rates for storefront
     *
     * @param RateRequest $request
     * @return DataObject|bool|null
     * @api
     */
    public function collectRates(RateRequest $request)
    {
        /**
         * Make sure that Shipping method is enabled
         */
        if (!$this->isActive()) {
            return false;
        }
            if(! $this->toshiShippingAllowed('GB', $request->getDestPostcode())){
          return false;
          }
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        $shippingPrice = $this->getConfigData('price');

        $method = $this->_rateMethodFactory->create();

        /**
         * Set carrier's method data
         */
        $method->setCarrier($this->getCarrierCode());
        $method->setCarrierTitle($this->getConfigData('title'));

        /**
         * Displayed as shipping method under Carrier
         */
        $method->setMethod($this->getCarrierCode());
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        $result->append($method);

        return $result;
    }
}