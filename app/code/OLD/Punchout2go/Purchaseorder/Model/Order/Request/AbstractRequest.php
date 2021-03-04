<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Punchout2go\Purchaseorder\Model\Order\Request;

use Magento\Framework\DataObject;
//use Symfony\Component\Config\Definition\Exception\Exception;

abstract class AbstractRequest
    extends \Magento\Framework\Model\AbstractModel
{
    protected $_document;
    protected $_customerId;
    protected $_shippingCode;
    protected $_shippingPrice;
    protected $_tax;
    protected $_storeId;
    protected $_storeCode;
    protected $_quoteId;
    protected $_helper;
    protected $_itemsFactory;
    protected $_dataObject;
    protected $_poNumber;
    protected $_requestId;

    /**
     * AbstractRequest constructor.
     * @param \Punchout2go\Purchaseorder\Model\Order\Request\ItemsFactory $itemsFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\DataObject $dataObject
     * @param \Magento\Store\Model\StoreRepository $storeRepository
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     */
    public function __construct(
        ItemsFactory $itemsFactory,
        \Magento\Framework\DataObject $dataObject,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Punchout2go\Purchaseorder\Helper\Data $helper
    ) {
        $this->_itemsFactory = $itemsFactory;
        $this->_dataObject = $dataObject;
        $this->_customerRepository = $customerRepository;
        $this->_storeRepository = $storeRepository;
        $this->_helper = $helper;
    }

    /**
     * @param $document
     */
    public function setDocument($document)
    {
        $this->_document = $document;
    }

    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->_document;
    }

    abstract public function isValid();

    abstract public function getShippingCode();

    abstract public function getShippingPrice();

    abstract public function getTax();

    abstract public function getShipToAddress();

    abstract public function getBillToAddress();

    /**
     * @return \Punchout2go\Purchaseorder\Model\Order\Request\Items
     */
    abstract public function getItems();

    public function setCustomerId($customerId)
    {
        $this->_customerId = $customerId;
    }

    public function getCustomerId()
    {
        return $this->_customerId;
    }

    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
    }

    public function getStoreId()
    {
        return $this->_storeId;
    }

    public function setStoreCode($storeCode)
    {
        $this->_storeCode = $storeCode;
    }

    public function getStoreCode()
    {
        return $this->_storeCode;
    }

    public function setQuoteId($quote_id)
    {
        $this->_quoteId = $quote_id;
    }

    public function getQuoteId()
    {
        return $this->_quoteId;
    }

    public function setPoNumber($poNumber)
    {
        $this->_poNumber = $poNumber;
    }

    public function getPoNumber()
    {
        return $this->_poNumber;
    }

    public function setRequestId($requestId)
    {
        $this->_requestId = $requestId;
    }

    public function getRequestId()
    {
        return $this->_requestId;
    }

}