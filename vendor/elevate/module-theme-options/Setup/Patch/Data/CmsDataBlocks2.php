<?php

namespace Elevate\Themeoptions\Setup\Patch\Data;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;
use Elevate\Themeoptions\Api\OptionsRepositoryInterface;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;


class CmsDataBlocks2 implements DataPatchInterface, PatchRevertableInterface
{
    /** @var  BlockRepositoryInterface */
    private $blockRepository;

    /** @var BlockFactory */
    private $blockInterfaceFactory;

    /** @var State */
    private $state;

    /** @var \Elevate\Themeoptions\Api\OptionsRepositoryInterface */
    protected $optionsRepositoryInterface;

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
     * @param OptionsRepositoryInterface $optionsRepositoryInterface
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockInterfaceFactory    $blockInterfaceFactory
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        OptionsRepositoryInterface $optionsRepositoryInterface,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockInterfaceFactory,
        State $state
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->optionsRepositoryInterface = $optionsRepositoryInterface;
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
            $this->blockRepository->getById('ev-footer-block-1');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div class="footer-block-internal-1">
  <h4>Useful Links</h4>
  <ul>
    <li><a href="/about-us">About Us</a></li>
    <li><a href="/blog">Our Blog</a></li>
    <li><a href="/privacy-policy">Privacy Policy</a></li>
    <li><a href="/terms-and-conditions">Terms and Conditions</a></li>
  </ul>
</div>';
            $cmsBlock->setIdentifier('ev-footer-block-1');
            $cmsBlock->setTitle('ev-footer-block-1');
            $cmsBlock->setContent($content);
            $cmsBlock->setData('stores', [Store::DEFAULT_STORE_ID]); // DEFAULT_STORE_ID = 0
            $this->blockRepository->save($cmsBlock);
        }
        try {
            $this->blockRepository->getById('ev-footer-block-2');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div class="footer-block-internal-1">
<h4>Customer Care</h4>
<ul>
  <li><a href="/customer/account">My Account</a></li>
  <li><a href="/customer/account/login">Login</a></li>
  <li><a href="/contact">Contact</a></li>
</ul>
</div>

<div class="footer-block-internal-1">
<h4>Order Information</h4>

<ul>
  <li><a href="/returns-policy">Returns Policy</a></li>
  <li><a href="/delivery-information">Delivery Information</a></li>
  <li><a href="/sales/guest/form/">Order History</a></li>
</ul>
</div>';
            $cmsBlock->setIdentifier('ev-footer-block-2');
            $cmsBlock->setTitle('ev-footer-block-2');
            $cmsBlock->setContent($content);
            $cmsBlock->setData('stores', [Store::DEFAULT_STORE_ID]); // DEFAULT_STORE_ID = 0
            $this->blockRepository->save($cmsBlock);
        }
        try {
            $this->blockRepository->getById('ev-footer-block-3');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div class="footer-block-internal">
  <h4>Newsletter</h4>
  <div class="ftr-b-collapsible">
    <form action="/newsletter/subscriber/new/" method="post" id="newsletter-validate-detail">
      <p>Sign up for our newsletter to get our latest offers and great training tips.</p>
      <div class="footer-input-search">
        <div class="footer-input-search-row">
          <input placeholder="Enter your email address" name="email" class="footer-input form-control required-entry validate-email" title="Sign up for our newsletter">
        </div>
        <div class="footer-input-search-row">
          <button class="btn footer-input-search-button" title="Subscribe" type="submit">Sign Up</button>
        </div>
      </div>
    </form>
  </div>
</div>
';
            $cmsBlock->setIdentifier('ev-footer-block-3');
            $cmsBlock->setTitle('ev-footer-block-3');
            $cmsBlock->setContent($content);
            $cmsBlock->setData('stores', [Store::DEFAULT_STORE_ID]); // DEFAULT_STORE_ID = 0
            $this->blockRepository->save($cmsBlock);
        }
        try {
            $this->blockRepository->getById('ev-footer-block-4');
        } catch (NoSuchEntityException $e) {
            $cmsBlock = $this->blockInterfaceFactory->create();
            $content = '<div class="footer-block-internal">
  <h4>Contact Us</h4>
  <ul>
    <li>Your Location, Your Building, Your Street, Your City, YOUR POSTCODE</li>
    <li><a href="mailto:">your@email.com</a></li>
  </ul>
</div>

<div class="footer-copyright">&copy; 2020 Your Company Name</div>
';
            $cmsBlock->setIdentifier('ev-footer-block-4');
            $cmsBlock->setTitle('ev-footer-block-4');
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
