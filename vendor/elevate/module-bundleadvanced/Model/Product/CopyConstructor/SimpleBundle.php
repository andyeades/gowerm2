<?php

namespace Elevate\BundleAdvanced\Model\Product\CopyConstructor;

use Elevate\BundleAdvanced\Model\Config;
use Magento\Bundle\Api\Data\OptionInterface;
use Elevate\BundleAdvanced\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\CopyConstructorInterface;
use Magento\Bundle\Api\Data\OptionInterfaceFactory;

/**
 * Class SimpleBundle
 * @package Elevate\BundleAdvanced\Model\Product\CopyConstructor
 */
class SimpleBundle implements CopyConstructorInterface
{
    /**
     * @var OptionInterfaceFactory
     */
    private $optionFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param OptionInterfaceFactory $optionFactory
     * @param Config $config
     */
    public function __construct(OptionInterfaceFactory $optionFactory, Config $config)
    {
        $this->optionFactory = $optionFactory;
        $this->config = $config;
    }

    /**
     * Duplicating bundle options and selections
     *
     * @param Product $product
     * @param Product $duplicate
     * @return void
     */
    public function build(Product $product, Product $duplicate)
    {
        $duplicatedBundleOptions = $duplicate->getExtensionAttributes()->getBundleProductOptions() ?: [];
        if (!$product->getAwSbpDuplicateToSimpleProduct() || empty($duplicatedBundleOptions)) {
            return;
        }

        $productLinks = [];
        /** @var OptionInterface $bundleOption */
        foreach ($duplicatedBundleOptions as $bundleOption) {
            foreach ($bundleOption->getProductLinks() as $productLink) {
                $productLink->setIsDefault(false);
                $productLinks[] = $productLink;
            }
        }
        $option = $this->optionFactory->create();
        $option
            ->setTitle($this->config->getDefaultTitleForListOfBundleProducts())
            ->setPosition(1)
            ->setProductLinks($productLinks)
            ->setRequired(false)
            ->setType('checkbox');

        $duplicate->getExtensionAttributes()->setBundleProductOptions([$option]);
        $duplicate->setData(ProductAttributeInterface::CODE_AW_SBP_BUNDLE_PRODUCT_TYPE, 1);
    }
}
