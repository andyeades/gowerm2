<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */

namespace Mirasvit\Kb\Block\Category;

use Mirasvit\Kb\Model\Article;
use Magento\Framework\View\Element\Template;

class View extends Template {
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory
     */
    protected $articleCollectionFactory;

    /**
     * @var \Mirasvit\Kb\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    private $filterProvider;

    /**
     * @param \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory  $articleCollectionFactory
     * @param \Mirasvit\Kb\Model\Config                                   $config
     * @param \Magento\Cms\Model\Template\FilterProvider                  $filterProvider
     * @param \Magento\Framework\Registry                                 $registry
     * @param \Magento\Customer\Model\Session                             $customerSession
     * @param \Magento\Framework\View\Element\Template\Context            $context
     * @param array                                                       $data
     */
    public function __construct(
        \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory,
        \Mirasvit\Kb\Model\Config $config,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->articleCollectionFactory = $articleCollectionFactory;
        $this->config = $config;
        $this->filterProvider = $filterProvider;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->context = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout() {
        parent::_prepareLayout();

        $category = $this->getCategory();
        if (!$category) {
            return $this;
        }

        $metaTitle = $category->getMetaTitle();
        if (!$metaTitle) {
            $metaTitle = $category->getName();
        }

        $metaDescription = $category->getMetaDescription();
        if (!$metaDescription) {
            $metaDescription = $metaTitle;
        }
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($metaDescription);
        $this->pageConfig->setKeywords($category->getMetaKeywords());

        if ($category && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb(
                'home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ]
            );
            $ids = $category->getParentIds();
            if (in_array(1, $ids)) {
                unset($ids[array_search(1, $ids)]);
            }

            $ids[] = 0;
            $parents = $this->categoryCollectionFactory->create()->addFieldToFilter('category_id', $ids)->setOrder('level', 'asc');
            foreach ($parents as $cat) {
                $breadcrumbs->addCrumb(
                    'kbase' . $cat->getUrlKey(), [
                    'label' => $cat->getName(),
                    'title' => $cat->getName(),
                    'link'  => $cat->getUrl(),
                ]
                );
            }
            $breadcrumbs->addCrumb(
                'kbase' . $category->getUrlKey(), [
                'label' => $category->getName(),
                'title' => $category->getName(),
            ]
            );
        }

        return $this;
    }

    public function getPreviewText($article) {


        $text = $article->getTextHtml();
        $length = 300;
        if (strlen($article->getMetaDescription()) > 10) {
            $text = $article->getMetaDescription();



            $string = (strlen($text) > $length) ? substr($text,0,$length).'...' : $text;


            return $string;
        }
        if (preg_match("/\/[a-z]*>/i", $text)) {
            $text = strip_tags(substr($text, strpos($text, "<p>"), strpos($text, "</p>")));
            $text = preg_replace("/\s|&nbsp;/", ' ', $text); // -- we need this to make trim work
        }

        return $string = (strlen($text) > $length) ? substr($text,0,$length).'...' : $text;
    }

    /**
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getKbPageTitle() {
        return $this->getLayout()->getBlock('page.main.title')->toHtml();
    }

    /**
     * Retrieve current category
     *
     * @return \Mirasvit\Kb\Model\Category
     */
    public function getCategory() {
        if ($this->hasData('category')) {
            return $this->getData('category');
        }

        return $this->registry->registry('kb_current_category');
    }

    /**
     * Retrieve current category
     *
     * @return \Mirasvit\Kb\Model\Category
     */
    public function getCategoryById($category_id) {

            $category = $this->categoryCollectionFactory->create()->getItemById($category_id);

        return $category;
    }

    /**
     * @return \Mirasvit\Kb\Model\ResourceModel\Category\Collection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoryCollection() {
        $collection = $this->categoryCollectionFactory->create()->addFieldToFilter('parent_id', $this->getCategory()->getId())->addFieldToFilter('is_active', true)->addStoreIdFilter($this->context->getStoreManager()->getStore()->getId())->setOrder('position', 'asc');

        return $collection;
    }

    /**
     * @param object $category
     *
     * @return \Mirasvit\Kb\Model\ResourceModel\Article\Collection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getArticleCollection($category) {
        $collection = $this->articleCollectionFactory->create()->addCategoryIdFilter($category->getId())->addFieldToFilter('main_table.is_active', true)->addStoreIdFilter($this->context->getStoreManager()->getStore()->getId())->addCustomerGroupIdFilter($this->customerSession->getCustomerGroupId())->setPageSize($this->config->getArticleLinksLimit())->setOrder('position', 'asc');

        return $collection;
    }

    /**
     * @param $input
     * @param $pagesize
     *
     * @return \Mirasvit\Kb\Model\ResourceModel\Article\Collection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllArticleCollection($input, $pagesize) {
        $collection = $this->articleCollectionFactory->create()->addCategoryIdFilter($input)->addFieldToFilter('main_table.is_active', true)->addStoreIdAltFilter($this->context->getStoreManager()->getStore()->getId(),$input)->addCustomerGroupIdFilter($this->customerSession->getCustomerGroupId())->setPageSize($pagesize)->setOrder('position', 'asc');
        return $collection;
    }

    /**
     * @param object $category
     *
     * @return string
     */
    public function getArticleAmount($category) {
        $html = '';
        $articles = $this->getArticleCollection($category);
        if ($this->config->getCategoryArticleAmount() && $articles && $articles->count()) {
            $html = ' (' . $articles->count() . ')';
        }

        return $html;
    }

    /**
     * @return int
     */
    public function getPageLimit() {
        return $this->config->getArticleLinksLimit();
    }

    /**
     * @param object $category
     *
     * @return int
     */
    public function getArticlesNumber($category) {
        return $category->getArticlesNumber();
    }

    /**
     * @return string
     */
    public function getArticleListHtml() {
        return $this->getChildHtml('kb.article_list');
    }

    /**
     * @param \Mirasvit\Kb\Model\Category $category
     * @param null                        $store
     * @param bool|false                  $isPaged
     *
     * @return \Mirasvit\Kb\Model\ResourceModel\Category\Collection
     */
    public function getCategoryChildren(
        $category,
        $store = NULL,
        $isPaged = false
    ) {
        $collection = $category->getChildren($store);
        if ($collection && $isPaged) {
            $collection->setPageSize($this->config->getArticleLinksLimit());
        }

        return $collection;
    }

    /**
     * Prepare HTML content
     *
     * @return string
     */
    protected function _toHtml() {
        return parent::_toHtml();
        // we can't use filter here, because if we switch to inline translation mode
        // we may receive an error.
        // if you would like to filter - filter text which does not require inline translation
        // return $this->filterProvider->getPageFilter()->filter(parent::_toHtml());
    }

    /**
     * Return identifiers for produced content.
     *
     * @return array
     */
    public function getIdentities() {
        return [Article::CACHE_KB_ARTICLE_CATEGORY . '_' . $this->getCategory()->getId()];
    }
}
