<?php
namespace Elevate\CartAssignments\Model\ResourceModel\CartAssignments\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Elevate\CartAssignments\Api\Data\CartAssignmentsInterface;
use Elevate\CartAssignments\Model\ResourceModel\CartAssignments;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class SaveHandler
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var CartAssignments
     */
    protected $resourceBlock;

    /**
     * @param MetadataPool $metadataPool
     * @param Block $resourceBlock
     */
    public function __construct(
        MetadataPool $metadataPool,
        CartAssignments $resourceBlock
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceBlock = $resourceBlock;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {


        $entityMetadata = $this->metadataPool->getMetadata(CartAssignmentsInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceBlock->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getData('store_ids');


        $table = $this->resourceBlock->getTable('elevate_cartassignments_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }



        /*assigned_categories*/
        $oldCategories = $this->resourceBlock->lookupCategoryIds((int)$entity->getId());
        $newCategories = (array)$entity->getData('assigned_categories');



        $table = $this->resourceBlock->getTable('elevate_cartassignments_categories');
        $delete = array_diff($oldCategories, $newCategories);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'category_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }
        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'category_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }
        /*end assigned_categories*/










        return $entity;
    }
}
