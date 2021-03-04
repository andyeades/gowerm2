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


namespace Mirasvit\Kb\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    /**
     * @var \Mirasvit\Kb\Api\Data\SitemapInterface
     */
    private $sitemapHelper;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var \Mirasvit\Core\Api\UrlRewriteHelperInterface
     */
    private $urlRewrite;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @param \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite
     * @param \Mirasvit\Kb\Api\Data\SitemapInterface $sitemapHelper
     * @param Config $config
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Sitemap\Helper\Data $sitemapData
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory $categoryFactory
     * @param \Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory $productFactory
     * @param \Magento\Sitemap\Model\ResourceModel\Cms\PageFactory $cmsFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $modelDate
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite,
        \Mirasvit\Kb\Api\Data\SitemapInterface $sitemapHelper,
        \Mirasvit\Kb\Model\Config $config,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Magento\Sitemap\Helper\Data $sitemapData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory $categoryFactory,
        \Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory $productFactory,
        \Magento\Sitemap\Model\ResourceModel\Cms\PageFactory $cmsFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $modelDate,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->sitemapHelper = $sitemapHelper;
        $this->urlRewrite    = $urlRewrite;
        $this->config        = $config;

        parent::__construct($context,
            $registry,
            $escaper,
            $sitemapData,
            $filesystem,
            $categoryFactory,
            $productFactory,
            $cmsFactory,
            $modelDate,
            $storeManager,
            $request,
            $dateTime,
            $resource,
            $resourceCollection,
            $data
        );

        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function _initSitemapItems()
    {
        $this->urlRewrite->registerBasePath('KBASE', $this->config->getBaseUrl($this->getStoreId()));
        $this->addSitemapItem($this->sitemapHelper->getBlogItem($this->getStoreId()));
        if ($categoryItems = $this->sitemapHelper->getCategoryItems($this->getStoreId())) {
            $this->addSitemapItem($categoryItems);
        }
        if ($postItems = $this->sitemapHelper->getPostItems($this->getStoreId())) {
            $this->addSitemapItem($postItems);
        }
        parent::_initSitemapItems();
    }
}