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



namespace Mirasvit\Kb\Model\ResourceModel\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Option\ArrayInterface;

class Collection extends AbstractCollection implements ArrayInterface
{
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
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
        $this->entityFactory = $entityFactory;
        $this->logger = $logger;
        $this->fetchStrategy = $fetchStrategy;
        $this->eventManager = $eventManager;
        $this->connection = $connection;
        $this->resource = $resource;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Kb\Model\Tag', 'Mirasvit\Kb\Model\ResourceModel\Tag');
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('tag_id', 'name');
    }

    /**
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
    public function joinNotEmptyFields()
    {
        $select = $this->getSelect();
        $select->joinRight(
            ['article_tag' => $this->getTable('mst_kb_article_tag')],
            'main_table.tag_id = at_tag_id',
            ['ratio' => 'count(main_table.tag_id)']
        )
            ->group('tag_id');
        return $this;
    }

    /**
     * @param int $articleId
     * @return array
     */
    public function tagsUsedInArticles($articleId)
    {
        $tableName = $this->resourceConnection->getTableName('mst_kb_article_tag');
        $select = "SELECT * FROM " . $tableName . " WHERE at_article_id != " . $articleId;
        $row = $this->resourceConnection->getConnection()->fetchAll($select);
        return $row;
    }
}
