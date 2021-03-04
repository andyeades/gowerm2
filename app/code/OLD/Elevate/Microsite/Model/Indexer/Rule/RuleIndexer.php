<?php

namespace Elevate\Microsite\Model\Indexer\Rule;

use Elevate\Microsite\Model\Indexer\AbstractIndexer;

class RuleIndexer extends AbstractIndexer
{
    /**
     * @var \Elevate\Microsite\Model\Indexer\Customer\IndexBuilder
     */
    protected $customerIndexBuilder;

    public function __construct(
        \Elevate\Microsite\Model\Indexer\Product\IndexBuilder $productIndexBuilder,
        \Elevate\Microsite\Model\Indexer\Customer\IndexBuilder $customerIndexBuilder,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        parent::__construct($productIndexBuilder, $eventManager);
        $this->customerIndexBuilder = $customerIndexBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteList($ids)
    {
        $this->indexBuilder->reindexByIds($ids);
        $this->customerIndexBuilder->reindexByIds($ids);
        $this->getCacheContext()->registerTags($this->getIdentities());
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecuteRow($id)
    {
        $this->indexBuilder->reindexById($id);
        $this->customerIndexBuilder->reindexById($id);
    }

    /**
     * {@inheritdoc}
     */
    public function executeFull()
    {
        $this->customerIndexBuilder->reindexFull();
        parent::executeFull();
    }
}
