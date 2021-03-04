<?php
namespace Elevate\ProductKeyFacts\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
  private $eavSetupFactory;

  public function __construct(EavSetupFactory $eavSetupFactory) {
    $this->eavSetupFactory = $eavSetupFactory;
  }

  public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {

    $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_keyfacts_1', [
        'type'                    => 'text',
        'group'                   => 'attributes',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Key Facts 1',
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
    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_keyfacts_2', [
      'type'                    => 'text',
      'group'                   => 'attributes',
      'backend'                 => '',
      'frontend'                => '',
      'label'                   => 'Key Facts 2',
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
    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_keyfacts_3', [
      'type'                    => 'text',
      'group'                   => 'attributes',
      'backend'                 => '',
      'frontend'                => '',
      'label'                   => 'Key Facts 3',
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
    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_keyfacts_4', [
      'type'                    => 'text',
      'group'                   => 'attributes',
      'backend'                 => '',
      'frontend'                => '',
      'label'                   => 'Key Facts 4',
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
    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_keyfacts_5', [
      'type'                    => 'text',
      'group'                   => 'attributes',
      'backend'                 => '',
      'frontend'                => '',
      'label'                   => 'Key Facts 5',
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
    $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_keyfacts_6', [
      'type'                    => 'text',
      'group'                   => 'attributes',
      'backend'                 => '',
      'frontend'                => '',
      'label'                   => 'Key Facts 6',
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