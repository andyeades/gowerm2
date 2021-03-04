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


class CmsDataBlocks5 implements DataPatchInterface, PatchRevertableInterface
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
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //The code that you want apply in the patch
        try {
            $this->blockRepository->getById('ev-hdr-top-list-loggedin');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<ul class="hdr-top-list">
<li> <a aria-label="Track your order" href="/trackorder" style="display:flex"><i class="header-trackorder-ico" style="    height: 22px;
    width: 22px;
    background: url({{media url="theme_assets/trackmyorder.png"}});
    background-size: contain;
    background-repeat: no-repeat;
display:block;"></i>
<div class="header-trackorder-name" style="    display: inline-block;
    padding-left: 10px;">Track Order</div>
</a>
</li>
<li class="hdr-acc-li"><a class="hdr-myacc-link" href="/customer/account/" aria-label="View Your Account"><svg viewBox="0 0 1000 1000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g><path d="M657.5,466.7c59.7-46.9,98.1-119.4,98.1-201C755.6,124.7,640.9,10,500,10c-141,0-255.6,114.7-255.6,255.7c0,81.5,38.5,154.1,98.2,201C185.4,529.4,73.9,682.9,73.9,862.2v85.2C73.9,971,93,990,116.5,990h767c23.6,0,42.6-19,42.6-42.6v-85.2c0-179.3-96.7-331.4-261.2-397.1L657.5,466.7z M500,76.3c98.4,0,178.4,80,178.4,178.4c0,98.4-80,178.4-178.4,178.4c-98.4,0-178.4-80-178.4-178.4C321.6,156.2,401.6,76.3,500,76.3L500,76.3z M856.8,923.7H143.1v-44.6c0-196.7,160.1-356.8,356.9-356.8c196.8,0,356.8,160.1,356.8,356.8L856.8,923.7L856.8,923.7z"/></g></svg><span>My Account</span></a> <a class="sign-in" href="/customer/account/logout" aria-label="Sign Out">Sign Out</a></li>
</ul>';
            $cmsBlock->setIdentifier('ev-hdr-top-list-loggedin');
            $cmsBlock->setTitle('ev-hdr-top-list-loggedin');
            $cmsBlock->setContent($content);
            $cmsBlock->setData('stores', [Store::DEFAULT_STORE_ID]); // DEFAULT_STORE_ID = 0
            $this->blockRepository->save($cmsBlock);
        }
        $this->moduleDataSetup->getConnection()->endSetup();
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
