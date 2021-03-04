<?php

namespace Elevate\Delivery\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;

class Attributes1 implements DataPatchInterface, PatchRevertableInterface {

    /** @var State */
    private $state;

    /** @var \Magento\Eav\Setup\EavSetupFactory */
    protected $eavSetupFactory;

    /** @var \Magento\Eav\Model\Config */
    protected $eavConfig;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     *
     */

    /**
     * InstallData constructor.
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Eav\Setup\EavSetupFactory                $eavSetupFactory
     * @param \Magento\Eav\Api\AttributeRepositoryInterface     $attributeRepository
     * @param \Magento\Eav\Model\Config                         $eavConfig
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Eav\Model\Config $eavConfig,
        State $state
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeRepository = $attributeRepository;
        $this->eavConfig = $eavConfig;
        $this->state = $state;
    }
    /**
     * If before, we pass $setup as argument in install/upgrade function, from now we start
     * inject it with DI. If you want to use setup, you can inject it, with the same way as here
     */

    /**
     * {@inheritdoc}
     */
    public function apply() {
        $this->moduleDataSetup->getConnection()->startSetup();
        //The code that you want apply in the patch
        try {

            $attribute = $this->attributeRepository->get(Product::ENTITY,
                'handling_time'
            );
        } catch(\Magento\Framework\Exception\NoSuchEntityException $e) {
            /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup])->addAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE, 'handling_time', [
                                 'type'       => 'varchar',
                                 'label'      => 'Handling Time (Days)',
                                 'input'      => 'text',
                                 'default'    => '0',
                                 'required'   => false,
                                 'sort_order' => 2000,
                                 'global'     => ScopedAttributeInterface::SCOPE_WEBSITE,
                                 'group'      => 'General',
                                 'visible_on_front'        => false,
                                 'used_in_product_listing' => false
                             ]
                );
        }
        try {
            $this->attributeRepository->get(
                Product::ENTITY,
                'date_next_available'
            );
        } catch(\Magento\Framework\Exception\NoSuchEntityException $e) {
            /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup])->addAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE, 'date_next_available', [
                                 'type'       => 'varchar',
                                 'label'      => 'Date Next Available',
                                 'input'      => 'text',
                                 'default'    => null,
                                 'required'   => false,
                                 'sort_order' => 2000,
                                 'global'     => ScopedAttributeInterface::SCOPE_WEBSITE,
                                 'group'      => 'General',
                                 'visible_on_front'        => false,
                                 'used_in_product_listing' => false,
                             ]
                );
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies() {
        /**
         * This is dependency to another patch. Dependency should be applied first
         * One patch can have few dependencies
         * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
         * versions, right now you need to point from patch with higher version to patch with lower version
         * But please, note, that some of your patches can be independent and can be installed in any sequence
         * So use dependencies only if this important for you
         */
        return [];
        //return [
        //  SomeDependency::class
        //];
    }

    public function revert() {
        $this->moduleDataSetup->getConnection()->startSetup();
        //Here should go code that will revert all operations from `apply` method
        //Please note, that some operations, like removing data from column, that is in role of foreign key reference
        //is dangerous, because it can trigger ON DELETE statement
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases() {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
