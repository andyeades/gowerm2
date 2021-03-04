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


namespace Mirasvit\Kb\Service;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Sitemap implements \Mirasvit\Kb\Api\Data\SitemapInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;
    /**
     * @var \Magento\Framework\Session\Generic
     */
    private $session;
    /**
     * @var \Magento\Framework\Session\SidResolverInterface
     */
    private $sidResolver;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory
     */
    private $articleCollectionFactory;
    /**
     * @var \Mirasvit\Kb\Model\Config
     */
    private $config;
    /**
     * @var \Mirasvit\Core\Api\UrlRewriteHelperInterface
     */
    private $urlRewrite;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    private $kbData;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @param \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Mirasvit\Kb\Model\Config $config
     * @param \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Framework\Session\Generic $session
     * @param \Magento\Framework\Session\SidResolverInterface $sidResolver
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Mirasvit\Kb\Model\Config $config,
        \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory,
        \Mirasvit\Kb\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $state,
        \Magento\Framework\Session\Generic $session,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->urlRewrite                = $urlRewrite;
        $this->kbData                    = $kbData;
        $this->config                    = $config;
        $this->articleCollectionFactory  = $articleCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager              = $storeManager;
        $this->state                     = $state;
        $this->session                   = $session;
        $this->sidResolver               = $sidResolver;
        $this->urlBuilder                = $urlBuilder;
        $this->objectManager             = $objectManager;
    }

    /**
     * @param int $storeId
     * @return string
     */
    public function getBaseRoute($storeId = 0)
    {
        return $this->config->getBaseUrl($storeId);
    }

    /**
     * @param int $storeId
     * @param int $parentId
     * @return array
     */
    public function getCategoryTree($storeId = 0, $parentId = 0)
    {
        $list = [];

        if ($parentId == null) {
            $parentId = $this->kbData->getRootCategory($storeId)->getId();
        }

        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('is_active', true)
            ->addFieldToFilter('parent_id', $parentId)
            ->addStoreIdFilter($storeId)
            ->setOrder('position', 'asc')
        ;

        foreach ($collection as $item) {
            $list[] = $item;
            if ($item->hasChildren()) {
                $children = $this->getCategoryTree($storeId, $item->getId());
                foreach ($children as $child) {
                    $list[] = $child;
                }
            }
        }

        return $list;
    }

    /**
     * @param int $storeId
     * @return \Magento\Framework\DataObject
     */
    public function getBlogItem($storeId = 0)
    {
        $this->urlRewrite->registerBasePath('KBASE', $this->config->getBaseUrl($storeId));
        $this->urlRewrite->registerPath(
            'KBASE',
            'CATEGORY_ROOT',
            '',
            'kbase_category_view',
            ['id' => $this->kbData->getRootCategory($storeId)->getId()]
        );
        $baseUrlCollection = new \Magento\Framework\DataObject(
            [
                'url' =>  $this->prepareSitemapUrl($this->kbData->getRootCategory($storeId)->getUrl()),
            ]
        );

        $sitemapItem = new \Magento\Framework\DataObject(
            [
                'changefreq' => self::CHANGEFREQ,
                'priority' => self::PRIORITY,
                'collection' => ['Homepage' => $baseUrlCollection],
            ]
        );

        return $sitemapItem;
    }

    /**
     * @param int $storeId
     * @return \Magento\Framework\DataObject|bool
     */
    public function getCategoryItems($storeId = 0)
    {
        $this->urlRewrite->registerBasePath('KBASE', $this->config->getBaseUrl($storeId));
        $categoryTree = $this->getCategoryTree($storeId);
        if (!$categoryTree) {
            return false;
        }

        foreach ($categoryTree as $category) {
            $categoryCollection[] = new \Magento\Framework\DataObject(
                [
                    'name' => $category->getName(),
                    'url' => $this->prepareSitemapUrl($category->getUrl()),
                ]
            );
        }

        $sitemapItem = new \Magento\Framework\DataObject(
            [
                'changefreq' => self::CHANGEFREQ,
                'priority' => self::PRIORITY,
                'collection' => $categoryCollection,
            ]
        );

        return $sitemapItem;
    }

    /**
     * @param int $storeId
     * @return \Magento\Framework\DataObject|bool
     */
    public function getPostItems($storeId)
    {
        $this->urlRewrite->registerBasePath('KBASE', $this->config->getBaseUrl($storeId));
        $postCollectionFactory = $this->articleCollectionFactory->create()
            ->addStoreIdFilter($storeId)
            ->addVisibilityFilter();

        if ($postCollectionFactory->getSize() <= 0) {
            return false;
        }

        $postCollection = null;
        /** @var \Mirasvit\Kb\Model\Article $post */
        foreach ($postCollectionFactory as $post) {
            $postCollection[] = new \Magento\Framework\DataObject(
                [
                    'name' => $post->getName(),
                    'url' => $this->prepareSitemapUrl($post->getUrl()),
                ]
            );
        }

        $sitemapItem = new \Magento\Framework\DataObject(
            [
                'changefreq' => self::CHANGEFREQ,
                'priority' => self::PRIORITY,
                'collection' => $postCollection,
            ]
        );

        return $sitemapItem;
    }

    /**
     * @param string $url
     * @return string
     */
    private function prepareSitemapUrl($url)
    {
        $baseUrl = $this->urlBuilder->getBaseUrl();

        $sessionKey = $this->sidResolver->getSessionIdQueryParam($this->session);
        if (
            $this->storeManager->getStore()->getConfig(\Magento\Store\Model\Store::XML_PATH_STORE_IN_URL) &&
            $this->state->getAreaCode() != \Magento\Framework\App\Area::AREA_CRONTAB
        ) {
            if (parse_url($baseUrl,PHP_URL_PATH) != "/{$this->storeManager->getStore()->getCode()}/") {
                $baseUrl .= $this->storeManager->getStore()->getCode() . '/';
            }
        }
        $url = preg_replace('/\??'.$sessionKey.'=[^&]*/', '', $url);
        $url = str_replace($baseUrl, '', $url);

        return $url;
    }
}
