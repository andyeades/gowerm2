<?php


namespace Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments;

/**
 * Class Collection
 *
 * @package Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'quoteitemassignments_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Elevate\CartAssignments\Model\QuoteItemAssignments::class,
            \Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments::class
        );
    }
}

