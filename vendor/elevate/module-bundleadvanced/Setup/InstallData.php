<?php


namespace Elevate\BundleAdvanced\Setup;

use Elevate\BundleAdvanced\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Bundle\Model\Product\Type as BundleProduct;

/**
 * Class InstallData
 * @package Elevate\BundleAdvanced\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this
            ->createBundleAttributes($setup);
    }

    /**
     * Create attributes
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    private function createBundleAttributes(ModuleDataSetupInterface $setup)
    {
        /** @var CategorySetup $installer */
        $installer = $this->categorySetupFactory->create(['resourceName' => 'catalog_setup', 'setup' => $setup]);
        $installer->addAttribute(
            Product::ENTITY,
            ProductAttributeInterface::CODE_ELEVATE_BUNDLEADVANCED_BUNDLE_PRODUCT_TYPE,
            [
                'backend' => '',
                'frontend' => '',
                'type' => 'int',
                'label' => 'Switch To Simple Bundle',
                'input' => 'boolean',
                'required' => true,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => false,
                'visible_in_advanced_search' => false,
                'used_in_product_listing' => false,
                'used_for_sort_by' => false,
                'apply_to' => BundleProduct::TYPE_CODE,
                'sort_order' => 10
            ]
        );

        return $this;
    }
}
