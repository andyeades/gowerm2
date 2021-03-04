<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Punchout2go\Purchaseorder\Model\Order\Request;

use Magento\Framework\DataObject;
//use Symfony\Component\Config\Definition\Exception\Exception;

class Json
    extends \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest
{

    protected $_items;
    protected $_json;

    /**
     * @return null|int
     */
    public function getCustomerId()
    {
        $helper = $this->_helper;
        $customerId = parent::getCustomerId();
        if (null != $customerId) {
            return $customerId;
        }

        $customerEmail = $this->_json->details->contact->email;
        $storeCode = $this->getStoreCode();

        /** @var \Magento\Store\Api\StoreRepositoryInterface $storeRepository */
        $storeRepository = $this->_storeRepository;

        /** @var \Magento\Store\Api\Data\StoreInterface $store */
        $store = $storeRepository->getActiveStoreByCode($storeCode);
        $websiteId = $store->getWebsiteId();

        /** @var \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository */
        $customerRepository = $this->_customerRepository;

        $customerId = $customerRepository->get($customerEmail, $websiteId)->getId();

        return $customerId;
    }

    /**
     * @return null|int
     */
    public function getQuoteId()
    {
        $quoteId = parent::getQuoteId();
        if (null != $quoteId) {
            return $quoteId;
        }
        /** @var \Magento\Framework\DataObject $item */
        $items = $this->getItems()->getData();
        $quoteId = false;
        foreach ($items as $item) {
            $itemQuoteId = explode('/', $item->getInternalReferenceId())[0];
            if ($quoteId != false && $quoteId != $itemQuoteId) {
                $quoteId = false;
                parent::setQuoteId($quoteId);
                return $quoteId;
            }
            if ($quoteId == false) {
                $quoteId = $itemQuoteId;
            }
        }

        parent::setQuoteId($quoteId);
        return $quoteId;
    }

    /**
     * @return string
     */
    public function getStoreCode()
    {
        $storeCode = parent::getStoreCode();
        if (null != $storeCode) {
            return $storeCode;
        }

        $storeCode = $this->_json->store_code;
        $this->_storeCode = $storeCode;

        return $storeCode;
    }

    /**
     * @return null|int
     */
    public function getStoreId()
    {
        $storeId = parent::getStoreId();
        if (null != $storeId) {
            return $storeId;
        }

        $storeCode = $this->_json->store_code;

        /** @var \Magento\Store\Api\StoreRepositoryInterface $storeRepository */
        $storeRepository = $this->_storeRepository;

        $storeId = $storeRepository->getActiveStoreByCode($storeCode)->getId();
        $this->_storeId = $storeId;

        return $storeId;
    }

    /**
     * @param string $key
     * @return array|mixed|SimpleXMLElement
     */
    public function getData($key = '', $index = null)
    {
        $result = parent::getData($key, $index);
        if (empty($result)) {
            return $this->getValue($key);
        }
        return $result;
    }

    /**
     * @return \Punchout2go\Purchaseorder\Model\Order\Request\Items
     */
    public function getItems()
    {
        if ($this->_items == null) {
            $jsonItems = $this->getJson()->items;
            $items = array();
            foreach ($jsonItems as $jsonItem) {
                $item = array();
                $item['line_number'] = $jsonItem->line_number;
                $item['quantity'] = $jsonItem->quantity;
                $item['part_id'] = $jsonItem->supplier_id;
                $item['internal_reference_id'] = $jsonItem->supplier_aux_id;
                $item['unit_price'] = $jsonItem->unitprice;
                $item['description'] = $jsonItem->description;
                $items[] = new \Magento\Framework\DataObject($item);
            }
            $this->setItems($items);
        }
        return $this->_items;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getJson()
    {
        if ($this->_json == null) {
            try {
                $this->_json = json_decode($this->getDocument());
            } catch (\Exception $e) {
                //throw $e;
                $this->_json = false;
                //throw new Exception('Cxml document is invalid',100);
            }
        }
        return $this->_json;
    }

    /**
     * @return bool
     */
    public function isValid() {
        if (is_array($this->getJson())) {
            return true;
        }
        return false;
    }

    /**
     * this is only used for loading from the stash
     */
    /*public function loadOwnership ()
    {
        // @var $helper Vbw_Procurement_Helper_Data
        $helper = $this->_helper;
        // @var $items array
        $items = $this->getJson()->items;
        foreach ($items AS $item) {
            $auxid = (string) $item->supplier_aux_id;
            $stash = $helper->getItemStashFromLine($auxid);
            if (!empty($stash)) {
                if ($this->_quote_id === null) {
                    // first match up just set them.
                    $this->_quote_id = $stash->getQuoteId();
                    $this->_store_id = $stash->getStoreId();
                    $this->_customer_id = $stash->getCustomerId();
                } else {
                    if ($this->_quote_id != $stash->getQuoteId()) {
                        $this->_quote_id = false; // if they don't match then don't use order level detail
                    }
                    if ($this->_store_id != $stash->getStoreId()) {
                        throw new Exception('You cannot mix line items from different stores.');
                    }
                    if ($this->_customer_id != $stash->getCustomerId()) {
                        throw new Exception('You cannot mix line items from different customer sessions.');
                    }
                }
            } else {
                $helper->debug('No stash found for : '. $auxid);
            }
        }
        if ($this->_store_id != null) {
            return true;
        }
        return false;
    }*/

    /**
     * @param \Punchout2go\Purchaseorder\Model\Order\Request\Items $items
     */
    public function setItems($items)
    {
        if (is_array($items)) {
            /** @var $set \Punchout2go\Purchaseorder\Model\Order\Request\Items */
            $set = new \Punchout2go\Purchaseorder\Model\Order\Request\Items($this->_dataObject);
            $set->setClassName('\Punchout2go\Purchaseorder\Model\Order\Request\Items\Item\Json');
            $set->setData($items);
            $this->_items = $set;
        } else {
            $this->_items = $items;
        }
    }

    /**
     * @param array $item
     * @return array
     */
    public function readKeyItemData($item)
    {
        $return = new \Magento\Framework\DataObject();
        $return->setLineNumber($item->getLineNumber());
        $return->setPartId($item->getPartId());
        $return->setInternalReferenceId($item->getInternalReferenceId());
        $return->setQuantity($item->getQuantity());
        $return->setUnitPrice($item->getUnitPrice());

        return $return;
    }

    /**
     * @param $xpath
     * @param bool $first
     * @return array|SimpleXMLElement
     */
    public function getRequestNode($xpath,$first = true)
    {
        $nodes = $this->getJson()->Request->xpath($xpath);
        if (!empty($nodes) && $first) {
            return $nodes[0];
        }
        return $nodes;
    }

    /**
     * @param \stdClass $address
     * @return array
     */
    public function parseAddress($address)
    {
        $helper = $this->_helper;
        $address = get_object_vars($address);

        $extAddressId = $address['address_id'];

        $nameData = $helper->getNameData($address['deliverto']);

        $countryId = $address['country_code'];
        $regionId = $helper->getRegionIdFromCode($address['state'], $countryId);
        $region = '';
        if (empty($regionId)) {
            $region = $address['state'];
            $regionId = '';
        }

        // make sure that the address array has all required fields,
        // even if they only contain a blank string
        $fields = ['address_name', 'street', 'city', 'postalcode', 'email', 'telephone'];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $address)) {
                $address[$field] = null;
            }
        }

        $addressData = array (
            'ext_address_id' => $extAddressId,
            'firstname' => $nameData[0],
            'lastname' => $nameData[1],
            'company' => $address['address_name'],
            'street' => [$address['street']],
            'city' => $address['city'],
            'postcode' => $address['postalcode'],
            'region' => $region,
            'region_id' => $regionId,
            'country_id' => $countryId,
            "email" => $address['email'],
            "telephone" => $address['telephone'],
        );

        return $addressData;
    }

    /**
     * @return string
     */
    public function getShippingCode()
    {
        if ('' == $this->_shippingCode) {
            $details = $this->_json->details;
            if (isset($details->shipping_code)) {
                $this->_shippingCode = $details->shipping_code;
            }
        }
        return $this->_shippingCode;
    }

    /**
     * @return string
     */
    public function getShippingPrice()
    {
        if ('' == $this->_shippingPrice) {
            $details = $this->_json->details;
            if (isset($details->shipping)) {
                $this->_shippingPrice = $details->shipping;
            }
        }
        return $this->_shippingPrice;
    }

    /**
     * @return string
     */
    public function getTax()
    {
        if ('' == $this->_tax) {
            $this->_tax = $this->_json->details->tax;
        }
        return $this->_tax;
    }

    /**
     * @return array
     */
    public function getShipToAddress()
    {
        $address = $this->_json->details->ship_to;
        $addressData = $this->parseAddress($address);
        return $addressData;
    }

    /**
     * @return array
     */
    public function getBillToAddress()
    {
        $address = $this->_json->details->bill_to;
        $addressData = $this->parseAddress($address);
        return $addressData;
    }

    /**
     * @return string
     */
    public function getPoNumber()
    {
        $poNumber = $this->_poNumber;

        if (null === $this->_poNumber) {
            $poNumber = $this->_json->header->po_order_id;
        }

        if ('' == $poNumber) {
            $poNumber = ($this->_json->header->order_request_id)
                ? "(" . $this->_json->header->order_request_id . ")"
                : $this->_json->header->order_request_id;
        }

        if ('' == $poNumber) {
            throw new \Exception('No PO number or order request ID specified in the request');
        }

        $this->_poNumber = $poNumber;

        return parent::getPoNumber();
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        $requestId = $this->_requestId;

        if (null === $this->_requestId) {
            $requestId = $this->_json->header->order_request_id;
        }

        if ('' == $requestId) {
            throw new \Exception('No order request ID specified in the request');
        }

        $this->_requestId = $requestId;

        return parent::getRequestId();
    }

}