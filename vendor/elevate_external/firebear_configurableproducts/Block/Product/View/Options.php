<?php
declare(strict_types=1);

namespace Firebear\ConfigurableProducts\Block\Product\View;

use LogicException;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Value;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Options
 * @package Firebear\ConfigurableProducts\Block\Product\View
 */
class Options extends Template
{
    /**
     * @var Product
     */
    protected $_product;

    /**
     * Product option
     *
     * @var Option
     */
    protected $_option;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_registry = null;

    /**
     * Catalog product
     *
     * @var Product
     */
    protected $_catalogProduct;

    /**
     * @var EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * @var Data
     */
    protected $_catalogData;

    /**
     * @var ArrayUtils
     */
    protected $arrayUtils;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param Context $context
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param Data $catalogData
     * @param EncoderInterface $jsonEncoder
     * @param Option $option
     * @param Registry $registry
     * @param ArrayUtils $arrayUtils
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        Data $catalogData,
        EncoderInterface $jsonEncoder,
        Option $option,
        Registry $registry,
        ArrayUtils $arrayUtils,
        ProductRepository $productRepository,
        array $data = []
    ) {
        $this->pricingHelper = $pricingHelper;
        $this->_catalogData = $catalogData;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_registry = $registry;
        $this->_option = $option;
        $this->arrayUtils = $arrayUtils;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        if ($this->getOptions()) {
            return true;
        }
        return false;
    }

    /**
     * Get product options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->getProduct()->getOptions();
    }

    /**
     * Retrieve product object
     *
     * @return Product
     * @throws NoSuchEntityException
     */
    public function getProduct()
    {
        if (!$this->_product) {
            if ($this->getRequest()->getParam('productId')) {
                $this->_product = $this->productRepository->getById($this->getRequest()->getParam('productId'));
            } else {
                throw new LogicException('Product is not defined');
            }
        }
        return $this->_product;
    }

    /**
     * Set product object
     *
     * @param Product $product
     * @return Options
     */
    public function setProduct(Product $product = null)
    {
        $this->_product = $product;
        return $this;
    }

    /**
     * Get json representation of
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = [];
        foreach ($this->getOptions() as $option) {
            /* @var $option Option */
            if ($option->getGroupByType() == Option::OPTION_GROUP_SELECT) {
                $tmpPriceValues = [];
                foreach ($option->getValues() as $valueId => $value) {
                    $tmpPriceValues[$valueId] = $this->_getPriceConfiguration($value);
                }
                $priceValue = $tmpPriceValues;
            } else {
                $priceValue = $this->_getPriceConfiguration($option);
            }
            $config[$option->getId()] = $priceValue;
        }

        $configObj = new DataObject(
            [
                'config' => $config,
            ]
        );

        //pass the return array encapsulated in an object for the other modules to be able to alter it eg: weee
        if (!$this->_registry->registry('current_product')) {
            $this->_registry->register('current_product', $this->getProduct());
        }

        $this->_eventManager->dispatch('catalog_product_option_price_configuration_after', ['configObj' => $configObj]);

        $config = $configObj->getConfig();

        return $this->_jsonEncoder->encode($config);
    }

    /**
     * Get price configuration
     *
     * @param Value|Option $option
     * @return array
     */
    protected function _getPriceConfiguration($option)
    {
        $optionPrice = $this->pricingHelper->currency($option->getPrice(true), false, false);
        $data = [
            'prices' => [
                'oldPrice' => [
                    'amount' => $this->pricingHelper->currency($option->getRegularPrice(), false, false),
                    'adjustments' => [],
                ],
                'basePrice' => [
                    'amount' => $this->_catalogData->getTaxPrice(
                        $option->getProduct(),
                        $optionPrice,
                        false,
                        null,
                        null,
                        null,
                        null,
                        null,
                        false
                    ),
                ],
                'finalPrice' => [
                    'amount' => $this->_catalogData->getTaxPrice(
                        $option->getProduct(),
                        $optionPrice,
                        true,
                        null,
                        null,
                        null,
                        null,
                        null,
                        false
                    ),
                ],
            ],
            'type' => $option->getPriceType(),
            'name' => $option->getTitle()
        ];
        return $data;
    }

    /**
     * Get option html block
     *
     * @param Option $option
     * @return string
     */
    public function getOptionHtml(Option $option)
    {
        $type = $this->getGroupOfOption($option->getType());
        $renderer = $this->getChildBlock($type);
        $renderer->setProduct($this->getProduct())->setOption($option);
        return $this->getChildHtml($type, false);
    }

    /**
     * @param string $type
     * @return string
     */
    public function getGroupOfOption($type)
    {
        $group = $this->_option->getGroupByType($type);

        return $group == '' ? 'default' : $group;
    }

    /**
     * Decorate a plain array of arrays or objects
     *
     * @param array $array
     * @param string $prefix
     * @param bool $forceSetAll
     * @return array
     */
    public function decorateArray($array, $prefix = 'decorated_', $forceSetAll = false)
    {
        return $this->arrayUtils->decorateArray($array, $prefix, $forceSetAll);
    }
}
