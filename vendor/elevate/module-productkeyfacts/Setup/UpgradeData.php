<?php

namespace Elevate\ProductKeyFacts\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Elevate\ProductKeyFacts\Setup
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
    if (version_compare($context->getVersion(), "1.0.2", "<")) {
      $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_1_type',
        [
        'type'                    => 'varchar',
        'group'                   => 'Attributes',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Key Facts 1 Type',
        'input'                   => 'select',
        'class'                   => '',
        'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
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
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_2_type',
        [
          'type'                    => 'varchar',
          'group'                   => 'Attributes',
          'backend'                 => '',
          'frontend'                => '',
          'label'                   => 'Key Facts 2 Type',
          'input'                   => 'select',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
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
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_3_type',
        [
          'type'                    => 'varchar',
          'group'                   => 'Attributes',
          'backend'                 => '',
          'frontend'                => '',
          'label'                   => 'Key Facts 3 Type',
          'input'                   => 'select',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
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
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_4_type',
        [
          'type'                    => 'varchar',
          'group'                   => 'Attributes',
          'backend'                 => '',
          'frontend'                => '',
          'label'                   => 'Key Facts 4 Type',
          'input'                   => 'select',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
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
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_5_type',
        [
          'type'                    => 'varchar',
          'group'                   => 'Attributes',
          'backend'                 => '',
          'frontend'                => '',
          'label'                   => 'Key Facts 5 Type',
          'input'                   => 'select',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
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
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_6_type',
        [
          'type'                    => 'varchar',
          'group'                   => 'Attributes',
          'backend'                 => '',
          'frontend'                => '',
          'label'                   => 'Key Facts 6 Type',
          'input'                   => 'select',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
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

      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_show_message',
        [
          'type'                    => 'int',
          'group'                   => 'Attributes',
          'backend'                 => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
          'frontend'                => '',
          'label'                   => 'Show Keyfacts Sub Message',
          'input'                   => 'select',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\YesNo',
          'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
          'visible'                 => true,
          'required'                => false,
          'user_defined'            => false,
          'default'                 => '0',
          'searchable'              => false,
          'filterable'              => false,
          'comparable'              => false,
          'visible_on_front'        => false,
          'used_in_product_listing' => true,
          'unique'                  => false,
          'apply_to'                => ''
        ]);
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'ev_keyfacts_show_message_1',
        [
          'type'                    => 'text',
          'group'                   => 'Attributes',
          'backend'                 => '',
          'frontend'                => '',
          'label'                   => 'KeyFacts Sub Message',
          'input'                   => 'text',
          'class'                   => '',
          'source'                  => 'Elevate\ProductKeyFacts\Model\Config\Source\KeyFactsOptions',
          'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
          'visible'                 => true,
          'required'                => false,
          'user_defined'            => false,
          'default'                 => '(Per Serving)',
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