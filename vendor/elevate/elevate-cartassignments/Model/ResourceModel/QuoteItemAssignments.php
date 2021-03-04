<?php


namespace Elevate\CartAssignments\Model\ResourceModel;

/**
 * Class QuoteItemAssignments
 *
 * @package Elevate\CartAssignments\Model\ResourceModel
 */
class QuoteItemAssignments extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elevate_cartassignments_quoteitemassignments', 'quoteitemassignments_id');
    }
}

