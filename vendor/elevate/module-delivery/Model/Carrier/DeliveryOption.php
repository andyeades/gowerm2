<?php


namespace Elevate\Delivery\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class DeliveryOption extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{

    protected $_code = 'deliveryoption';

    protected $_isFixed = true;

    protected $_rateResultFactory;

    protected $_rateMethodFactory;

    /**
     * Session
     *
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $session;

    protected $cartSession;

    protected $deliveryMethod;

    protected $deliveryFee;

    protected $elevateHelper;
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Checkout\Model\Session $cartSession,
        \Elevate\Delivery\Api\DeliveryMethodRepositoryInterface $deliveryMethod,
        \Elevate\Delivery\Api\DeliveryFeeRepositoryInterface $deliveryFee,
        \Elevate\Delivery\Helper\General $elevateHelper,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->session = $session;
        $this->cartSession = $cartSession;
        $this->deliveryMethod = $deliveryMethod;
        $this->deliveryFee = $deliveryFee;
        $this->elevateHelper = $elevateHelper;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $checkoutSession = $objectManager->create('Magento\Checkout\Model\Session');
        $quote = $checkoutSession->getQuote();

        $address = $quote->getShippingAddress();

        $delivery_date_selected = $address->getData('delivery_date_selected');
        $delivery_option_selected = $address->getData('delivery_option_selected');
        $delivery_area = $address->getData('delivery_area_selected');
        $delivery_selected_summarytext = $address->getData('delivery_selected_summarytext');


        if (!empty($delivery_date_selected)) {
            if ($delivery_date_selected != '0000-00-00 00:00:00') {

                $delivery_method = $this->deliveryMethod->getById($delivery_option_selected);
                $delivery_fees = $this->elevateHelper->getDeliveryFees($delivery_option_selected,$delivery_area,'day', 'ASC');

                $deliverymethod_date = explode(' ',$delivery_date_selected);

                $arr_location = date("N",strtotime($deliverymethod_date[0]));

                if (isset($delivery_fees[$arr_location])) {
                    $shippingPrice = $delivery_fees[$arr_location]['fee'];
                } else {
                    $shippingPrice = 0;
                    // Log error
                }


            } else {
                $shippingPrice = $this->getConfigData('price');
            }
        } else {
            $shippingPrice = $this->getConfigData('price');
        }


        $result = $this->_rateResultFactory->create();

        if ($shippingPrice !== false) {
            $method = $this->_rateMethodFactory->create();

            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name'));

            if (!empty($delivery_date_selected)) {
                if ($delivery_date_selected != '0000-00-00 00:00:00') {
                    // This is what is saved in the quote_shipping_rates table (which will be used after page reload as the name!)

                    $method->setMethodTitle($delivery_selected_summarytext);
                }
            }




            if ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
                $shippingPrice = '0.00';
            }

            $method->setPrice($shippingPrice);
            $method->setCost($shippingPrice);

            $result->append($method);
        }

        return $result;
    }

    /**
     * getAllowedMethods
     *
     * @param array
     */
    public function getAllowedMethods()
    {
        return ['flatrate' => $this->getConfigData('name')];
    }
}
