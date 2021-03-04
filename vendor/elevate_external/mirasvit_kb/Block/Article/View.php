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

namespace Mirasvit\Kb\Block\Article;

class View extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Mirasvit\Kb\Model\Config
     */
    protected $config;

    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    protected $kbData;

    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogData;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Mirasvit\Kb\Helper\Vote
     */
    private $kbVote;
    /**
     * @var \Magento\Customer\Model\Url
     */
    private $customerUrl;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Comment\CollectionFactory
     */
    private $commentCollectionFactory;

    /**
     * @var \Mirasvit\Kb\Api\ArticleRepositoryInterface
     */
    protected $articleRepository;

    /**
     * @var \Mirasvit\Kb\Api\ArticlesectionsRepositoryInterface
    */
    protected $articlesectionsRepository;

    /**
     * @var \Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface
     */
    protected $articlesubsectionsRepository;

    /** @var \Elevate\Themeoptions\Helper\General
     *
     */
    protected $ev_helper;

    /**
     * @var \Mirasvit\Kb\Api\Data\ArticleInterface
     */
    protected $current_article;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Api\SortOrderBuilder
     */
    protected $sortOrderBuilder;
    /**
     * @var \Magento\Framework\Api\Filter
     */
    protected $filter;
    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;
    /**
     * @var \Magento\Framework\Api\Search\FilterGroup
     */
    protected $filterGroup;
    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * @param \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory
     * @param \Mirasvit\Kb\Api\ArticleRepositoryInterface $articleRepository
     * @param \Mirasvit\Kb\Api\ArticlesectionsRepositoryInterface $articlesectionsRepository
     * @param \Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface $articlesubsectionsRepository
     * @param \Mirasvit\Kb\Model\Config $config
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Mirasvit\Kb\Helper\Vote $kbVote
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
     * @param \Magento\Framework\Api\Filter $filter
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder
     * @param \Elevate\Themeoptions\Helper\General $ev_helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Mirasvit\Kb\Api\Data\ArticleInterface $current_article
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     *
     * @param array $data
     */
    public function __construct(
        \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Mirasvit\Kb\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \Mirasvit\Kb\Api\ArticleRepositoryInterface $articleRepository,
        \Mirasvit\Kb\Api\ArticlesectionsRepositoryInterface $articlesectionsRepository,
        \Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface $articlesubsectionsRepository,
        \Mirasvit\Kb\Model\Config $config,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Mirasvit\Kb\Helper\Vote $kbVote,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Elevate\Themeoptions\Helper\General $ev_helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Mirasvit\Kb\Api\Data\ArticleInterface $current_article,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->commentCollectionFactory  = $commentCollectionFactory;
        $this->articleRepository = $articleRepository;
        $this->articlesectionsRepository = $articlesectionsRepository;
        $this->articlesubsectionsRepository = $articlesubsectionsRepository;
        $this->config                    = $config;
        $this->kbData                    = $kbData;
        $this->kbVote                    = $kbVote;
        $this->customerFactory           = $customerFactory;
        $this->customerSession           = $customerSession;
        $this->customerUrl               = $customerUrl;
        $this->catalogData               = $catalogData;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filter = $filter;
        $this->filterGroup = $filterGroup;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->ev_helper = $ev_helper;
        $this->registry                  = $registry;
        $this->context                   = $context;
        $this->current_article           = $current_article;
        $this->filterProvider            = $filterProvider;


        parent::__construct($context, $data);
    }

    // Process the cms related content
    public function getParsedContent($content) {
        return $this->filterProvider->getBlockFilter()->setStoreId($this->_storeManager->getStore()->getId())->filter($content);
    }

    /**
     * @param $articlesectionname
     *
     * @return mixed
     */
    public function getSectionUrl($articlesectionname) {
        return rtrim(str_replace(['----','---','--'],'-', (str_replace([',','(', ')', '£', '[', ']', ';', '&', '!', '?', ':', '\\', '/', '*', ' '], '-', strtolower($articlesectionname)))), '-');
    }

    /**
     * @param $parentarticle_id
     *
     * @return \Mirasvit\Kb\Api\Data\ArticlesectionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getArticleSections($parentarticle_id) {


        $filters = array(
            array(
                'field' => 'parentarticle_id',
                'value' => $parentarticle_id,
                'condition_type' => 'eq'
            ),
            array(
                'field' => 'asec_is_active',
                'value' => 1,
                'condition_type' => 'eq'
            )
        );

        $sortorder = array(
            'field' => 'asec_position',
            'direction' => 'ASC'
        );


        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);
        $articleSections = $this->articlesectionsRepository->getList($searchCriteria);


        return $articleSections;
    }

    /**
     * @param $parentarticlesection_id
     *
     * @return \Mirasvit\Kb\Api\Data\ArticlesubsectionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getArticleSubSections($parentarticlesection_id) {
        $filters = array(
            array(
                'field' => 'parentarticlesection_id',
                'value' => $parentarticlesection_id,
                'condition_type' => 'eq'
            ),
            array(
                'field' => 'asecsub_is_active',
                'value' => 1,
                'condition_type' => 'eq'
            )
        );

        $sortorder = array(
            'field' => 'asecsub_position',
            'direction' => 'ASC'
        );


        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);
        $articlesubsections = $this->articlesubsectionsRepository->getList($searchCriteria);


        return $articlesubsections;
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $article = $this->getArticle();

        if (!$article) {
            return;
        }

        $category = $article->getCategory();
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($article->getMetaTitle() ? $article->getMetaTitle() : $article->getName());
            $headBlock->setDescription($article->getMetaDescription());
            $headBlock->setKeywords($article->getMetaKeywords());
        }

        $metaTitle = $article->getMetaTitle();
        if (!$metaTitle) {
            $metaTitle = $article->getName();
        }

        $metaDescription = $article->getMetaDescription();
        if (!$metaDescription) {
            $metaDescription = $this->filterManager->truncate(
                $this->filterManager->stripTags($article->getText()),
                ['length' => 150, 'etc' => ' ...', 'remainder' => '', 'breakWords' => false]
            );
        }
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setDescription($article->getName() . ' ' . $metaDescription);
        $this->pageConfig->setKeywords($article->getMetaKeywords());

        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->context->getUrlBuilder()->getBaseUrl(),
            ]);
            $ids = [0];
            /*
             *
             */
            if (is_array($category->getParentIds())) {
                $ids = array_merge($ids, $category->getParentIds());
            }
            if (in_array(1, $ids)) {
                unset($ids[array_search(1, $ids)]);
            }
            $ids[]   = 0;
            $parents = $this->categoryCollectionFactory->create()
                ->addFieldToFilter('category_id', $ids)
                ->setOrder('level', 'asc');
            foreach ($parents as $cat) {
                $breadcrumbs->addCrumb('kbase' . $cat->getUrlKey(), [
                    'label' => $cat->getName(),
                    'title' => $cat->getName(),
                    'link'  => $cat->getUrl(),
                ]);
            }
            if (count($article->getCategories()) === 1) {
                $breadcrumbs->addCrumb('kbase' . $category->getUrlKey(), [
                    'label' => $category->getName(),
                    'title' => $category->getName(),
                    'link' => $category->getUrl(),
                ]);
            }
            $breadcrumbs->addCrumb('kbase' . $article->getUrlKey(), [
                'label' => $article->getName(),
                'title' => $article->getName(),
            ]);
        }
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($article->getName());
        }
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getKbPageTitle()
    {
        return $this->getLayout()->getBlock('page.main.title')->toHtml();
    }

    /**
     * @return null|\Mirasvit\Kb\Model\Article
     */
    public function getArticle()
    {
        return $this->registry->registry('current_article');
    }
    /**
     * @return \Mirasvit\Kb\Api\Data\ArticleInterface
     */
    public function getArticleByRepo()
    {
        $article_id = $this->getRequest()->getParam('id');

        $article = $this->articleRepository->getById($article_id);

        $this->current_article = $article;
        return $article;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getTextHtml($text)
    {
        return $this->filterProvider->getPageFilter()->filter($text);
    }

    /**
     * @return \Mirasvit\Kb\Model\ResourceModel\Category\Collection
     */
    public function getCategories()
    {
        $collection = $this->getArticle()->getCategories()
            ->addFieldToFilter('is_active', true);

        return $collection;
    }

    /**
     * @return \Mirasvit\Kb\Model\ResourceModel\Tag\Collection
     */
    public function getTags()
    {
        $collection = $this->getArticle()->getTags();

        return $collection;
    }

    /**
     * @param int $vote
     *
     * @return string
     */
    public function getVoteUrl($vote)
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/vote', [
            'id'   => $this->getArticle()->getId(),
            'vote' => $vote,
        ]);
    }

    /**
     * @return int|void
     */
    public function getVoteResult()
    {
        return $this->kbVote->getVoteResult($this->getArticle());
    }

    /**
     * @return bool|null|string
     */
    public function isRatingEnabled()
    {
        return $this->config->isRatingEnabled();
    }

    /**
     * @param \Mirasvit\Kb\Model\Article $article
     *
     * @return string
     * @throws \Exception
     * @deprecated
     */
    public function getArticleText($article)
    {
        $helper    = $this->catalogData;
        $processor = $helper->getPageTemplateProcessor();
        $html      = $processor->filter($article->getText());

        return $html;
    }

    /**
     * @param \Mirasvit\Kb\Model\Comment $comment
     *
     * @return string
     */
    public function getCustomerName($comment)
    {
        $customer = $this->customerFactory->create();
        $customer->getResource()->load($customer, $comment->getCustomerId());

        return $customer->getName();
    }

    /**
     * @return string
     */
    public function getCommentProvider()
    {
        return $this->config->getCommentProvider();
    }


    /**
     * @return string
     */
    public function getDisqusShortname()
    {
        return $this->config->getDisqusShortname();
    }

    /**
     * @return bool
     */
    public function isShowAuthor()
    {
        return !$this->config->isArticleHideAuthor();
    }

    /**
     * @return bool
     */
    public function isShowDate()
    {
        return !$this->config->isArticleHideDate();
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        if ($this->getArticle()) {
            return parent::toHtml();
        }
    }
}
