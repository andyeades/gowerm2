<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Punchout2go\Purchaseorder\Model\Order\Request;

//use Symfony\Component\Config\Definition\Exception\Exception;

class Cxml
    extends \Punchout2go\Purchaseorder\Model\Order\Request\AbstractRequest
{

    protected $_items;
    protected $_cxml;

    /**
     * @return null|int
     */
    public function getCustomerId()
    {
        $customerId = parent::getCustomerId();
        if (null != $customerId) {
            return $customerId;
        }

        $customerEmail = $this->_cxml->Request->OrderRequest->OrderRequestHeader->Contact->Email;

        /** @var \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository */
        $customerRepository = $this->_customerRepository;

        $customerId = $customerRepository->get($customerEmail)->getId();

        return $customerId;
    }

    /**
     * TODO: must include a store code attribute in the cXML
     * @return null|int
     */
    public function getStoreId()
    {
        $storeId = parent::getStoreId();
        if (null != $storeId) {
            return $storeId;
        }

        $storeCode = $this->_cxml->Request->OrderRequest->OrderRequestHeader->Extrinsic->attributes()->store_code;

        /** @var \Magento\Store\Api\StoreRepositoryInterface $storeRepository */
        $storeRepository = $this->_storeRepository;

        $storeId = $storeRepository->getActiveStoreByCode($storeCode)->getId();

        return $storeId;
    }

    /**
     * @return string
     */
    public function getShippingCode()
    {
        if ('' == $this->_shippingCode) {
            $this->_shippingCode = (string) $this->_cxml->Request->OrderRequest->OrderRequestHeader->Extrinsic->attributes()->shipping_code;
        }
        return $this->_shippingCode;
    }

    /**
     * @return string
     */
    public function getShippingPrice()
    {
        if ('' == $this->_shippingPrice) {
            $this->_shippingCode = (float) $this->_cxml->Request->OrderRequest->OrderRequestHeader->Shipping->Money;
        }
        return $this->_shippingCode;
    }

    /**
     * @return string
     */
    public function getTax()
    {
        if ('' == $this->_tax) {
            $this->_tax = (float) $this->_cxml->Request->OrderRequest->OrderRequestHeader->Tax->Money;
        }
        return $this->_tax;
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
            $items = $this->getXml()->items;
            $this->setItems($items);
        }
        return $this->_items;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getXml()
    {
        if ($this->_cxml == null) {
            try {
                $this->_cxml = new \SimpleXMLElement($this->getDocument());
            } catch (\Exception $e) {
                //throw $e;
                $this->_cxml = false;
                //throw new Exception('Cxml document is invalid',100);
            }
        }
        return $this->_cxml;
    }

    /**
     * @return null|void
     */
    public function getQuoteId()
    {
        /*if (null === $this->_quoteId) {
            $this->loadOwnership();
        }*/
        return $this->_quoteId;
    }

    /**
     * @return bool
     */
    public function isValid() {
        if (is_array($this->getXml())) {
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
        die("implement setItems() properly");
        if (is_array($items)) {
            /** @var $set \Punchout2go\Purchaseorder\Model\Order\Items */
            $set = Mage::getModel('vbw_procurement/order_request_items');
            $set->setClassName('vbw_procurement/order_request_items_item_cxml');
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
        $return = array();
        $return['line_number'] = (string) $item['line_number'];
        $return['part_id'] = $item['supplier_id'];
        $return['internal_reference_id'] = (string) $item['supplier_aux_id'];
        $return['quantity'] = (string) $item['quantity'];
        $return['unit_price'] = (float) $item['unitprice'];

        return $return;
    }

    /**
     * @param $xpath
     * @param bool $first
     * @return array|SimpleXMLElement
     */
    public function getRequestNode ($xpath,$first = true)
    {
        $nodes = $this->getXml()->Request->xpath($xpath);
        if (!empty($nodes) && $first) {
            return $nodes[0];
        }
        return $nodes;
    }

    /**
     * @param \SimpleXMLElement $addressNode
     * @return array
     */
    public function parseAddress($address)
    {
        $helper = $this->_helper;

        $extAddressId = $address->attributes()->addressID;

        $nameData = $helper->getNameData($address->PostalAddress->DeliverTo);

        $countryId = $address->PostalAddress->Country->attributes()->isoCountryCode;
        $regionId = $helper->getRegionIdFromCode($address->PostalAddress->State, $countryId);
        $region = '';
        if (empty($regionId)) {
            $region = $address->PostalAddress->State;
            $regionId = '';
        }

        $addressData = array (
            'ext_address_id' => $extAddressId,
            'firstname' => $nameData[0],
            'lastname' => $nameData[1],
            'company' => $address->Name,
            'street' => [$address->PostalAddress->Street],
            'city' => $address->PostalAddress->City,
            'postcode' => $address->PostalAddress->PostalCode,
            'region' => $region,
            'region_id' => $regionId,
            'country_id' => $countryId,
            "email" => $address->Email,
            "telephone" => $address->Phone->TelephoneNumber->CountryCode .
                           $address->Phone->TelephoneNumber->AreaOrCityCode .
                           $address->Phone->TelephoneNumber->Number
        );

        return $addressData;
    }

    /**
     * @return array
     */
    public function getShipToAddress()
    {
        $address = $this->_cxml->Request->OrderRequest->OrderRequestHeader->ShipTo->Address;
        $addressData = $this->parseAddress($address);
        return $addressData;
    }

    /**
     * @return array
     */
    public function getBillToAddress()
    {
        $address = $this->_cxml->Request->OrderRequest->OrderRequestHeader->BillTo->Address;
        $addressData = $this->parseAddress($address);
        return $addressData;
    }

}