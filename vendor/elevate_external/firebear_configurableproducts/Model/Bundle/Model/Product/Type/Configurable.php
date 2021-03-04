<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Firebear\ConfigurableProducts\Model\Bundle\Model\Product\Type;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Model\Product\Gallery\ReadHandler as GalleryReadHandler;
use Magento\ConfigurableProduct\Model\Product\Type\Collection\SalableProcessor;
use Magento\Framework\App\ObjectManager;

class Configurable extends \Magento\ConfigurableProduct\Model\Product\Type\Configurable
{
    protected $typeFile;
    protected $store;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;
    /**
     * Url
     *
     * @var \Magento\Catalog\Model\Product\Option\UrlBuilder
     */
    protected $_urlBuilder;

    public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger, ProductRepositoryInterface $productRepository,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\ConfigurableFactory $typeConfigurableFactory,
        \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory $eavAttributeFactory,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable\AttributeFactory $configurableAttributeFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Product\CollectionFactory $productCollectionFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Framework\Cache\FrontendInterface $cache = null,
        \Magento\Customer\Model\Session $customerSession = null,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        ProductInterfaceFactory $productFactory = null,
        SalableProcessor $salableProcessor = null,
        \Magento\Catalog\Model\Product\Option\Type\File $typeFile,
        \Magento\Store\Model\StoreManagerInterface $store,
        \Magento\Catalog\Model\Product\Option\UrlBuilder $urlBuilder,
        \Magento\Framework\Escaper $escaper

    )
    {
        $this->typeFile = $typeFile;
        $this->store = $store;
        $this->_urlBuilder = $urlBuilder;
        $this->_escaper = $escaper;

        parent::__construct(
            $catalogProductOption,
            $eavConfig,
            $catalogProductType,
            $eventManager,
            $fileStorageDb,
            $filesystem,
            $coreRegistry,
            $logger,
            $productRepository,
            $typeConfigurableFactory,
            $eavAttributeFactory,
            $configurableAttributeFactory,
            $productCollectionFactory,
            $attributeCollectionFactory,
            $catalogProductTypeConfigurable,
            $scopeConfig,
            $extensionAttributesJoinProcessor,
            $cache,
            $customerSession,
            $serializer,
            $productFactory,
            $salableProcessor
        );
    }


    /**
     * Prepare product and its configuration to be added to some products list.
     * Perform standard preparation process and then add Configurable specific options.
     *
     * @param \Magento\Framework\DataObject  $buyRequest
     * @param \Magento\Catalog\Model\Product $product
     * @param string                         $processMode
     *
     * @return \Magento\Framework\Phrase|array|string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _prepareProduct(\Magento\Framework\DataObject $buyRequest, $product, $processMode)
    {
        $attributes = $buyRequest->getSuperAttribute();
        if (!isset($buyRequest['only_for_checkbox_bundle'])) {
            $subProduct = $this->getProductByAttributes($attributes, $product);
            $prepareProductOptions = parent::_prepareProduct($buyRequest, $product, $processMode);
            if  ($subProduct) {
                $this->addCustomFileOption($buyRequest, $subProduct, $processMode, $product);
            }
            return $prepareProductOptions;
        }
        if ($attributes
            || !$this->_isStrictProcessMode($processMode)
            && !isset($buyRequest['only_for_checkbox_bundle'])) {
            if (!$this->_isStrictProcessMode($processMode)) {
                if (is_array($attributes)) {
                    foreach ($attributes as $key => $attribute) {
                        foreach ($attribute as $val) {
                            if (empty($val)) {
                                unset($attributes[$key][$product->getId()]);
                            }
                        }
                    }
                } else {
                    $attributes = [];
                }
            }

            //TODO: MAGETWO-23739 get id from _POST and retrieve product from repository immediately.

            /**
             * $attributes = array($attributeId=>$attributeValue)
             */
            $subProduct = true;
            if (!$this->_isStrictProcessMode($processMode) && !isset($buyRequest['only_for_checkbox_bundle'][$product->getId()])) {
                foreach ($this->getConfigurableAttributes($product) as $attributeItem) {
                    /* @var $attributeItem \Magento\Framework\DataObject */
                    $attrId = $attributeItem->getData('attribute_id');
                    if (!isset($attributes[$attrId][$product->getId()])
                        || empty($attributes[$attrId][$product->getId()])) {
                        $subProduct = null;
                        break;
                    }
                }
            }
            if ($subProduct) {
                $subProduct = $this->getProductByAttributes($attributes, $product);
                $result     = $subProduct->getTypeInstance()->_prepareProduct($buyRequest, $product, $processMode);
            }
            if (!$result) {
                return $this->getSpecifyOptionMessage()->render();
            }
            if ($subProduct) {
                $subProductLinkFieldId = $subProduct->getId();
                $product->addCustomOption('attributes', $this->serializer->serialize($attributes));
                $product->addCustomOption('product_qty_' . $subProductLinkFieldId, 1, $subProduct);
                $product->addCustomOption('simple_product', $subProductLinkFieldId, $subProduct);

                $_result = $subProduct->getTypeInstance()->processConfiguration(
                    $buyRequest,
                    $subProduct,
                    $processMode
                );
                if (is_string($_result) && !is_array($_result)) {
                    return $_result;
                }

                if (!isset($_result[0])) {
                    return __('You can\'t add the item to shopping cart.')->render();
                }

                /**
                 * Adding parent product custom options to child product
                 * to be sure that it will be unique as its parent
                 */
                if ($optionIds = $product->getCustomOption('option_ids')) {
                    $optionIds = explode(',', $optionIds->getValue());
                    foreach ($optionIds as $optionId) {
                        if ($option = $product->getCustomOption('option_' . $optionId)) {
                            $_result[0]->addCustomOption('option_' . $optionId, $option->getValue());
                        }
                    }
                }

                $productLinkFieldId = $product->getId();
                $_result[0]->setParentProductId($productLinkFieldId)
                    ->addCustomOption('parent_product_id', $productLinkFieldId);
                if ($this->_isStrictProcessMode($processMode)) {
                    $_result[0]->setCartQty(1);
                }
                $result[] = $_result[0];

                return $result;
            } else {
                if (!$this->_isStrictProcessMode($processMode)) {
                    return $result;
                }
            }
        }

        return $result;
    }

    /**
     * Process product configuration
     *
     * @param \Magento\Framework\DataObject $buyRequest
     * @param \Magento\Catalog\Model\Product $product
     * @param string $processMode
     * @param $product
     * @return array|string
     */
    public function addCustomFileOption(
        \Magento\Framework\DataObject $buyRequest,
        $subProduct,
        $processMode = self::PROCESS_MODE_LITE,
        $product
    ) {

        $options = $this->_prepareOptions($buyRequest, $subProduct, $processMode);
        foreach ($options as $optionId => $option) {
            $productOption = $subProduct->getOptionById($optionId);
            $optionType = $productOption->getType();
            $optionTitle = $productOption->getTitle();

            if ($optionType == 'file') {
                $unserializeOptionData = $this->serializer->unserialize($option);
                $additionalOptions[] = [
                'label' => $optionTitle,
                'value' => $this->getDownloadUrl($option),
                'is_file' => true
                ];
            }
        }
        if (!empty($additionalOptions)) {
            $customOption = $product->getCustomOption('additional_options');
            if ($customOption) {
                $currentOptionValue = $this->serializer->unserialize($customOption->getValue());
                $additionalOptions = array_merge($additionalOptions, $currentOptionValue);
            }
            $product->addCustomOption('additional_options', $this->serializer->serialize($additionalOptions));
        }
    }
    
    public function getDownloadUrl($value) {
        $value = $this->serializer->unserialize($value);
        $urlRoute = 'cpi/download/downloadCustomOption';
        $this->_urlBuilder->getUrl($urlRoute, []);
        $sizes = $this->prepareSize($value);
        $title = !empty($value['title']) ? $value['title'] : '';

        return sprintf(
            '<a href="%s" target="_blank">%s</a> %s',
            $this->_urlBuilder->getUrl($urlRoute,
                [
                    'fileName' => $title,
                    'path' => str_replace('/', '-', $value['quote_path'])
                ]
            ),
            $this->_escaper->escapeHtml($title, ['div']),
            $sizes
        );
    }

    protected function prepareSize($value)
    {
        $sizes = '';
        if (!empty($value['width']) && !empty($value['height']) && $value['width'] > 0 && $value['height'] > 0) {
            $sizes = $value['width'] . ' x ' . $value['height'] . ' ' . __('px.');
        }
        return $sizes;
    }
}
