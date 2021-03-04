<?php
declare(strict_types=1);
/**
 * @copyright: Copyright © 2017 Firebear Studio. All rights reserved.
 * @author   : Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\ConfigurableProducts\Helper;

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Model\StockRegistry;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeHandler;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper implements ArgumentInterface
{
    const XML_PATH_CONFIG_COINPAYMENTS = 'firebear_configurableproducts/';
    protected $fields = ['x_axis', 'y_axis'];
    protected $filterProvider;
    protected $storeManager;
    /**
     * @var ConfigurableAttributeHandler
     */
    protected $configurableAttributeHandler;
    private $containerData;
    private $catalogProduct;
    private $stockRegistry;
    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * Data constructor.
     * @param Context $context
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param StockRegistry $stockRegistry
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param ConfigurableAttributeHandler $configurableAttributeHandler
     * @param HttpContext|null $httpContext
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Helper\Product $catalogProduct,
        StockRegistry $stockRegistry,
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        ConfigurableAttributeHandler $configurableAttributeHandler,
        HttpContext $httpContext = null
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->catalogProduct = $catalogProduct;
        $this->filterProvider = $filterProvider;
        $this->storeManager = $storeManager;
        $this->configurableAttributeHandler = $configurableAttributeHandler;
        $this->httpContext = $httpContext ?: ObjectManager::getInstance()->get(HttpContext::class);
        parent::__construct($context);
        $this->defineContainerData();
    }

    /**
     * @param $moduleName
     * @return bool
     */
    public function isModuleEnabled($moduleName)
    {
        return $this->_moduleManager->isEnabled($moduleName);
    }

    public function defineContainerData()
    {
        $this->containerData = [
            'container_x_axis' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'breakLine' => '',
                            'label' => '',
                            'required' => 0,
                            'sortOrder' => 0,
                            'component' => 'Magento_Ui/js/form/components/group',
                            'dataScope' => ''
                        ]
                    ]
                ],
                'children' => [
                    'x_axis' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType' => 'text',
                                    'formElement' => 'select',
                                    'visible' => 1,
                                    'required' => 0,
                                    'notice' => 'Select an attribute code for the X axis',
                                    'default' => '',
                                    'label' => 'Attribute code for matrix X axis',
                                    'code' => 'attribute_for_x_axis',
                                    'source' => '',
                                    'globalScope' => '',
                                    'sortOrder' => 1,
                                    'componentType' => 'field',
                                    'options' => $this->getAttributesOptionsForMatrix()
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'container_y_axis' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'breakLine' => '',
                            'label' => '',
                            'required' => 0,
                            'sortOrder' => 0,
                            'component' => 'Magento_Ui/js/form/components/group',
                            'dataScope' => ''
                        ]
                    ]
                ],
                'children' => [
                    'y_axis' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType' => 'text',
                                    'formElement' => 'select',
                                    'visible' => 1,
                                    'required' => 0,
                                    'notice' => 'Select an attribute code for the Y axis',
                                    'default' => '',
                                    'label' => 'Attribute code for matrix Y axis',
                                    'code' => 'attribute_for_y_axis',
                                    'source' => '',
                                    'globalScope' => '',
                                    'sortOrder' => 2,
                                    'componentType' => 'field',
                                    'options' => $this->getAttributesOptionsForMatrix()
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'container_linked_attributes' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'breakLine' => '',
                            'label' => '',
                            'required' => 0,
                            'sortOrder' => 0,
                            'component' => 'Magento_Ui/js/form/components/group',
                            'dataScope' => ''
                        ]
                    ]
                ],
                'children' => [
                    'linked_attributes' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType' => 'text',
                                    'formElement' => 'multiselect',
                                    'visible' => 1,
                                    'required' => 0,
                                    'notice' => '',
                                    'default' => '',
                                    'label' => 'Attributes list',
                                    'code' => 'linked_attributes',
                                    'source' => '',
                                    'globalScope' => '',
                                    'sortOrder' => 1,
                                    'componentType' => 'field',
                                    'options' => $this->getAttributesOptions(),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'container_display_matrix' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => 'container',
                            'componentType' => 'container',
                            'breakLine' => '',
                            'label' => '',
                            'required' => 0,
                            'sortOrder' => 0,
                            'component' => 'Magento_Ui/js/form/components/group',
                            'dataScope' => ''
                        ]
                    ]
                ],
                'children' => [
                    'display_matrix' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'dataType' => 'string',
                                    'formElement' => 'select',
                                    'visible' => 1,
                                    'required' => 0,
                                    'notice' => '',
                                    'default' => '',
                                    'label' => 'Display matrix or swatch',
                                    'code' => 'display_matrix',
                                    'source' => '',
                                    'globalScope' => '',
                                    'sortOrder' => 1,
                                    'componentType' => 'field',
                                    'options' => $this->getDisplayAttributesOptions(),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * Get a list of product attributes for matrix
     *
     * @return array
     */
    public function getAttributesOptionsForMatrix()
    {
        $attributesOptions = [];
        $attributesOptions[] = ['value' => 'default', 'label' => 'Use extension settings'];
        foreach ($this->configurableAttributeHandler->getApplicableAttributes() as $attributes) {
            if ($this->configurableAttributeHandler->isAttributeApplicable($attributes)) {
                $attributesOptions[] = [
                    'value' => $attributes->getAttributeCode(),
                    'label' => $attributes->getAttributeCode()
                ];
            }
        }
        return $attributesOptions;
    }

    /**
     * Get a list of product attributes
     *
     * @return array
     */
    public function getAttributesOptions()
    {
        $attributesOptions = [];
        foreach ($this->configurableAttributeHandler->getApplicableAttributes() as $attributes) {
            if ($this->configurableAttributeHandler->isAttributeApplicable($attributes)) {
                $attributesOptions[] = [
                    'value' => $attributes->getAttributeId(),
                    'label' => $attributes->getAttributeCode()
                ];
            }
        }
        return $attributesOptions;
    }

    public function getDisplayAttributesOptions()
    {
        return [
            ['value' => '0', 'label' => __('Use extension settings')],
            ['value' => '1', 'label' => __('Matrix')],
            ['value' => '2', 'label' => __('Swatch')],
        ];
    }

    /**
     * @param $attr
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getAttrContent($attr)
    {
        $store_id = $this->storeManager->getStore()->getId();
        return $this->filterProvider->getBlockFilter()->setStoreId($store_id)->filter($attr);
    }

    /**
     * @return array
     */
    public function getContainerData()
    {
        return $this->containerData;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function getChildInStock($product)
    {
        $inStock = [];
        foreach ($this->getAllowProducts($product) as $_product) {
            $stockItem = $this->stockRegistry->getStockItem($_product->getId(), 1);
            $saleable = $stockItem->getIsInStock();
            if ($saleable) {
                $inStock[] = $_product;
            }
        }

        return $inStock;
    }

    /**
     * @param $product
     *
     * @return Product[]
     */
    public function getAllowProducts($product)
    {
        $skipSaleableCheck = $this->catalogProduct->getSkipSaleableCheck();
        return $skipSaleableCheck ? $product->getTypeInstance()->getUsedProducts($product, null) :
            $product->getTypeInstance()->getSalableUsedProducts($product, null);
    }

    /**
     * Hide price for unregistered users
     *
     * return bool
     */
    public function hidePrice()
    {
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return ($this->getGeneralConfig('general/hide_price') && !$isLoggedIn);
    }

    /**
     * @param      $code
     * @param null $storeId
     *
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_CONFIG_COINPAYMENTS . $code, $storeId);
    }

    /**
     * @param      $field
     * @param null $storeId
     *
     * @return mixed
     */
    private function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
