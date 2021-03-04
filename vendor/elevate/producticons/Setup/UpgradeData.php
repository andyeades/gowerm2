<?php

namespace Elevate\Producticons\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Elevate\ProductIcons\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
  private $eavSetupFactory;

  /**
   * UpgradeData constructor.
   * @param EavSetupFactory $eavSetupFactory
   */
  public function __construct(
    EavSetupFactory $eavSetupFactory
  )
  {
    $this->eavSetupFactory = $eavSetupFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function upgrade(
    ModuleDataSetupInterface $setup,
    ModuleContextInterface $context
  )
  {
    if (version_compare($context->getVersion(), "0.0.3", "<")) {
      $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
      $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'elevate_producticons', [
        'type'                    => 'text',
        'group'                   => 'Elevate Product Icons',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Product Icons to Show',
        'input'                   => 'text',
        'class'                   => '',
        'source'                  => '',
        'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
        'visible'                 => true,
        'required'                => false,
        'user_defined'            => false,
        'default'                 => '',
        'searchable'              => false,
        'filterable'              => false,
        'comparable'              => false,
        'visible_on_front'        => false,
        'used_in_product_listing' => true,
        'unique'                  => false,
        'apply_to'                => ''
      ]);
    }

  }
}