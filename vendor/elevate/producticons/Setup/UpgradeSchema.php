<?php namespace Elevate\ProductIcons\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


class UpgradeSchema implements UpgradeSchemaInterface
{
  /**
   * Upgrades DB schema for a module
   *
   * @param SchemaSetupInterface $setup
   * @param ModuleContextInterface $context
   * @return void
   */
  public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
    $connection = $setup->getConnection();

    if (version_compare($context->getVersion(), '0.0.1', '===')) {
      // Action to do if module version is less than 0.0.1
    }

    if (version_compare($context->getVersion(), '0.0.2', '===')) {

      $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
      $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'elevate_producticons',
        [
          'type' => 'text',
          'group' => 'Elevate Product Icons',
          'backend' => '',
          'frontend' => '',
          'label' => 'Product Icons to Show',
          'input' => 'text',
          'class' => '',
          'source' => '',
          'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
          'visible' => true,
          'required' => false,
          'user_defined' => false,
          'default' => '',
          'searchable' => false,
          'filterable' => false,
          'comparable' => false,
          'visible_on_front' => false,
          'used_in_product_listing' => true,
          'unique' => false,
          'apply_to' => ''
        ]
      );

    }

    if (version_compare($context->getVersion(), '0.0.6', '<')) {


    }


    if (version_compare($context->getVersion(), '0.0.7', '<')) {


    }

    if (version_compare($context->getVersion(), '1.1.0.0') < 0) {
      // Action to do if module version is less than 1.1.0.0
    }

    if (version_compare($context->getVersion(), '1.1.0.1') < 0) {
      // Action to do if module version is less than 1.1.0.1
    }

    if (version_compare($context->getVersion(), '2.0.0.0') < 0) {
      // Action to do if module version is less than 2.0.0.0
    }

    $connection->endSetup();
  }
}