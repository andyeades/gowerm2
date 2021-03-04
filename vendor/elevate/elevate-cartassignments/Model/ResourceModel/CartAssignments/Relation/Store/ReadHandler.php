<?php

namespace Elevate\CartAssignments\Model\ResourceModel\CartAssignments\Relation\Store;

use Elevate\CartAssignments\Model\ResourceModel\CartAssignments;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var CartAssignments
     */
    protected $resourceBlock;

    /**
     * @param Block $resourceBlock
     */
    public function __construct(
        CartAssignments $resourceBlock
    ) {
        $this->resourceBlock = $resourceBlock;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {

        if ($entity->getId()) {
            $stores = $this->resourceBlock->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
            $entity->setData('stores', $stores);


            $categories = $this->resourceBlock->lookupCategoryIds((int)$entity->getId());
            if(!array($categories)){
                $categories = [];
            }
            //$entity->setData('assigned_categories', $categories);
        }



        return $entity;
    }
}
