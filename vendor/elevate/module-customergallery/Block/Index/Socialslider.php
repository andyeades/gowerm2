<?php

namespace Elevate\CustomerGallery\Block\Index;

use Elevate\CustomerGallery\Model\ItemsFactory;
use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Socialslider extends Template
{
    /**
     * @var ResourceConnection
     */
    protected $_itemsFactory;

    /**
     * @var Product
     */
    protected $product;

    /**
     * Constructor
     *
     * @param Context  $context
     * @param array $data
     * @param  ItemsFactory  $itemsFactory
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = [],
        ItemsFactory $itemsFactory
    ) {
        $this->helper = $helper;
        $this->_itemsFactory = $itemsFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get the account manager factory to load the collection in
     *
     * @return ResourceConnection|ItemsFactory
     */
    public function itemsFactory()
    {
        return $this->_itemsFactory;
    }

    public function getProduct()
    {
        return $this->helper->getProduct();
    }
}
