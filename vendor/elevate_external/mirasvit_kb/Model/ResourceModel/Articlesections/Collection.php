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



namespace Mirasvit\Kb\Model\ResourceModel\Articlesections;

use Mirasvit\Kb\Model\Articlesections;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection implements
    \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var string
     */
    protected $_idFieldName = 'articlesection_id';//@codingStandardsIgnoreLine

    /**
     * @var \Mirasvit\Kb\Model\SearchFactory
     */
    protected $searchFactory;

    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Articlesections\CollectionFactory
     */
    protected $articlesectionsCollectionFactory;

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
     * @param CollectionFactory $articlesectionsCollectionFactory
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
        CollectionFactory $articlesectionsCollectionFactory,
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
        $this->articlesectionsCollectionFactory = $articlesectionsCollectionFactory;
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
        $this->_init('Mirasvit\Kb\Model\Articlesections', 'Mirasvit\Kb\Model\ResourceModel\Articlesections');
    }

    /**
     * @todo ??
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('articlesection_id', 'name');
    }

    /**
     * @todo ??
     * @return array
     */
    public function getOptionArray()
    {
        $arr = [];
        foreach ($this as $item) {
            $arr[$item->getId()] = $item->getName();
        }

        return $arr;
    }

    /**
     * @return $this
     */
    public function addVisibilityFilter()
    {
        $this->getSelect()
            ->where("main_table.asecsub_is_active = 1");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
    }

    /**
     * @return \Mirasvit\Kb\Model\Search
     */
    public function getSearchInstance()
    {
        $collection = $this->articleCollectionFactory->create();

        $collection = $this->addTagSearch($collection);

        $search = $this->searchFactory->create();
        $search->setSearchableCollection($collection);
        $search->setSearchableAttributes([
            'main_table.articlesection_id'       => ['priority' => 0, 'selectStatement' => 'main_table.articlesection_id'],
            'main_table.name'             => ['priority' => 100, 'selectStatement' => 'main_table.name'],
            'main_table.text'             => ['priority' => 50, 'selectStatement' => 'main_table.text'],
            'main_table.meta_title'       => ['priority' => 80, 'selectStatement' => 'main_table.meta_title'],
            'main_table.meta_keywords'    => ['priority' => 80, 'selectStatement' => 'main_table.meta_keywords'],
            'main_table.meta_description' => ['priority' => 60, 'selectStatement' => 'main_table.meta_description'],
            'tag_name'                    => ['priority' => 55, 'selectStatement' => 'GROUP_CONCAT(tag.name)'],
        ]);
        $search->setPrimaryKey('articlesection_id');

        return $search;
    }

    /**
     * @param \Mirasvit\Kb\Model\ResourceModel\Article\Collection $collection
     * @return \Mirasvit\Kb\Model\ResourceModel\Article\Collection
     */
    private function addTagSearch($collection)
    {
        $tagTable        = $collection->getTable('mst_kb_tag');
        $tagAlias        = 'tag';
        $articleTagTable = $collection->getTable('mst_kb_article_tag');
        $articleTagAlias = 'article_tag';

        $collection->getSelect()
            ->joinLeft(
                [$articleTagAlias => $articleTagTable],
                $articleTagAlias.'.at_articlesection_id = main_table.articlesection_id',
                ['']
            )->joinLeft(
                [$tagAlias => $tagTable],
                $tagAlias.'.tag_id = '.$articleTagAlias.'.at_tag_id',
                ['tag_name' => 'GROUP_CONCAT('.$tagAlias.'.name)']
            )->group('main_table.articlesection_id');

        return $collection;
    }
}
