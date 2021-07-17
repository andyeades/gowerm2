<?php

namespace Elevate\Themeoptions\Setup\Patch\Data;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;


class CmsDataBlocks8 implements DataPatchInterface, PatchRevertableInterface
{
    /** @var  BlockRepositoryInterface */
    private $blockRepository;

    /** @var BlockFactory */
    private $blockInterfaceFactory;

    /** @var State */
    private $state;


    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     *
     */

    /**
     * InstallData constructor.
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockInterfaceFactory    $blockInterfaceFactory
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockInterfaceFactory,
        State $state
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockRepository = $blockRepository;
        $this->blockInterfaceFactory = $blockInterfaceFactory;
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
            $this->blockRepository->getById('elevate-footer-newsletter-text');
        } catch(NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div class="newsletter-additional">Never miss our next offer</div>
<h3>Sign Up For Our Newsletter</h3>';
            $cmsBlock->setIdentifier('elevate-footer-newsletter-text');
            $cmsBlock->setTitle('elevate-footer-newsletter-text');
            $cmsBlock->setContent($content);
            $cmsBlock->setData('stores', [Store::DEFAULT_STORE_ID]); // DEFAULT_STORE_ID = 0
            $this->blockRepository->save($cmsBlock);
        }

    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
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

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //Here should go code that will revert all operations from `apply` method
        //Please note, that some operations, like removing data from column, that is in role of foreign key reference
        //is dangerous, because it can trigger ON DELETE statement
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
