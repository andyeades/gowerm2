<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Aheadworks\Giftcard\Api\Data\ProductAttributeInterface;

/**
 * Class Uninstall
 *
 * @package Aheadworks\Giftcard\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $dataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @param ModuleDataSetupInterface $dataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param QuoteSetupFactory $setupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $dataSetup,
        EavSetupFactory $eavSetupFactory,
        QuoteSetupFactory $setupFactory
    ) {
        $this->dataSetup = $dataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->quoteSetupFactory = $setupFactory;
    }

    /**
     * @inheritdoc
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $this
            ->uninstallTables($installer)
            ->uninstallAttributes()
            ->uninstallConfigData($installer);

        $installer->endSetup();
    }

    /**
     * Uninstall all module tables
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function uninstallTables(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $connection->dropTable($installer->getTable('aw_giftcard_product_entity_amounts'));
        $connection->dropTable($installer->getTable('aw_giftcard_product_entity_templates'));
        $connection->dropTable($installer->getTable('aw_giftcard_pool_code'));
        $connection->dropTable($installer->getTable('aw_giftcard_pool'));
        $connection->dropTable($installer->getTable('aw_giftcard_history_entity'));
        $connection->dropTable($installer->getTable('aw_giftcard_history'));
        $connection->dropTable($installer->getTable('aw_giftcard_creditmemo'));
        $connection->dropTable($installer->getTable('aw_giftcard_invoice'));
        $connection->dropTable($installer->getTable('aw_giftcard_order'));
        $connection->dropTable($installer->getTable('aw_giftcard_quote'));
        $connection->dropTable($installer->getTable('aw_giftcard_statistics'));
        $connection->dropTable($installer->getTable('aw_giftcard'));

        return $this;
    }

    /**
     * Uninstall attributes
     *
     * @return $this
     */
    private function uninstallAttributes()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->dataSetup]);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_TYPE);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_DESCRIPTION);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_EXPIRE);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_CUSTOM_MESSAGE_FIELDS);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_EMAIL_TEMPLATES);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_AMOUNTS);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_ALLOW_OPEN_AMOUNT);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_OPEN_AMOUNT_MIN);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_OPEN_AMOUNT_MAX);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_ALLOW_DELIVERY_DATE);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_DAYS_ORDER_DELIVERY);
        $eavSetup->removeAttribute(Product::ENTITY, ProductAttributeInterface::CODE_AW_GC_POOL);

        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $this->dataSetup]);

        $attributes = [
            'aw_giftcard_amount',
            'base_aw_giftcard_amount'
        ];
        foreach ($attributes as $attributeCode) {
            $quoteSetup->removeAttribute('quote', $attributeCode);
            $quoteSetup->removeAttribute('quote_address', $attributeCode);
        }

        return $this;
    }

    /**
     * Uninstall module data from config
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function uninstallConfigData(SchemaSetupInterface $installer)
    {
        $configTable = $installer->getTable('core_config_data');
        $installer->getConnection()->delete($configTable, "`path` LIKE 'aw_giftcard%'");

        return $this;
    }
}
