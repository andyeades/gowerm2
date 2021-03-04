<?php
/**
 * Blackbird Data Model Sample Module
 *
 * NOTICE OF LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@bird.eu so we can send you a copy immediately.
 *
 * @category    Blackbird
 * @package     Blackbird_DataModelSample
 * @copyright   Copyright (c) 2018 Blackbird (https://black.bird.eu)
 * @author      Blackbird Team
 * @license     MIT
 * @support     help@bird.eu
 */
namespace Elevate\CartAssignments\Model\ResourceModel;


use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Elevate\CartAssignments\Api\Data\CartAssignmentsInterface;

/**
 * Class CartAssignments
 *
 * @package Elevate\CartAssignments\Model\ResourceModel
 */
class CartAssignments extends AbstractDb
{
    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    private $metadataPool;

    /**
     * @var \Magento\Framework\EntityManager\EntityManager
     */
    private $entityManager;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\EntityManager\MetadataPool $metadataPool
     * @param \Magento\Framework\EntityManager\EntityManager $entityManager
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        MetadataPool $metadataPool,
        EntityManager $entityManager,
        $connectionName = null
    ) {
        $this->metadataPool = $metadataPool;
        $this->entityManager = $entityManager;
        parent::__construct($context, $connectionName);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(CartAssignmentsInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
                             ->from(['cbs' => $this->getTable('elevate_cartassignments_store')], 'store_id')
                             ->join(
                                 ['cb' => $this->getMainTable()],
                                 'cbs.' . $linkField . ' = cb.' . $linkField,
                                 []
                             )
                             ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :cartassignments_id');

        return $connection->fetchCol($select, ['cartassignments_id' => (int)$id]);
    }
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupCategoryIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(CartAssignmentsInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
                             ->from(['cbs' => $this->getTable('elevate_cartassignments_categories')], 'category_id')
                             ->join(
                                 ['cb' => $this->getMainTable()],
                                 'cbs.' . $linkField . ' = cb.' . $linkField,
                                 []
                             )
                             ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :cartassignments_id');

        return $connection->fetchCol($select, ['cartassignments_id' => (int)$id]);
    }
    public function lookupCategoryBlacklistIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(CartAssignmentsInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
                             ->from(['cbs' => $this->getTable('elevate_cartassignments_categories_blacklist')], 'category_id')
                             ->join(
                                 ['cb' => $this->getMainTable()],
                                 'cbs.' . $linkField . ' = cb.' . $linkField,
                                 []
                             )
                             ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :cartassignments_id');

        return $connection->fetchCol($select, ['cartassignments_id' => (int)$id]);
    }
    public function lookupProductIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(CartAssignmentsInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
                             ->from(['cbs' => $this->getTable('elevate_cartassignments_products')], 'product_id')
                             ->join(
                                 ['cb' => $this->getMainTable()],
                                 'cbs.' . $linkField . ' = cb.' . $linkField,
                                 []
                             )
                             ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :cartassignments_id');

        return $connection->fetchCol($select, ['cartassignments_id' => (int)$id]);
    }
    public function lookupProductBlacklistIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(CartAssignmentsInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
                             ->from(['cbs' => $this->getTable('elevate_cartassignments_products_blacklist')], 'product_id')
                             ->join(
                                 ['cb' => $this->getMainTable()],
                                 'cbs.' . $linkField . ' = cb.' . $linkField,
                                 []
                             )
                             ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :cartassignments_id');

        return $connection->fetchCol($select, ['cartassignments_id' => (int)$id]);
    }
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('elevate_cartassignments_cartassignments', 'cartassignments_id');
    }

    public function save(AbstractModel $object) {
        return $this->entityManager->save($object);
    }

    public function load(
        \Magento\Framework\Model\AbstractModel $object,
        $value,
        $field = NULL
    ) {
        return $this->entityManager->load($object, $value);
    }

    public function delete(\Magento\Framework\Model\AbstractModel $object) {
        $this->entityManager->delete($object);
    }


    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return BlockInterface
     */
    public function setCartassignmentsId($cartassignmentsId)
    {
        return $this->setData(self::CARTASSIGNMENTS_ID, $cartassignmentsId);
    }
}