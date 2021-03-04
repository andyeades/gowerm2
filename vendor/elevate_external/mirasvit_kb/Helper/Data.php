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

namespace Mirasvit\Kb\Helper;

use \Elevate\Themeoptions\Helper\General;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Mirasvit\Kb\Model\Category
     */
    protected $rootCategory;
    /**
     * @var \Mirasvit\Kb\Model\CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\User\Model\ResourceModel\User\CollectionFactory
     */
    private $userCollectionFactory;

    /**
     * @var \Mirasvit\Kb\Api\ArticleRepositoryInterface
     */
    protected $articleRepository;

    /** @var \Elevate\Themeoptions\Helper\General
     *
     */
    protected $ev_helper;

    protected $searchCriteriaBuilder;
    protected $sortOrderBuilder;
    protected $filter;
    protected $filterBuilder;
    protected $filterGroup;
    protected $filterGroupBuilder;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param \Mirasvit\Kb\Model\CategoryFactory $categoryFactory
     * @param \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollectionFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mirasvit\Kb\Api\ArticleRepositoryInterface $articleRepository
     *
     * @param \Elevate\Themeoptions\Helper\General $ev_helper
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Mirasvit\Kb\Model\CategoryFactory $categoryFactory,
        \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollectionFactory,
        \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mirasvit\Kb\Api\ArticleRepositoryInterface $articleRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Elevate\Themeoptions\Helper\General $ev_helper
    ) {
        $this->registry                  = $registry;
        $this->categoryFactory           = $categoryFactory;
        $this->userCollectionFactory     = $userCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager              = $storeManager;
        $this->articleRepository         = $articleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filter = $filter;
        $this->filterGroup = $filterGroup;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->ev_helper = $ev_helper;

        parent::__construct($context);
    }


    /**
     * @param $articlesectionname
     *
     * @return mixed
     */
    public function getSectionUrl($articlesectionname) {
        return rtrim(str_replace(['----','---','--'],'-', (str_replace([',','(', ')', '£', '[', ']', ';', '&', '!', '?', ':', '\\', '/', '*', ' '], '-', strtolower($articlesectionname)))), '-');
    }

    public function toArticleOptionArray() {

        $filters = array(
            array(
                'field' => 'article_id',
                'value' => '',
                'condition_type' => 'notnull'
            )
        );

        $sortorder = array(
            'field' => 'article_id',
            'direction' => 'DESC'
        );


        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);
        $articles = $this->articleRepository->getList($searchCriteria);


        $output = array();

        foreach ($articles->getItems() as $article) {

            $innerarticle = $article->getAllData();

            $new_array = array(
                'value' => $innerarticle['article_id'],
                'label' => $innerarticle['article_id'].' - '.$innerarticle['name']
            );

            $output[] = $new_array;
        }

        return $output;

    }

    /**
     * @param bool $emptyOption
     * @return array
     */
    public function toAdminUserOptionArray($emptyOption = false)
    {
        $arr = $this->userCollectionFactory->create()->toArray();
        $result = [];
        foreach ($arr['items'] as $value) {
            $result[] = ['value' => $value['user_id'], 'label' => $value['firstname'] . ' ' . $value['lastname']];
        }

        if ($emptyOption) {
            array_unshift($result, ['value' => 0, 'label' => __('-- Please Select --')]);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAdminUserOptionArray()
    {
        $arr = $this->userCollectionFactory->create()->toArray();
        $result = [];
        foreach ($arr['items'] as $value) {
            $result[$value['user_id']] = $value['firstname'] . ' ' . $value['lastname'];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCategoriesOptionArray()
    {
        $collection = $this->categoryCollectionFactory->create()
            ->setOrder('path', 'asc');
        $result = [];

        /** @var \Mirasvit\Kb\Model\Category $category */
        foreach ($collection as $category) {
            $result[$category->getId()] = str_repeat('-', $category->getLevel()) . $category->getName();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getHomeUrl()
    {
        return $this->getRootCategory()->getUrl();
    }

    /**
     * @param int $storeId
     *
     * @return \Mirasvit\Kb\Model\Category
     */
    public function getRootCategory($storeId = null)
    {
        /** @var \Mirasvit\Kb\Model\Category $category */
        $category = $this->registry->registry('kb_current_category');
        if ($category) {
            $rootId = $category->getParentRootCategory();
            $category = $this->categoryFactory->create()->load($rootId);
        } else {
            $category = $this->getRootIdByStore($storeId);
        }
        $this->rootCategory = $category;

        return $this->rootCategory;
    }

    /**
     * @param int $storeId
     *
     * @return \Mirasvit\Kb\Model\Category
     */
    private function getRootIdByStore($storeId)
    {
        $rootId = 1;

        if (empty($storeId)) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', $rootId)
            ->addRootStoreIdFilter($storeId);

        if ($collection->count()) {
            /** @var \Mirasvit\Kb\Model\Category $root */
            $root = $collection->getFirstItem();
            $rootId = $root->getId();
        }

        return $this->categoryFactory->create()->load($rootId);
    }

    /**
     * @param array $params
     *
     * @return mixed|string
     */
    public function getPagerUrl($params)
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (count($params) > 0) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }

        return $url;
    }

    /**
     * @param \Mirasvit\Kb\Model\Article $article
     * @return void
     */
    public function setRating($article)
    {
        if ($rating = $article->getData('rating')) {
            if ($rating > 5) {
                $rating = 5;
            }
            $article->setVotesSum($article->getVotesNum() * $rating);
        }
    }

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     * @param string                                                                  $query
     * @return void
     */
    public function addSearchFilter($collection, $query)
    {
        $collection->getSearchInstance()->joinMatched($query, $collection, 'main_table.article_id');
    }
}
