<?php
declare(strict_types=1);
/**
 * Copyright © 2017 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Block\ConfigurableProduct\Product\View\Type;

use Exception;
use Firebear\ConfigurableProducts\Framework\Serializer\Json;
use Firebear\ConfigurableProducts\Helper\Data as ICPHelper;
use Firebear\ConfigurableProducts\Logger\Logger;
use Firebear\ConfigurableProducts\Model\Inventory\ProductStockQty;
use Firebear\ConfigurableProducts\Model\Product\Defaults;
use Firebear\ConfigurableProducts\Model\ProductOptionsRepository;
use Firebear\ConfigurableProducts\Service\ProductProvider;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\TierPrice;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as ConfigurableBlock;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as TypeConfigurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutInterface;
use Magento\Swatches\Helper\Data as SwatchesHelper;

class Configurable
{
    const ALL_CUSTOMER_GROUPS = 32000;
    const CATALOG_CATEGORY_VIEW_LAYOUT = 'catalog_category_view';
    const CMS_INDEX_INDEX_LAYOUT = 'cms_index_index';
    const CUSTOM_ATTRIBUTES = 'customAttributes';

    /**
     * @var string[]
     */
    protected $_configProductAttributes = [
        'name',
        'description',
        'short_description',
        'sku',
        'breadcrumbs'
    ];

    /**
     * @var string[]
     */
    protected $_defaultParentAttr = [
        'description',
        'short_description'
    ];

    /**
     * @var ConfigurableBlock
     */
    protected $configurableBlock;

    /**
     * @var ICPHelper
     */
    protected $cpiHelper;

    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var ProductProvider
     */
    protected $productProvider;

    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @var SwatchesHelper
     */
    protected $swatchesHelper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var Defaults
     */
    protected $productDefaults;

    /**
     * @var ProductOptionsRepository
     */
    protected $productOptionsRepository;

    /**
     * @var StockConfigurationInterface
     */
    protected $stockConfigurationInterface;

    /**
     * @var ProductStockQty
     */
    protected $productStockQty;

    /**
     * @var ProductInterface|mixed|null
     */
    protected $currentParentProduct;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ProductMetadata
     */
    protected $productMetaData;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Configurable constructor.
     * @param ICPHelper $icpHelper
     * @param Registry $coreRegistry
     * @param Json $jsonSerializer
     * @param ProductProvider $productProvider
     * @param SwatchesHelper $swatchesHelper
     * @param Defaults $productDefaults
     * @param ProductOptionsRepository $productOptionsRepository
     * @param StockConfigurationInterface $stockConfigurationInterface
     * @param ProductStockQty $productStockQty
     * @param CustomerSession $customerSession
     * @param Logger $logger
     * @param ProductMetadataInterface $productMetaData
     */
    public function __construct(
        ICPHelper $icpHelper,
        Registry $coreRegistry,
        Json $jsonSerializer,
        ProductProvider $productProvider,
        SwatchesHelper $swatchesHelper,
        Defaults $productDefaults,
        ProductOptionsRepository $productOptionsRepository,
        StockConfigurationInterface $stockConfigurationInterface,
        ProductStockQty $productStockQty,
        CustomerSession $customerSession,
        Logger $logger,
        ProductMetadataInterface $productMetaData
    ) {
        $this->cpiHelper = $icpHelper;
        $this->coreRegistry = $coreRegistry;
        $this->productProvider = $productProvider;
        $this->jsonSerializer = $jsonSerializer;
        $this->swatchesHelper = $swatchesHelper;
        $this->productDefaults = $productDefaults;
        $this->productOptionsRepository = $productOptionsRepository;
        $this->stockConfigurationInterface = $stockConfigurationInterface;
        $this->productStockQty = $productStockQty;
        $this->customerSession = $customerSession;
        $this->productMetaData = $productMetaData;
        $this->logger = $logger;
    }

    /**
     * @param ConfigurableBlock $configurableBlock
     * @param $result
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGetJsonConfig(
        ConfigurableBlock $configurableBlock,
        $result
    ) {
        $this->configurableBlock = $configurableBlock;
        $this->layout = $configurableBlock->getLayout();
        $this->request = $configurableBlock->getRequest();
        $config = [];
        try {
            $fullActionName = $this->request->getFullActionName();
            if ($fullActionName == self::CATALOG_CATEGORY_VIEW_LAYOUT ||
                $fullActionName == self::CMS_INDEX_INDEX_LAYOUT) {
                if ($this->cpiHelper->getGeneralConfig('general/disable_swatches_functionallity_in_listing')) {
                    return $result;
                }
            }
            $data = $this->coreRegistry->registry('firebear_configurableproducts');
            if (isset($data['child_id'])) {
                $productId = $data['child_id'];
            } else {
                $productId = $configurableBlock->getProduct()->getId();
            }
            $config = $this->jsonSerializer->jsonDecode($result);
            $enablePreselect = $this->cpiHelper->getGeneralConfig('general/enable_preselect');
            if ($enablePreselect) {
                /**
                 * Prepare default values for configurable product
                 */
                $isProductHasSwatch = $this->swatchesHelper
                    ->isProductHasSwatch($this->configurableBlock->getProduct());
                $defaultValues = $this->prepareDefaultValues($config, $productId, $isProductHasSwatch);
                $usedProductId = $this->productDefaults->getDefaultProductId($configurableBlock->getProduct());
                if ((empty($defaultValues) || count($config['attributes']) != count($defaultValues))
                    && $usedProductId
                ) {
                    $defaultValues = $this->prepareDefaultValues($config, $usedProductId, $isProductHasSwatch);
                }
                $config['defaultValues'] = $defaultValues;
            }

            /**
             * Do not replace page content on category view page.
             */
            if ($this->request->getFullActionName() === self::CATALOG_CATEGORY_VIEW_LAYOUT) {
                $config['doNotReplaceData'] = true;
            }
            if ($this->request->getFullActionName() !== self::CATALOG_CATEGORY_VIEW_LAYOUT) {
                $config['bundle_id'] = isset($this->request->getParams()['id']) ?
                    $this->request->getParams()['id'] : null;
            } else {
                $config['bundle_id'] = null;
            }
            $this->prepareGeneralConfig($config);
            $this->prepareParentProductOptions($config);
            $this->prepareSimpleProductOptions($config);
        } catch (Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return $this->jsonSerializer->jsonEncode($config);
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
     * @param array $config
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function prepareGeneralConfig(array &$config)
    {
        $config['currencySymbol'] = $this->configurableBlock->getCurrentStore()
            ->getCurrentCurrency()
            ->getCurrencySymbol();
        $config['considerTierPricesInFromToPrice'] = $this->cpiHelper
            ->getGeneralConfig('general/price_range_compatible_with_tier_price');
        $config['hidePrice'] = $this->cpiHelper->hidePrice();
        $config['priceText'] = $this->cpiHelper->getGeneralConfig('general/price_text');
        $config['setOpenGraphUrl'] = $this->configurableBlock->getUrl('cpi/product/UpdateOpenGraph');
        $versionNumber = $this->productMetaData->getVersion();
        if ($versionNumber >= '2.4') {
            $config['attribute_prefix'] = 'data-';
        } else {
            $config['attribute_prefix'] = '';
        }
        if ($this->cpiHelper->getGeneralConfig('general/price_range_category_from_to_option')) {
            $config['disaplyingFromToPrice'] = true;
        }
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
        $config['loadOptionsUrl'] = $this->configurableBlock->getUrl('cpi/product/LoadOptions');
        if ($this->stockConfigurationInterface->getManageStock() == 0) {
            $config['configManageStock'] = false;
        } else {
            $config['configManageStock'] = true;
        }
    }

    /**
     * @param array $config
     * @throws NoSuchEntityException
     */
    protected function prepareParentProductOptions(array &$config)
    {
        $parentProductId = $config['productId'];
        if ($parentProductId) {
            $parentProduct = $this->productProvider->getProductById($parentProductId);
            $productOptions = $this->productOptionsRepository->getByProductId($parentProductId);
            $productAttributes = $parentProduct->getAttributes();
            $config['parentProductName'] = $parentProduct->getName();
            if ($config['bundle_id']) {
                try {
                    $bundleProduct = $this->productProvider->getProductById($config['bundle_id']);
                } catch (NoSuchEntityException $e) {
                    $bundleProduct = false;
                }
                if ($bundleProduct && $bundleProduct->getTypeId() == 'bundle') {
                    $bundleProductOptions = $this->productOptionsRepository->getByProductId($config['bundle_id']);
                    if ($bundleProductOptions->getLinkedAttributes()) {
                        $config['linkedAttributes'] = explode(',', $bundleProductOptions->getLinkedAttributes());
                    }
                    $currentTime = strtotime('now');
                    $specialFromDate = !$bundleProduct->getSpecialFromDate() ?: strtotime($bundleProduct->getSpecialFromDate());
                    $specialToDatePrice = !$bundleProduct->getSpecialToDate() ?: strtotime($bundleProduct->getSpecialToDate());
                    $specialPrice = $bundleProduct->getSpecialPrice();
                    if (($specialFromDate <= $currentTime && $currentTime < $specialToDatePrice)
                        || !$specialToDatePrice
                    ) {
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
            $config[self::CUSTOM_ATTRIBUTES]['parent'] = $this->renderAttributesParent($parentProduct);
            $this->currentParentProduct = $parentProduct;
            $config['urls']['parent'] = $this->configurableBlock
                ->getProductUrl($parentProduct);
            if (!isset($config['deliveryDate']['parent'])) {
                if (isset($parentProduct) && $parentProduct) {
                    $config['deliveryDate']['parent'] = $this->renderDeliveryDateBlock($parentProduct);
                }
            }
        }
    }

    /**
     * @param ProductInterface $product
     * @return array
     * @throws NoSuchEntityException
     */
    protected function renderAttributesParent(ProductInterface $product)
    {
        $customAttributes = $this->renderAttributes($product);
        /**
         * Render price on category page
         */
        if ($this->request->getFullActionName() === self::CATALOG_CATEGORY_VIEW_LAYOUT) {
            $priceRenderBlock = $this->layout->getBlock('product.price.render.default');
            if ($priceRenderBlock) {
                $priceHtml = $priceRenderBlock->render(
                    'final_price',
                    $product,
                    []
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
     * @param ProductInterface $product
     * @param ProductInterface $parentProduct
     * @return array
     * @throws NoSuchEntityException
     */
    protected function renderAttributes(ProductInterface $product, $parentProduct = null)
    {
        $settings = $this->getSettings();
        $customAttributes = [];
        foreach ($this->_configProductAttributes as $attributeCode) {
            $value = null;
            if (isset($settings['change_' . $attributeCode]) && $settings['change_' . $attributeCode] == 1) {
                $value = $product->getData($attributeCode);
                if ($attributeCode === 'breadcrumbs') {
                    $value = $product->getName();
                }
                if ($parentProduct instanceof ProductInterface) {
                    if (in_array($attributeCode, $this->_defaultParentAttr)) {
                        if ((!$value || empty($value) || $value == '')
                            || !$this->cpiHelper->getGeneralConfig(
                                'general/change_' . $attributeCode
                            )
                        ) {
                            $value = $parentProduct->getData($attributeCode);
                        }
                    }
                }
                if ($value) {
                    $value = $this->cpiHelper->getAttrContent($value);
                }
                if ($value) {
                    $customAttributes[$attributeCode] = [
                        'value' => $value,
                        'class' => $settings[$attributeCode . '_id_class']
                    ];
                }
            } elseif ($product->getTypeId() === 'simple') {
                if (in_array($attributeCode, $this->_defaultParentAttr)
                    && $parentProduct instanceof ProductInterface
                ) {
                    $value = $product->getData($attributeCode);
                    if ((!$value || empty($value) || $value == '')
                        || !$this->cpiHelper->getGeneralConfig(
                            'general/change_' . $attributeCode
                        )
                    ) {
                        $value = $parentProduct->getData($attributeCode);
                    }
                    if ($value) {
                        $value = $this->cpiHelper->getAttrContent($value);
                    }
                    if ($value) {
                        $customAttributes[$attributeCode] = [
                            'value' => $value,
                            'class' => $settings[$attributeCode . '_id_class']
                        ];
                    }
                }
            }
        }
        return $customAttributes;
    }

    /**
     * Get extension settings.
     *
     * @return mixed
     */
    private function getSettings()
    {
        if (!$this->settings) {
            $this->settings = $this->cpiHelper->getGeneralConfig('general');
        }
        return $this->settings;
    }

    /**
     * @param ProductInterface $product
     * @return array
     * @throws NoSuchEntityException
     */
    protected function renderDeliveryDateBlock(ProductInterface $product)
    {
        $deliveryDate = [];
        if ($product->getCustomAttribute(
            $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_start')
        )) {
            $deliveryDate['startdate'] = $product->getCustomAttribute(
                $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_start')
            )->getValue();
        }
        if ($product->getCustomAttribute(
            $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_end')
        )) {
            $deliveryDate['enddate'] = $product->getCustomAttribute(
                $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_end')
            )->getValue();
        }
        if ($product->getCustomAttribute(
            $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_text')
        )) {
            $text = $product->getCustomAttribute(
                $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_code_text')
            )->getValue();
            $text = $this->parseAttributeText($text, $product->getId());
            $deliveryDate['text'] = $text;
        }
        return $deliveryDate;
    }

    /**
     * @param $attributeText
     * @param $productId
     * @return string|string[]
     * @throws NoSuchEntityException
     */
    private function parseAttributeText($attributeText, $productId)
    {
        $countAttributes = substr_count($attributeText, '[');
        if ($countAttributes == 0) {
            return $attributeText;
        }
        $attributesArray = [];
        $tempText = $attributeText;
        for ($i = 0; $i < $countAttributes; $i++) {
            $positionAttributeStart = strpos($tempText, '[');
            $positionAttributeEnd = strpos($tempText, ']');
            $attributesArray[] = $attr = substr(
                $tempText,
                $positionAttributeStart,
                $positionAttributeEnd - $positionAttributeStart + 1
            );
            $tempText = substr($tempText, $positionAttributeEnd + 1);
        }
        foreach ($attributesArray as $attribute) {
            $product = $this->productProvider->getProductById($productId);
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
     * Prepare simple product attributes, such as name, sku, description
     * @param array $config
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function prepareSimpleProductOptions(array &$config)
    {
        $settings = $this->getSettings();
        $customerGroupId = $this->customerSession->getCustomerGroupId();
        $allowedProducts = $this->configurableBlock->getAllowProducts();
        $config['deliveryDate']['block'] = $this->cpiHelper->getGeneralConfig('shippign_logic/custom_attr_block');
        /** @var Product $product */
        foreach ($allowedProducts as $product) {
            $productId = intval($product->getId());
            $product = $this->productProvider->getProductById($productId);
            $websiteId = intval($product->getStore()->getWebsiteId());
            $stockQty = $this->productStockQty->getProductQty($productId, $websiteId);
            $config['stockQty'][$productId] = $stockQty;

            if (isset($this->currentParentProduct)) {
                /**
                 * Render Simple Product Data
                 */
                $config[self::CUSTOM_ATTRIBUTES][$productId] = $this->renderAttributes(
                    $product,
                    $this->currentParentProduct
                );
            }
            /**
             * Render tier prices templates for each simple product.
             */
            if (isset($settings['change_tier_prices']) && $settings['change_tier_prices'] == 1 && !$config['hidePrice']) {
                $config[self::CUSTOM_ATTRIBUTES][$productId]['tier_prices_html'] = $this->renderTierPrice($product);
            }

            /**
             * Render attributes block.
             */
            if (isset($settings['change_attributes_block']) && $settings['change_attributes_block'] == 1) {
                $config[self::CUSTOM_ATTRIBUTES][$productId]['attributes_html'] = $this
                    ->renderAttributesBlock($product);
            }

            /**
             * Render simple product urls.
             */
            $config['urls'][$productId] = $this->prepareUrls($product);

            /**
             * Render custom product attributes.
             */
            $config[self::CUSTOM_ATTRIBUTES][$productId]['custom_1'] = $this->renderCustomBlock($product, 1);
            $config[self::CUSTOM_ATTRIBUTES][$productId]['custom_2'] = $this->renderCustomBlock($product, 2);
            $config[self::CUSTOM_ATTRIBUTES][$productId]['custom_3'] = $this->renderCustomBlock($product, 3);

            /**
             * Show left qty in stock
             */
            if ($this->cpiHelper->getGeneralConfig('general/left_in_stock')) {
                $config[self::CUSTOM_ATTRIBUTES][$productId]['left_in_stock'] = [
                    'value' => $stockQty,
                    'class' => '.stock.available'
                ];
            }
            if ($this->cpiHelper->getGeneralConfig('shippign_logic/enable')) {
                $config['deliveryDate'][$productId] = $this->renderDeliveryDateBlock($product);
            }

            if ($this->cpiHelper->getGeneralConfig('matrix/tier_price')) {
                $productTierPrices = $product->getTierPrices();
                $iterator = 0;
                foreach ($productTierPrices as $tierPriceItem) {
                    $tierPriceCustomerGroupId = $tierPriceItem->getCustomerGroupId();
                    if ($tierPriceCustomerGroupId == $customerGroupId ||
                        $tierPriceCustomerGroupId == self::ALL_CUSTOMER_GROUPS
                    ) {
                        $config['tierPrice'][$productId]['qty'][$iterator] = $tierPriceItem->getQty();
                        $config['tierPrice'][$productId]['price'][$iterator] = $tierPriceItem->getValue();
                        $config['tierPrice2'][$productId]['price'][round($tierPriceItem->getQty(), 0)] = $tierPriceItem->getValue();
                    }
                    $iterator++;
                }
            }
        }
    }

    /**
     * Render tier prices templates for each simple product.
     *
     * @param ProductInterface $product
     *
     * @return array
     */
    protected function renderTierPrice(ProductInterface $product)
    {
        $settings = $this->getSettings();
        $priceRender = $this->layout->getBlock('product.price.render.default');
        $priceHtml = '';
        if (!$priceRender) {
            $priceRender = $this->layout->createBlock(
                'Magento\Framework\Pricing\Render',
                'product.price.render.default',
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }
        if ($priceRender) {
            $priceHtml = $priceRender->render(
                TierPrice::PRICE_CODE,
                $product,
                ['zone' => Render::ZONE_ITEM_LIST]
            );
        }
        //solve conflict with FireGento MageSetup
        if ($this->cpiHelper->isModuleEnabled('FireGento_MageSetup')) {
            preg_match('/<div class=\"price\-details\">(.*?)<\/div>/s', $priceHtml, $match);
            if (count($match)) {
                $priceDetailsBlock = $match[0];
                $priceHtml = str_replace($priceDetailsBlock, '', $priceHtml);
            }
        }
        return [
            'value' => $priceHtml,
            'class' => $settings['tier_prices_id_class'],
            'replace' => true,
            'container' => '.prices-tier-container'
        ];
    }

    /**
     * Render attributes block.
     *
     * @param ProductInterface $product
     *
     * @return array
     */
    protected function renderAttributesBlock(ProductInterface $product)
    {
        $settings = $this->getSettings();
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
                'value' => $html,
                'class' => $settings['attributes_block_class'],
                'replace' => true,
            ];
        } else {
            $attributesBlock->setProduct($this->configurableBlock->getProduct());
            $html = $attributesBlock->toHtml();

            $attributesHtml = [
                'value' => $html,
                'class' => $settings['attributes_block_class'],
                'replace' => true,
            ];
        }

        return $attributesHtml;
    }

    /**
     * @param ProductInterface $product
     *
     * @return string
     */
    protected function prepareUrls(ProductInterface $product)
    {
        $settings = $this->getSettings();
        $url = '';

        if (isset($settings['change_url'])
            && $settings['change_url'] == 1
        ) {
            $url = $product->getProductUrl();
        }

        return $url;
    }

    /**
     * Render custom block.
     *
     * @param ProductInterface $product
     *
     * @param $number
     * @return array
     */
    private function renderCustomBlock(ProductInterface $product, $number)
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
     * @param ConfigurableBlock $subject
     * @param $result
     * @return mixed
     */
    public function afterGetCacheKeyInfo(
        ConfigurableBlock $subject,
        $result
    ) {
        /**
         * Prevent save same cache on category view page and product view page with same default values.
         */
        $fullActionName = $subject->getRequest()->getFullActionName();
        if ($fullActionName == self::CATALOG_CATEGORY_VIEW_LAYOUT || $fullActionName == self::CMS_INDEX_INDEX_LAYOUT) {
            $result[] = 'doNotReplaceData';
            return $result;
        }
        if ($subject->getProduct()->getTypeId() == TypeConfigurable::TYPE_CODE) {
            $jsonConfig = $subject->getJsonConfig();
            $config = $this->jsonSerializer->jsonDecode($jsonConfig);
            /**
             * Different cache for different simple products.
             */
            if (isset($config['defaultValues']) && !empty($config['defaultValues'])) {
                $result[] = http_build_query($config['defaultValues']);
            }
        }
        return $result;
    }
}
