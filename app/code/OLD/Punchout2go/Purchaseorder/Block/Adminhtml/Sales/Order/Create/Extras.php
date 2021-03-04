<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Punchout2go\Purchaseorder\Block\Adminhtml\Sales\Order\Create;

//class Extras extends \Magento\Backend\Block\Template
//class Extras extends \Magento\Sales\Block\Adminhtml\Items\Column\DefaultColumn
class Extras extends \Magento\Framework\View\Element\AbstractBlock
{

    /**
     * Extras constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Framework\View\Element\Context $context, $data)
    {
        $data = (array) $data;
        parent::__construct($context, $data);
    }

    public function test()
    {
        // @TODO BP: this function should be able to call $_item->getAdditionalData() but it can't
        $_item = $this->getItem();
        return 'test';
    }
}
