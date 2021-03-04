<?php

namespace Elevate\Microsite\Model\Indexer;

abstract class AbstractIndexer extends \Magento\CatalogRule\Model\Indexer\AbstractIndexer
{
    /**
     * @param IndexBuilder $indexBuilder
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \Elevate\Microsite\Model\Indexer\AbstractIndexBuilder $indexBuilder,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->indexBuilder = $indexBuilder;
        $this->_eventManager = $eventManager;
    }
}
