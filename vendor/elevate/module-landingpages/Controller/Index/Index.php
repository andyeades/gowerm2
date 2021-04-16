<?php

namespace Elevate\LandingPages\Controller\Index;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Catalog session
     *
     * @var \Magento\Catalog\Model\Session
     */
    protected $_catalogSession;

    /**
     * Catalog design
     *
     * @var \Magento\Catalog\Model\Design
     */
    protected $_catalogDesign;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator
     */
    protected $categoryUrlPathGenerator;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Catalog Layer Resolver
     *
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var ToolbarMemorizer
     */
    private $toolbarMemorizer;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context                     $context
     * @param \Magento\Catalog\Model\Design                             $catalogDesign
     * @param \Magento\Catalog\Model\Session                            $catalogSession
     * @param \Magento\Framework\Registry                               $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface                $storeManager
     * @param \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator
     * @param \Magento\Framework\View\Result\PageFactory                $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory       $resultForwardFactory
     * @param Resolver                                                  $layerResolver
     * @param CategoryRepositoryInterface                               $categoryRepository
     * @param ToolbarMemorizer|null                                     $toolbarMemorizer
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Design $catalogDesign,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        ToolbarMemorizer $toolbarMemorizer = null
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_catalogDesign = $catalogDesign;
        $this->_catalogSession = $catalogSession;
        $this->_coreRegistry = $coreRegistry;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->layerResolver = $layerResolver;
        $this->categoryRepository = $categoryRepository;
        $this->toolbarMemorizer = $toolbarMemorizer ?: $context->getObjectManager()->get(ToolbarMemorizer::class);
    }

    /**
     * Initialize requested category object
     *
     * @return \Magento\Catalog\Model\Category|bool
     */
    protected function _initCategory($categoryId)
    {
        $categoryId = (int)$categoryId;

        if (!$categoryId) {
            return false;
        }

        try {
            $category = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
        } catch (NoSuchEntityException $e) {
            return false;
        }
        if (!$this->_objectManager->get(\Magento\Catalog\Helper\Category::class)->canShow($category)) {
            return false;
        }

        $this->_catalogSession->setLastVisitedCategoryId($category->getId());

        $this->_coreRegistry->register('current_category', $category);
        $this->toolbarMemorizer->memorizeParams();
        try {
            $this->_eventManager->dispatch(
                'catalog_controller_category_init_after',
                ['category'          => $category,
                                                           'controller_action' => $this
                                                        ]
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);

            return false;
        }

        return $category;
    }

    public function execute()
    {

        //this function runs for a landing page for example
        //https://m2.happybeds.co.uk/beds/theme-beds

        $identifier = $this->_request->getPathInfo();
        $url = ltrim($identifier, '/');

        //Get Object Manager Instance
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $contactModel = $objectManager->create('Elevate\LandingPages\Model\LandingPage');

        $params = $this->_request->getParams();

        //   print_r($params);
        if (isset($params['landingpage_id'])) {
            $landingpage_id = $params['landingpage_id'];
        } else {
            return false;
        }
        if (!is_numeric($landingpage_id)) {
            return false;
        }

        //we could reduce the overhead of this also by storing values in the actual rewrite table
        //TODO - Improvement
        $landingPageModel = $contactModel->load($landingpage_id);

        if ($landingPageModel) {

            //   echo "<pre>";
            //  print_r($landingPageModel->getData());
            //echo "</pre>";

            $landingPage = $landingPageModel->getData();
            $this->_coreRegistry->register('elevate_landingpage', true);
            $this->_coreRegistry->register('elevate_landingpage_data', $landingPage);

            $page_title = $landingPageModel->getData('page_title');
            $url_key = $landingPageModel->getData('url_key');
            $meta_title = $landingPageModel->getData('meta_title');
            $meta_description = $landingPageModel->getData('meta_description');
            $meta_keywords = $landingPageModel->getData('meta_keywords');
            $canonical_url = $landingPageModel->getData('canonical_url');
            $page_top_description = $landingPageModel->getData('page_top_description');
            $page_bottom_description = $landingPageModel->getData('page_bottom_description');
        } else {
            return false;
        }

        //we must have a category id to match on, so we can use the category controllers
        if (isset($landingPage['category_ids'])) {
            $categoryIdsValue = $landingPage['category_ids'];
            $categoryIds = explode(',', $categoryIdsValue);
            $firstCategoryId = $categoryIds[0];
        }

        //if there is no category - then we cant show the landing page
        if (!is_numeric($firstCategoryId)) {
            return false;
        }

        //behave like category - shows
        if (isset($landingPage['behave_like_category'])) {
            $behave_like_category_id = $landingPage['behave_like_category'];
            if (is_numeric($behave_like_category_id) && $behave_like_category_id > 0) {
                $firstCategoryId = $behave_like_category_id;
            }
        }

        $category = $this->_initCategory($firstCategoryId);
        // $this->_initParams();

        /* mapping for custom filters*/

        /**/

        if ($category) {

            // set the params
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $current_params = [];
            $attributes = $connection->fetchPairs("SELECT attribute_id,option_id FROM elevate_landingpages_attributes WHERE landingpage_landingpage_id = ?", [$landingPage['landingpage_id']]);
            foreach ($attributes as $attribute_id => $option_id) {
                $eavModel = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
                $attr = $eavModel->load($attribute_id);
                $attributeCode = $eavModel->getAttributeCode();//Get attribute code from its id

                $this->_request->setParam($attributeCode, $option_id);
                $current_params[$attributeCode] = $option_id;
            }

            //$this->_coreRegistry->register('elevate_landingpage_data', $landingPage);
            $this->_coreRegistry->register('elevate_landingpage_attributes', $current_params);

            $this->layerResolver->create(Resolver::CATALOG_LAYER_CATEGORY);
            $settings = $this->_catalogDesign->getDesignSettings($category);

            // apply custom design
            if ($settings->getCustomDesign()) {
                //    $this->_catalogDesign->applyCustomDesign($settings->getCustomDesign());
            }

            //$this->_catalogSession->setLastViewedCategoryId($category->getId());

            $page = $this->resultPageFactory->create();

            // $page->getLayout()->getBlock('page.main.title')->setPageTitle(__('Shop All Products'));

            //override breadcrumb route manually

            $page->getLayout()->getBlock('breadcrumbs')->addCrumb(
                'home',
                [
                          'label' => __('Home'),
                          'title' => __('Go to Home Page'),
                          'link'  => ''
                      ]
            )->addCrumb(
                'product-tag',
                [
                                 'label' => __('Shop All Products'),
                                 'title' => __('Shop All Products')
                             ]
            );

            //here we set the custom configuration for the landingpages
            $page->getLayout()->getBlock('breadcrumbs')->addCrumb(
                'product-tag',
                [
                                 'label' => __($page_title),
                                 'title' => __($page_title),
                                 'link'  => '/' . $url_key

                             ]
            );

            $pageMainTitle = $page->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($page_title);
            }

            $category->setDescription($page_top_description);

            if (!empty($page_title)) {
                $page->getConfig()->getTitle()->set(__($meta_title));
            }
            if (!empty($meta_keywords)) {
                $page->getConfig()->setKeywords(__($meta_keywords));
            }
            if (!empty($page_top_description)) {
                $page->getConfig()->setDescription(__($meta_description));
            }

            //  $page->getConfig()->addBodyClass('page-products');

            //    $page->getConfig()->addRemotePageAsset($this->_url->getUrl('shop-all/index/index'), 'canonical', ['attributes' => ['rel' => 'canonical']]);

            /*
            // apply custom layout (page) template once the blocks are generated
            if ($settings->getPageLayout()) {
                $page->getConfig()->setPageLayout($settings->getPageLayout());
            }

            $hasChildren = $category->hasChildren();
            if ($category->getIsAnchor()) {
                $type = $hasChildren ? 'layered' : 'layered_without_children';
            } else {
                $type = $hasChildren ? 'default' : 'default_without_children';
            }

            if (!$hasChildren) {
                // Two levels removed from parent.  Need to add default page type.
                $parentType = strtok($type, '_');
                $page->addPageLayoutHandles(['type' => $parentType], null, false);
            }
            $page->addPageLayoutHandles(['type' => $type], null, false);
            $page->addPageLayoutHandles(['id' => $category->getId()]);

            // apply custom layout update once layout is loaded
            $layoutUpdates = $settings->getLayoutUpdates();
            if ($layoutUpdates && is_array($layoutUpdates)) {
                foreach ($layoutUpdates as $layoutUpdate) {
                    $page->addUpdate($layoutUpdate);
                    $page->addPageLayoutHandles(['layout_update' => sha1($layoutUpdate)], null, false);
                }
            }

            $page->getConfig()->addBodyClass('page-products')
                ->addBodyClass('categorypath-' . $this->categoryUrlPathGenerator->getUrlPath($category))
                ->addBodyClass('category-' . $category->getUrlKey());
               */

            return $page;
        } elseif (!$this->getResponse()->isRedirect()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }

    public function execute2()
    {
        $store = $this->_storeManager->getStore();
        $category = $this->categoryRepository->get(
            $store->getRootCategoryId()
        );

        $this->_coreRegistry->register('current_category', $category);

        $page = $this->resultPageFactory->create();

        $page->getLayout()->getBlock('page.main.title')->setPageTitle(__('Shop All Products'));
        $page->getLayout()->getBlock('breadcrumbs')->addCrumb(
            'home',
            [
                      'label' => __('Home'),
                      'title' => __('Go to Home Page'),
                      'link'  => $store->getBaseUrl()
                  ]
        )->addCrumb(
            'product-tag',
            [
                             'label' => __('Shop All Products'),
                             'title' => __('Shop All Products'),
                         ]
        );
        $page->getConfig()->addBodyClass('page-products');

        if (!empty($page_title)) {
            $page->getConfig()->getTitle()->set(__($page_title));
        }
        if (!empty($meta_keywords)) {
            $page->getConfig()->setKeywords(__($meta_keywords));
        }
        if (!empty($page_top_description)) {
            $page->getConfig()->setDescription(__($page_top_description));
        }

        /*
        $url_key ;
        $meta_title;
        $meta_description;
        $meta_keywords;
        $canonical_url;
        $page_top_description;
        $page_bottom_description;


        */

        $page->getConfig()->addRemotePageAsset($this->_url->getUrl('shop-all/index/index'), 'canonical', ['attributes' => ['rel' => 'canonical']]);

        return $page;
    }
}
