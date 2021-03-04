<?php


namespace Elevate\Discontinuedproducts\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Discontinuedproducts
 *
 * @category Elevate
 * @package  Elevate\Discontinuedproducts\Block
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Discontinuedproducts extends \Magento\Catalog\Block\Product\View
{
  /**
   * @var \Magento\Framework\Registry
   */
  protected $_registry;

  /**
   * @var \Magento\Variable\Model\VariableFactory
   */
  protected $_varFactory;


    /**
     * Magento string lib
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     * @deprecated 102.0.0
     */
    protected $priceCurrency;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    /**
     * @var \Magento\Catalog\Helper\Product
     */
    protected $_productHelper;

    /**
     * @var \Magento\Catalog\Model\ProductTypes\ConfigInterface
     */
    protected $productTypeConfig;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    protected $_localeFormat;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param Context $context
     * @param \Magento\Variable\Model\VariableFactory $varFactory
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface|\Magento\Framework\Pricing\PriceCurrencyInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param array $data
     * @codingStandardsIgnoreStart
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Variable\Model\VariableFactory $varFactory,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_productHelper = $productHelper;
        $this->_varFactory = $varFactory;
        $this->urlEncoder = $urlEncoder;
        $this->_jsonEncoder = $jsonEncoder;
        $this->productTypeConfig = $productTypeConfig;
        $this->string = $string;
        $this->_localeFormat = $localeFormat;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->priceCurrency = $priceCurrency;
        $this->_registry = $registry;
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
    }

    /**
     * Retrieve current Product object
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getCurrentProduct()
    {
      return $this->_registry->registry('current_product');
    }

  /**
   * Get Custom Variable Value
   *
   * @param string $var_to_get
   *
   * @return string
   */
  public function getVariableValue(string $var_to_get) {
        $var = $this->_varFactory->create();
        $var->loadByCode($var_to_get);
        return $var->getValue();
    }

    public function getDiscontinuedProductShowProductPageSectionTitle() {
        $storeId = $this->_storeManager->getStore()->getId();

        return $this->_scopeConfig->getValue('discontinuedproducts/general/show_pp_title', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getDiscontinuedProductProductPageSectionTitle() {
        $storeId = $this->_storeManager->getStore()->getId();

        return $this->_scopeConfig->getValue('discontinuedproducts/general/pp_title', ScopeInterface::SCOPE_STORE, $storeId);

    }
}
