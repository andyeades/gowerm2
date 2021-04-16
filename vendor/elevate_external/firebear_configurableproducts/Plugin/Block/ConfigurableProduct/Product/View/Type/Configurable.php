<?php
/**
 * Copyright © 2017 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type;

use Firebear\ConfigurableProducts\Model\Product\Defaults;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Module\Manager;
use Magento\Framework\Registry;
use \Magento\Framework\Json\EncoderInterface;
use \Magento\Framework\Json\DecoderInterface;
use Magento\Swatches\Helper\Data as SwatchesHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\CatalogInventory\Api\StockStateInterface;
use Firebear\ConfigurableProducts\Helper\Data as CpiHelper;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable as ConfigurableProductObject;
use Magento\Framework\App\ResourceConnection;


class Configurable
{
    const ALL_CUSTOMER_GROUPS = 32000;

    /**
     * Core registry.
     *
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var DecoderInterface
     */
    private $jsonDecoder;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Defaults
     */
    private $productDefaults;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * @var SwatchesHelper
     */
    private $swatchesHelper;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var []
     */
    private $settings;

    /**
     * @var \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable
     */
    private $subject;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    private $layout;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    private $stockStateInterface;

    /**
     * @var \Firebear\ConfigurableProducts\Helper\Data
     */
    private $cpiHelper;

    /**
     * @var \Magento\CatalogInventory\Api\StockConfigurationInterface
     */
    private $stockConfigurationInterface;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    private $configurableProductObject;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlInterface;

    /**
     * @var \Firebear\ConfigurableProducts\Model\ProductOptionsRepository
     */
    private $productOptionsRepository;

    /**
     * @var ResourceConnection
     */
    protected  $resourceConnection;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param Registry                   $coreRegistry
     * @param EncoderInterface           $jsonEncoder
     * @param DecoderInterface           $jsonDecoder
     * @param ScopeConfigInterface       $scopeConfig
     * @param Defaults                   $productDefaults
     * @param Manager                    $moduleManager
     * @param ProductRepositoryInterface $productRepository
     * @param SwatchesHelper             $swatchesHelper
     * @param RequestInterface           $request
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        Registry $coreRegistry,
        EncoderInterface $jsonEncoder,
        DecoderInterface $jsonDecoder,
        ScopeConfigInterface $scopeConfig,
        Defaults $productDefaults,
        Manager $moduleManager,
        ProductRepositoryInterface $productRepository,
        SwatchesHelper $swatchesHelper,
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        StockStateInterface $stockStateInterface,
        CpiHelper $cpiHelper,
        StockConfigurationInterface $stockConfigurationInterface,
        ConfigurableProductObject $configurableProductObject,
        \Magento\Framework\UrlInterface $urlInterface,
        \Firebear\ConfigurableProducts\Model\ProductOptionsRepository $productOptionsRepository,
        ResourceConnection $resourceConnection,
        Session $customerSession
    ) {
        $this->coreRegistry                = $coreRegistry;
        $this->jsonEncoder                 = $jsonEncoder;
        $this->jsonDecoder                 = $jsonDecoder;
        $this->scopeConfig                 = $scopeConfig;
        $this->productDefaults             = $productDefaults;
        $this->moduleManager               = $moduleManager;
        $this->productRepository           = $productRepository;
        $this->swatchesHelper              = $swatchesHelper;
        $this->request                     = $request;
        $this->storeManager                = $storeManager;
        $this->stockStateInterface         = $stockStateInterface;
        $this->cpiHelper                   = $cpiHelper;
        $this->stockConfigurationInterface = $stockConfigurationInterface;
        $this->configurableProductObject   = $configurableProductObject;
        $this->urlInterface                = $urlInterface;
        $this->productOptionsRepository    = $productOptionsRepository;
        $this->resourceConnection = $resourceConnection;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     * @param                                                                   $result
     *
     * @return string
     */
    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {



        if ($this->request->getFullActionName() == 'catalog_category_view' || $this->request->getFullActionName() == 'elevate_landingpages_index_index') {
           // if ($this->cpiHelper->getGeneralConfig('general/disable_swatches_functionallity_in_listing')) {
                 return $result;
           // }
        }
        $this->subject = $subject;
        $this->layout  = $this->subject->getLayout();
        $data          = $this->coreRegistry->registry('firebear_configurableproducts');

        if (isset($data['child_id'])) {
            $productId = $data['child_id'];
        } else {
            $productId = $this->subject->getProduct()->getId();
        }
        $config = $this->jsonDecoder->decode($result);

        $enablePreselect = $this->cpiHelper->getGeneralConfig('general/enable_preselect');
        if ($enablePreselect) {
            /**
             * Prepare default values for configurable product
             */
            $isProductHasSwatch = $this->swatchesHelper->isProductHasSwatch($this->subject->getProduct());

            $defaultValues = $this->prepareDefaultValues($config, $productId, $isProductHasSwatch);

            $usedProductId = $this->productDefaults->getDefaultProductId($this->subject->getProduct());

            if ((empty($defaultValues) || count($config['attributes']) != count($defaultValues)) && $usedProductId) {
                $defaultValues = $this->prepareDefaultValues($config, $usedProductId, $isProductHasSwatch);
            }
            $config['defaultValues'] = $defaultValues;
        }
        $config['currencySymbol'] = $this->storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();
        /**
         * Do not replace page content on category view page.
         */
        if ($this->request->getFullActionName() == 'catalog_category_view') {
            $config['doNotReplaceData'] = true;
        }
        if ($this->request->getFullActionName() !== 'catalog_category_view') {
            $config['bundle_id'] = isset($this->subject->getRequest()->getParams()['id']) ?
                $this->subject->getRequest()->getParams()['id'] : null;
        } else {
            $config['bundle_id'] = null;
        }

        /**
         * Prepare simple product attributes, such as name, sku, description
         */
        $config['considerTierPricesInFromToPrice'] = $this->cpiHelper->getGeneralConfig('general/price_range_compatible_with_tier_price');
        $config['hidePrice'] = $this->cpiHelper->hidePrice();
        $config = $this->getOptions($config);
        $config['setOpenGraphUrl'] = $this->urlInterface->getUrl('cpi/product/UpdateOpenGraph');
        if (!$this->cpiHelper->hidePrice()) {
            $config['hidePrice'] = false;
        } else {
            $config['hidePrice'] = true;
            $config['priceText'] = $this->cpiHelper->getGeneralConfig('general/price_text');
        }
        $config['urls']['parent'] = $subject->getProductUrl($subject->getProduct());
        $result = $this->jsonEncoder->encode($config);
        return $result;
    }

    /**
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     * @param                                                                   $result
     *
     * @return mixed
     */
    public function afterGetCacheKeyInfo(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {

        /**
         * Prevent save same cache on category view page and product view page with same default values.
         */
        if ($this->request->getFullActionName() == 'catalog_category_view') {
            $result[] = 'doNotReplaceData';
            return $result;
        }
        if ($subject->getProduct()->getTypeId() == 'configurable') {
            $jsonConfig = $subject->getJsonConfig();
            $config     = $this->jsonDecoder->decode($jsonConfig);

            /**
             * Different cache for different simple products.
             */
            if (isset($config['defaultValues']) && !empty($config['defaultValues'])) {
                $result[] = http_build_query($config['defaultValues']);
            }
        }
        return $result;
    }

    /**
     * Prepare default values.
     *
     * @param $config
     * @param $productId
     * @param $isSwatchEnabled
     *
     * @return array
     */
    private function prepareDefaultValues($config, $productId, $isSwatchEnabled)
    {
        $defaultValues = [];
        foreach ($config['attributes'] as $attributeId => $attribute) {
            foreach ($attribute['options'] as $option) {
                $optionId = $option['id'];
                if (in_array($productId, $option['products']) || count($attribute['options']) == 1) {
                    if ($isSwatchEnabled) {
                        $defaultValues[$attribute['code']] = $optionId;
                    } else {
                        $defaultValues[$attributeId] = $optionId;
                    }
                }
            }
        }

        return $defaultValues;
    }

    /**
     * Get extension settings.
     *
     * @return mixed
     */
    private function getSettings()
    {
        if (!$this->settings) {
            $this->settings = $this->scopeConfig->getValue(
                'firebear_configurableproducts/general',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        }

        return $this->settings;
    }

    /**
     * Get Options
     *
     * @param $config
     *
     * @return mixed
     */
    public function getOptions($config)
    {
        $parentProductId = $config['productId'];
        $customerGroupId = $this->customerSession->getCustomerGroupId();
        if ($parentProductId) {
            $parentProduct = $this->productRepository->getById($parentProductId);
            $productOptions = $this->productOptionsRepository->getByProductId($parentProductId);
            $productAttributes = $parentProduct->getAttributes();
            $config['parentProductName'] = $parentProduct->getName();
            if ($config['bundle_id']) {
                try {
                    $bundleProduct = $this->productRepository->getById($config['bundle_id']);
                } catch (NoSuchEntityException $e) {
                    $bundleProduct = false;
                }
                if ($bundleProduct && $bundleProduct->getTypeId() == 'bundle') {
                    $bundleProductOptions = $this->productOptionsRepository->getByProductId($config['bundle_id']);
                    $config['linkedAttributes'] = explode(',', $bundleProductOptions->getLinkedAttributes());
                    $currentTime = strtotime('now');
                    $specialFromDate = strtotime($bundleProduct->getSpecialFromDate());
                    $specialToDatePrice = strtotime($bundleProduct->getSpecialToDate());
                    $specialPrice = $bundleProduct->getSpecialPrice();
                    if (($specialFromDate <= $currentTime && $currentTime < $specialToDatePrice)
                        || !$specialToDatePrice) {
                        $config['special_price'] = ($specialPrice > 0) ? $specialPrice : false;
                    } else {
                        $config['special_price'] = false;
                    }
                }
            }
            //Get attribute codes for matrix axises
            $xAxis = $productOptions->getXAxis();
            $yAxis = $productOptions->getYAxis();
            if (empty($xAxis)) {
                $xAxis = $this->cpiHelper->getGeneralConfig('matrix/x_axis');
            }
            if (empty($yAxis)) {
                $yAxis = $this->cpiHelper->getGeneralConfig('matrix/y_axis');
            }
            $config['x_matrix_axis']['code'] = $xAxis;
            $config['y_matrix_axis']['code'] = $yAxis;

            foreach ($config['attributes'] as $k => &$value) {
                if (isset($productAttributes[$value['code']])
                    && !$productAttributes[$value['code']]->getSwatchInputType()) {
                    $value['type'] = 'select';
                } else {
                    $value['type'] = '';
                }
                if ($value['code'] == $xAxis) {
                    $config['x_matrix_axis']['label'] = $value['label'];
                    $config['x_matrix_axis']['id'] = $value['id'];
                    $config['x_matrix_axis']['type'] = $value['type'];
                }

                if ($value['code'] == $yAxis) {
                    $config['y_matrix_axis']['label'] = $value['label'];
                    $config['y_matrix_axis']['id'] = $value['id'];
                }
            }
            $config['customAttributes']['parent'] = $this->renderAttributesParent($parentProduct);
        }
        if ($this->cpiHelper->getGeneralConfig('general/change_breadcrumbs')) {
            $config['customAttributes']['parent']['.breadcrumbs .items .product'] = [
                'value' => $parentProduct->getName(),
                'class' => $this->cpiHelper->getGeneralConfig('general/breadcrumbs_id_class')
            ];
        }

        if ($this->stockConfigurationInterface->getManageStock() == 0) {
            $config['configManageStock'] = false;
        } else {
            $config['configManageStock'] = true;
        }

        if ($this->cpiHelper->getGeneralConfig('general/price_range_category_from_to_option')) {
            $config['disaplyingFromToPrice'] = true;
        }

        $settings = $this->getSettings();
        $allowedProducts = $this->subject->getAllowProducts();
        if ($this->cpiHelper->getGeneralConfig('general/price_range_category')) {
            $config['priceRange'] = true;
        }
        if (isset($settings['allow_deselect_swatch']) && $settings['allow_deselect_swatch'] == 1) {
            $config['allow_deselect_swatch'] = true;
        }
        if ($this->cpiHelper->getGeneralConfig('general/price_range_category_original')) {
            $config['defaultPriceWithRange'] = true;
        }
        $config['useCustomOptionsForVariations'] =
            $this->cpiHelper->getGeneralConfig('general/use_custom_options_for_variations');
        $config['loadOptionsUrl'] = $this->urlInterface->getUrl('cpi/product/LoadOptions');
        foreach ($allowedProducts as $product) {
            $productId = $product->getId();
            $product = $this->productRepository->getById($productId);
            $webSiteId = $product->getStore()->getWebsiteId();
            $select = $this->resourceConnection->getConnection()->select()
                ->from($this->resourceConnection->getTableName('cataloginventory_stock_item'))
                ->where('product_id=?', $productId)
                ->where('stock_id', $webSiteId);
            $stock_info = $this->resourceConnection->getConnection()->fetchRow($select);

            $stockQty = (int)$stock_info['qty'];
            $config['stock_info'][$productId]['is_in_stock'] = (int)$stock_info['is_in_stock'];
            $config['stock_info'][$productId]['product_discontinued'] = (int)$product->getData('product_discontinued');

            $config['stockQty'][$productId] = $stockQty;

            if ($this->cpiHelper->getGeneralConfig('matrix/tier_price')) {
                $productTierPrices = $product->getTierPrices();
                $iterator = 0;
                foreach ($productTierPrices as $tierPriceItem) {
                    $tierPriceCustomerGroupId = $tierPriceItem->getCustomerGroupId();
                    if ($tierPriceCustomerGroupId == $customerGroupId ||
                        $tierPriceCustomerGroupId == self::ALL_CUSTOMER_GROUPS) {
                        $config['tierPrice'][$productId]['qty'][$iterator] = $tierPriceItem->getQty();
                        $config['tierPrice'][$productId]['price'][$iterator] = $tierPriceItem->getValue();
                        $config['tierPrice2'][$productId]['price'][round($tierPriceItem->getQty(), 0)] = round(
                            $tierPriceItem->getValue(),
                            0
                        );
                    }
                    $iterator++;
                }
            }

            if ($this->cpiHelper->getGeneralConfig('shippign_logic/enable')) {
                if ($product->getCustomAttribute(
                    $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_start')
                )) {
                    $config['deliveryDate'][$productId]['startdate'] = $product->getCustomAttribute(
                        $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_start')
                    )->getValue();
                }
                if ($product->getCustomAttribute(
                    $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_end')
                )) {
                    $config['deliveryDate'][$productId]['enddate'] = $product->getCustomAttribute(
                        $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_end')
                    )->getValue();
                }
                if ($product->getCustomAttribute(
                    $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_text')
                )) {
                    $text                                       = $product->getCustomAttribute(
                        $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_text')
                    )->getValue();
                    $text                                       = $this->parseAttributeText($text, $productId);
                    $config['deliveryDate'][$productId]['text'] = $text;
                }
                $config['deliveryDate']['block'] = $this->cpiHelper->getGeneralConfig(
                    'shippign_logic/custom_attr_block'
                );
            }

            if (isset($parentProduct)) {
                /**
                 * Render default product attributes.
                 */
                $config['customAttributes'][$productId] = $this->renderAttributes($product, $parentProduct);
            }

            /**
             * Render tier prices templates for each simple product.
             */
            if (isset($settings['change_tier_prices']) && $settings['change_tier_prices'] == 1 && !$config['hidePrice']) {
                $config['customAttributes'][$productId]['tier_prices_html'] = $this->renderTierPrice($product);
            }

            /**
             * Render attributes block.
             */
            if (isset($settings['change_attributes_block']) && $settings['change_attributes_block'] == 1) {
                $config['customAttributes'][$productId]['attributes_html'] = $this->renderAttributesBlock($product);
            }

            /**
             * Render simple product urls.
             */
            $config['urls'][$productId] = $this->prepareUrls($product);

            /**
             * Render custom product attributes.
             */
            $config['customAttributes'][$productId]['custom_1'] = $this->renderCustomBlock($product, 1);
            $config['customAttributes'][$productId]['custom_2'] = $this->renderCustomBlock($product, 2);
            $config['customAttributes'][$productId]['custom_3'] = $this->renderCustomBlock($product, 3);

            /**
             * Change breadcrumbs
             */
            if ($this->cpiHelper->getGeneralConfig('general/change_breadcrumbs')) {
                $config['customAttributes'][$productId]['.breadcrumbs .items .product'] = [
                    'value' => $product->getName(),
                    'class' => $this->cpiHelper->getGeneralConfig('general/breadcrumbs_id_class')
                ];
            }

            /**
             * Show left qty in stock
             */
            if ($this->cpiHelper->getGeneralConfig('general/left_in_stock')) {
                $config['customAttributes'][$productId]['left_in_stock'] = [
                    'value' => $stockQty,
                    'class' => '.stock.available'
                ];
            }
        }

        if (!isset($config['deliveryDate']['parent'])) {
            if (isset($parentProduct) && $parentProduct) {
                if ($parentProduct->getCustomAttribute(
                    $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_start')
                )) {
                    $config['deliveryDate']['parent']['startdate'] = $parentProduct->getCustomAttribute(
                        $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_start')
                    )->getValue();
                }
                if ($parentProduct->getCustomAttribute(
                    $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_end')
                )) {
                    $config['deliveryDate']['parent']['enddate'] = $parentProduct->getCustomAttribute(
                        $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_end')
                    )->getValue();
                }
                if ($parentProduct->getCustomAttribute(
                    $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_text')
                )) {
                    $text = $parentProduct->getCustomAttribute(
                        $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_text')
                    )->getValue();
                    $text = $this->parseAttributeText($text, $parentProduct->getId());
                    $config['deliveryDate']['parent']['text'] = $text;
                }
            }
        }
        return $config;
    }

    /**
     * Parse attributes in text field attribute
     *
     * @param $attributeText
     * @param $productId
     *
     * @return mixed
     */
    private function parseAttributeText($attributeText, $productId)
    {
        $countAttributes = substr_count($attributeText, '[');
        if ($countAttributes == 0) {
            return $attributeText;
        }
        $attributesArray = [];
        $tempText        = $attributeText;
        for ($i = 0; $i < $countAttributes; $i++) {
            $positionAttributeStart = strpos($tempText, '[');
            $positionAttributeEnd   = strpos($tempText, ']');
            $attributesArray[]      = $attr = substr(
                $tempText,
                $positionAttributeStart,
                $positionAttributeEnd - $positionAttributeStart + 1
            );
            $tempText               = substr($tempText, $positionAttributeEnd + 1);
        }
        foreach ($attributesArray as $attribute) {
            $product         = $this->productRepository->getById($productId);
            $attributeString = str_replace(['[', ']'], '', $attribute);
            if ($product->getCustomAttribute(str_replace(['[', ']'], '', $attribute))) {
                $attributeText = str_replace(
                    $attribute,
                    explode(' ', $product->getCustomAttribute($attributeString)->getValue())[0],
                    $attributeText
                );
            }
        }

        return $attributeText;
    }

    /**
     * Render default product attributes.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param $parentProduct
     *
     * @return array
     */
    private function renderAttributes(\Magento\Catalog\Api\Data\ProductInterface $product, $parentProduct = null)
    {
        $attributesArray  = ['name', 'description', 'short_description', 'sku'];
        $settings         = $this->getSettings();
        $customAttributes = [];
        foreach ($attributesArray as $attributeCode) {
            if (isset($settings['change_' . $attributeCode]) && $settings['change_' . $attributeCode] == 1) {
                $value = $product->getData($attributeCode);
                if ($attributeCode == 'description' || $attributeCode == 'short_description') {
                    if ($value == '' || empty($value)
                        || !$this->cpiHelper->getGeneralConfig(
                            'general/change_description'
                        )) {
                        if (!$parentProduct) {
                            $parent = $this->configurableProductObject->getParentIdsByChild($product->getId());
                            $parentProduct = $this->productRepository->getById($parent[0]);
                        }
                        $value = $parentProduct->getData($attributeCode);
                    }
                }
                if ($value) {
                    $value = $this->cpiHelper->getAttrContent($value);
                }
                $customAttributes[$attributeCode] = [
                    'value' => $value,
                    'class' => $settings[$attributeCode . '_id_class']
                ];
            } else {
                if ($attributeCode == 'description' || $attributeCode == 'short_description') {
                    $value = $product->getData($attributeCode);
                    if ($value == '' || empty($value)
                        || !$this->cpiHelper->getGeneralConfig(
                            'general/change_description'
                        )) {
                        if (!$parentProduct) {
                            $parent = $this->configurableProductObject->getParentIdsByChild($product->getId());
                            $parentProduct = $this->productRepository->getById($parent[0]);
                        }
                        $value = $parentProduct->getData($attributeCode);
                    }
                    if ($value) {
                        $value = $this->cpiHelper->getAttrContent($value);
                    }
                    $customAttributes[$attributeCode] = [
                        'value' => $value,
                        'class' => $settings[$attributeCode . '_id_class']
                    ];
                }
            }
        }
        return $customAttributes;
    }

    private function renderAttributesParent(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $attributesArray  = ['name', 'description', 'short_description', 'sku'];
        $settings         = $this->getSettings();
        $customAttributes = [];

        foreach ($attributesArray as $attributeCode) {
            if (isset($settings['change_' . $attributeCode]) && $settings['change_' . $attributeCode] == 1) {
                $value                            = $product->getData($attributeCode);
                if ($value) {
                    $value = $this->cpiHelper->getAttrContent($value);
                }
                $customAttributes[$attributeCode] = [
                    'value' => $value,
                    'class' => $settings[$attributeCode . '_id_class']
                ];
            }
        }
        /**
         * Render price on category page
         */
        if ($this->request->getFullActionName() == 'catalog_category_view') {
            $priceRenderBlock = $this->layout->getBlock('product.price.render.default');
            if ($priceRenderBlock) {
                $priceHtml = $priceRenderBlock->render(
                    'final_price',
                    $product,
                    array()
                );
                $customAttributes['.price-final_price'] = [
                    'value' => $priceHtml,
                    'class' => '.price-final_price'
                ];
            }
        }
        return $customAttributes;
    }

    /**
     * Render tier prices templates for each simple product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return array
     */
    private function renderTierPrice(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $settings    = $this->getSettings();
        $priceRender = $this->layout->getBlock('product.price.render.default');
        $priceHtml   = '';
        if (!$priceRender) {
            $priceRender = $this->layout->createBlock(
                'Magento\Framework\Pricing\Render',
                'product.price.render.default',
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }
        if ($priceRender) {
            $priceHtml = $priceRender->render(
                \Magento\Catalog\Pricing\Price\TierPrice::PRICE_CODE,
                $product,
                ['zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST]
            );
        }
        //solve conflict with FireGento MageSetup
        if ($this->moduleManager->isEnabled('FireGento_MageSetup')) {
            preg_match('/<div class=\"price\-details\">(.*?)<\/div>/s', $priceHtml, $match);
            if (count($match)) {
                $priceDetailsBlock = $match[0];
                $priceHtml = str_replace($priceDetailsBlock, '', $priceHtml);
            }
        }
        $html = [
            'value'     => $priceHtml,
            'class'     => $settings['tier_prices_id_class'],
            'replace'   => true,
            'container' => '.prices-tier-container'
        ];

        return $html;
    }

    /**
     * Render attributes block.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return array
     */
    private function renderAttributesBlock(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $settings        = $this->getSettings();
        $attributesBlock = $this->layout
            ->getBlock('firebear.product.attributes');

        if (!$attributesBlock) {
            if ($this->layout->getBlock('product.attributes')) {
                $attributesTemplate = $this->layout->getBlock('product.attributes')->getTemplate();
            } else {
                $attributesTemplate = 'product/view/attributes.phtml';
            }
            if (strpos($attributesTemplate, '::') === false) {
                $attributesTemplate = 'Magento_Catalog::' . $attributesTemplate;
            }
            $attributesBlock = $this->layout
                ->createBlock(
                    '\Firebear\ConfigurableProducts\Block\Product\View\Attributes',
                    'firebear.product.attributes'
                )
                ->setTemplate($attributesTemplate);
        }

        $attributesBlock->setProduct($product);

        $html = $attributesBlock->toHtml();
        if ($html) {
            $attributesHtml = [
                'value'   => $html,
                'class'   => $settings['attributes_block_class'],
                'replace' => true,
            ];
        } else {
            $attributesBlock->setProduct($this->subject->getProduct());
            $html = $attributesBlock->toHtml();

            $attributesHtml = [
                'value'   => $html,
                'class'   => $settings['attributes_block_class'],
                'replace' => true,
            ];
        }

        return $attributesHtml;
    }

    /**
     * Render custom block.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return array
     */
    private function renderCustomBlock(\Magento\Catalog\Api\Data\ProductInterface $product, $number)
    {
        $settings = $this->getSettings();

        if (isset($settings['custom_block_' . $number]) && $settings['custom_block_' . $number] == 1
            && !empty($settings['custom_block_' . $number . '_data'])
            && !empty($settings['custom_block_' . $number . '_id_class'])
        ) {
            $attr = $settings['custom_block_' . $number . '_data'];
            $attr = str_replace(['{', '}'], '', $attr);
            if ($attr == 'price') {
                $value = number_format(
                    $product->getResource()->getAttribute($attr)->getFrontend()->getValue($product),
                    2,
                    '.',
                    ''
                );
            } else {
                $value = $product->getResource()->getAttribute($attr)->getFrontend()->getValue($product);
            }
            $replaceData = $this->cpiHelper->getGeneralConfig('general/replace_custom_block_' . $number);

            return [
                'value' => $value,
                'class' => $settings['custom_block_' . $number . '_id_class'],
                'replace' => $replaceData,
                'container' => $settings['custom_block_' . $number . '_id_class']
            ];
        }

        return [];
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     *
     * @return string
     */
    private function prepareUrls(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $settings = $this->getSettings();
        $url      = '';

        if (isset($settings['change_url'])
            && $settings['change_url'] == 1
        ) {
            $url = $product->getProductUrl();
        }

        return $url;
    }
}
