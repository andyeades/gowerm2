<?php

namespace Elevate\Microsite\Model\Indexer\Product;

use Elevate\Microsite\Model\Indexer\AbstractIndexer;

class ProductRuleIndexer extends AbstractIndexer
{
    /**
     * Override constructor. Indexer is changed
     *
     * @param IndexBuilder                              $productIndexBuilder
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \Elevate\Microsite\Model\Indexer\Product\IndexBuilder $productIndexBuilder,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        parent::__construct($productIndexBuilder, $eventManager);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteList($ids)
    {
        $this->indexBuilder->reindexByProductIds(array_unique($ids));
        $this->getCacheContext()->registerEntities(\Magento\Catalog\Model\Product::CACHE_TAG, $ids);
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteRow($id)
    {
        $this->indexBuilder->reindexByProductId($id);
    }
}
