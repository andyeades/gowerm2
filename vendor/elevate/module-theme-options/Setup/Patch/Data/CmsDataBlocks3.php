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


class CmsDataBlocks3 implements DataPatchInterface, PatchRevertableInterface
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
       // ev-footer-above-inner-content was here
        try {
            $this->blockRepository->getById('ev-footer-main-inside-top-inner-content');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div id="footer-social" class="footer-social-icons">
<a rel="noopener" href="https://www.facebook.com/" target="_blank" class="soc-ico-fb" title="Facebook"><i class="fa fa-facebook"></i></a>
<a rel="noopener" href="https://twitter.com/" target="_blank" class="soc-ico-twit" title="Twitter"><i class="fa fa-twitter"></i></a>
<a rel="noopener" href="https://www.youtube.com/user/" target="_blank" class="soc-ico-yt" title="YouTube"><i class="fa fa-youtube"></i></a>
<a rel="noopener" href="https://uk.pinterest.com/" target="_blank" class="soc-ico-pin" title="Pinterest"><i class="fa fa-pinterest"></i></a>
<a rel="noopener" href="https://www.instagram.com/" target="_blank" class="soc-ico-inst" title="Instagram"><i class="fa fa-instagram"></i></a>
</div>';
            $cmsBlock->setIdentifier('ev-footer-main-inside-top-inner-content');
            $cmsBlock->setTitle('ev-footer-main-inside-top-inner-content');
            $cmsBlock->setContent($content);
            $cmsBlock->setData('stores', [Store::DEFAULT_STORE_ID]); // DEFAULT_STORE_ID = 0
            $this->blockRepository->save($cmsBlock);
        }

        try {
            $this->blockRepository->getById('ev-footer-below-inner-content');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div id="ev-footer-below-inner-content" class="">
<div id="footer-mid-left">
<div id="footer-cards">
<div class="ftr-card-stripe"></div>
<div class="ftr-cards-visa-mastercard"></div>
<div class="ftr-card-paypal"></div>
</div>
<div class="footer-secure-message">Shopping on website is safe and secure. All information is encrypted and transmitted without risk using our secure payment facility</div>
</div>
<div id="footer-mid-right">
<div>Feefo Icon?</div>
</div>
</div>
<div class="footer-copyright row">
<div class="footer-copy-left">Company Details Here, Address etc
<div class="ftr-copy-left-item">Registered in England and Wales No: Company Number</div>
</div>
<div class="footer-copy-right">© Company Name 2010 - 2019. All Rights Reserved.</div>
</div>
';
            $cmsBlock->setIdentifier('ev-footer-below-inner-content');
            $cmsBlock->setTitle('ev-footer-below-inner-content');
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
