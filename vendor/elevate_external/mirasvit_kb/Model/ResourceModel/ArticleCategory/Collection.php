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



namespace Mirasvit\Kb\Model\ResourceModel\ArticleCategory;

use Mirasvit\Kb\Model\ArticleCategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection implements
    \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var string
     */
    protected $_idFieldName = 'article_category_id';//@codingStandardsIgnoreLine

    /**
     * @var \Mirasvit\Kb\Model\SearchFactory
     */
    protected $searchFactory;

    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\ArticleCategory\CollectionFactory
     */
    protected $articleCategoryCollectionFactory;

    /**
     * @var \Magento\Framework\Data\Collection\EntityFactoryInterface
     */
    protected $entityFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Data\Collection\Db\FetchStrategyInterface
     */
    protected $fetchStrategy;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface|string|null
     */
    protected $connection;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected $resource;

    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    protected $kbData;

    /**
     * @param \Mirasvit\Kb\Model\SearchFactory $searchFactory
     * @param CollectionFactory $articleCategoryCollectionFactory
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Mirasvit\Kb\Model\SearchFactory $searchFactory,
        CollectionFactory $articleCategoryCollectionFactory,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mirasvit\Kb\Helper\Data $kbData,
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->searchFactory = $searchFactory;
        $this->articleCategoryCollectionFactory = $articleCategoryCollectionFactory;
        $this->entityFactory = $entityFactory;
        $this->logger = $logger;
        $this->fetchStrategy = $fetchStrategy;
        $this->eventManager = $eventManager;
        $this->storeManager = $storeManager;
        $this->connection = $connection;
        $this->resource = $resource;
        $this->kbData = $kbData;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Kb\Model\ArticleCategory', 'Mirasvit\Kb\Model\ResourceModel\ArticleCategory');
    }
}
