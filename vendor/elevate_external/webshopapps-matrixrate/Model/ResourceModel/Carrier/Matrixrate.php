<?php
/**
 * WebShopApps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * WebShopApps MatrixRate
 *
 * @category WebShopApps
 * @package WebShopApps_MatrixRate
 * @copyright Copyright (c) 2014 Zowta LLC (http://www.WebShopApps.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @author WebShopApps Team sales@webshopapps.com
 *
 */
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace WebShopApps\MatrixRate\Model\ResourceModel\Carrier;

use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DirectoryList;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Matrixrate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Import table rates website ID
     *
     * @var int
     */
    protected $importWebsiteId = 0;
    /**
     * Errors in import process
     *
     * @var array
     */
    protected $importHeaders = [];
    /**
     * Errors in import process
     *
     * @var array
     */
    protected $importErrors = [];

    /**
     * Count of imported table rates
     *
     * @var int
     */
    protected $importedRows = 0;

    /**
     * Array of unique table rate keys to protect from duplicates
     *
     * @var array
     */
    protected $importUniqueHash = [];

    /**
     * Array of countries keyed by iso2 code
     *
     * @var array
     */
    protected $importIso2Countries;

    /**
     * Array of countries keyed by iso3 code
     *
     * @var array
     */
    protected $importIso3Countries;

    /**
     * Associative array of countries and regions
     * [country_id][region_code] = region_id
     *
     * @var array
     */
    protected $importRegions;

    /**
     * Import Table Rate condition name
     *
     * @var string
     */
    protected $importConditionName;

    /**
     * Array of condition full names
     *
     * @var array
     */
    protected $conditionFullNames = [];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $coreConfig;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \WebShopApps\MatrixRate\Model\Carrier\Matrixrate
     */
    protected $carrierMatrixrate;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $countryCollectionFactory;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\CollectionFactory
     */
    protected $regionCollectionFactory;

    /**
     *   * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    private $readFactory;
    /**
     *   * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $coreConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \WebShopApps\MatrixRate\Model\Carrier\Matrixrate $carrierMatrixrate
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $coreConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \WebShopApps\MatrixRate\Model\Carrier\Matrixrate $carrierMatrixrate,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\Filesystem $filesystem,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->coreConfig = $coreConfig;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->carrierMatrixrate = $carrierMatrixrate;
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->readFactory = $readFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * Define main table and id field name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('webshopapps_matrixrate', 'pk');
    }

    /**
     * Return table rate array or false by rate request
     *
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     * @param bool                                           $zipRangeSet
     * @param string                                           $shipping_code
     *
     * @return array|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Db_Select_Exception
     */
    public function getRate(\Magento\Quote\Model\Quote\Address\RateRequest $request, $zipRangeSet = false, $shipping_code = false)
    {





        //do the customer based pricing first if we have a match

            //add where customer_
//run through each setting


        //$output = $this->getShippingRatesFromDb($request, $zipRangeSet, $shipping_code);



    $output = $this->getShippingRatesFromDb($request, $zipRangeSet, $shipping_code);





//print_r($shippingData);
        return $output;
    }





function getShippingRatesFromDb($request, $zipRangeSet, $shipping_code){

    // $e = new \Exception; var_dump($e->getTraceAsString());
    $customer_shipping_group_value = $shipping_code;
    //this is where the main work gets done
    $adapter = $this->getConnection();
    $shippingData=[];

    if(empty($customer_shippingetConnectiong_group_value)){$customer_shipping_group_value = "'','*'";}
    //lets figure out what data we need

    $postcode = trim($request->getDestPostcode()); //SHQ18-1978 e.g * or NP205AA
    $country_id = $request->getDestCountryId(); //e.g GB
    $region_id = $request->getDestRegionId();
    $city = $request->getDestCity();
    $order_weight =  $request->getPackageWeight();
    $order_total =  $request->getPackageValue();

    //sku based pricing

    //get this attribute from nav
    //$customer_shipping_group =  $request->getCustomerShippingGroup(); //BESPOKE2 //FLAT500


    $om = \Magento\Framework\App\ObjectManager::getInstance();
    $customerSession = $om->get('Magento\Customer\Model\Session');
    //$customerData = $customerSession->getCustomer()->getData(); //get all data of customerData
    $customer_shipping_group = $customerSession->getCustomer()->getData('nav_shipping_method_code');//get id of customer


   // $customer_shipping_group = 'STANDARD';
   // ///$customer_shipping_group = '';
    //to implement
    //customer group id
  //  $customer_shipping_group = 'STANDARD';
    //default shipping group
    if($customer_shipping_group == ''){

       // $customer_shipping_group = 'FLAT500';
    }

    if($country_id != 'GB'){
        $customer_shipping_group = '';

    }

    if($city == ''){
        $city = "*";

    }
    if($customer_shipping_group == ''){
        $customer_shipping_group = "";

    }
    //echo "Postcode:$postcode<br>";
    // echo "oountry id:$country_id<br>";
    // echo "region id:$region_id<br>";
    // echo "city:$city<br>";

    $website_id = $request->getWebsiteId();
    $condition_mr_name = $request->getConditionMRName();



    /*American range use */
    if ($zipRangeSet && is_numeric($postcode)) {
        #  Want to search for postcodes within a range. SHQ18-98 Can't use bind. Will convert int to string
        $zipSearchString = ' AND ' .(int)$postcode. ' BETWEEN dest_zip AND dest_zip_to ';
    } else {
        $zipSearchString = " AND :postcode LIKE dest_zip ";
    }


    for ($j=0; $j<8; $j++) {



        $select = $adapter->select()->from(
            $this->getMainTable()
        )->where(
            "website_id = :website_id AND customer_shipping_group = :customer_shipping_group"
        )->order(
            ['dest_country_id DESC', 'dest_region_id DESC', 'dest_zip DESC', 'position ASC']
        );





        $zoneWhere='';
        $bind=[];
        switch ($j) {

            case 0: // country, region, city, postcode
                $zoneWhere =  "dest_country_id = :country_id AND dest_region_id = :region_id " .$zipSearchString;
                $bind = [
                    ':country_id' => $country_id,
                    ':region_id' => (int)$region_id,
                    ':postcode' => $postcode,
                ];
                break;
            case 1: // country, region, no city, postcode
                $zoneWhere =  "dest_country_id = :country_id AND dest_region_id = :region_id AND dest_city='*' "
                    .$zipSearchString;
                $bind = [
                    ':country_id' => $country_id,
                    ':region_id' => (int)$request->getDestRegionId(),
                    ':postcode' => $postcode,
                ];
                break;
            case 2: // country, state, city, no postcode
                $zoneWhere = "dest_country_id = :country_id AND dest_region_id = :region_id AND STRCMP(LOWER(dest_city),LOWER(:city))= 0 AND dest_zip ='*'";
                $bind = [
                    ':country_id' => $country_id,
                    ':region_id' => (int)$request->getDestRegionId(),
                    ':city' => $request->getDestCity(),
                ];
                break;
            case 3: //country, city, no region, no postcode
                $zoneWhere =  "dest_country_id = :country_id AND dest_region_id = '0' AND STRCMP(LOWER(dest_city),LOWER(:city))= 0 AND dest_zip ='*'";
                $bind = [
                    ':country_id' => $country_id,
                    ':city' => $request->getDestCity(),
                ];
                break;
            case 4: // country, postcode
                $zoneWhere =  "dest_country_id = :country_id AND dest_region_id = '0' AND dest_city ='*' "
                    .$zipSearchString;
                $bind = [
                    ':country_id' => $country_id,
                    ':postcode' => $postcode,
                ];
                break;
            case 5: // country, region
                $zoneWhere =  "dest_country_id = :country_id AND dest_region_id = :region_id  AND dest_city ='*' AND dest_zip ='*'";
                $bind = [
                    ':country_id' => $request->getDestCountryId(),
                    ':region_id' => (int)$request->getDestRegionId(),
                ];
                break;
            case 6: // country
                $zoneWhere =  "dest_country_id = :country_id AND dest_region_id = '0' AND dest_city ='*' AND dest_zip ='*'";
                $bind = [
                    ':country_id' => $request->getDestCountryId(),
                ];
                break;
            case 7: // nothing
                $zoneWhere =  "dest_country_id = '0' AND dest_region_id = '0' AND dest_city ='*' AND dest_zip ='*'";
                break;
        }

        //    echo $zoneWhere."\n";
        $select->where($zoneWhere);

        $bind[':website_id'] = (int)$request->getWebsiteId();


        $bind[':customer_shipping_group'] = $customer_shipping_group;
        // $bind[':condition_name'] = $request->getConditionMRName();

        //SHQ18-1978
        $condition = $request->getData($request->getConditionMRName());

        if ($condition == null || $condition == "") {
            $condition = 0;
        }

        //  $bind[':condition_value'] = $condition;

        // $select->where('weight_from >= :weight_from_value');
        // $bind[':weight_from_value'] = $order_weight;


        // $select->where('weight_to <= :weight_to_value');
        // $bind[':weight_to_value'] = $order_weight;

        // $select->where('((weight_from >= -1) AND weight_to >= :order_weight) OR ((weight_from <= :order_weight) AND weight_to <= 99999999999)');


        $select->where("(weight_to > :order_weight OR weight_to = '*')"); //29 logic ok

        $select->where("(weight_from < :order_weight OR weight_from = '*')"); //29 logic ok

        $bind[':order_weight'] = $order_weight;

        $select->where("(order_total_under_and_equal > :order_total OR order_total_under_and_equal = '*')"); //29 logic ok
        $select->where("(order_total_over < :order_total OR order_total_over = '*')"); //29 logic ok



        $bind[':order_total'] = $order_total;
        // $select->where('price_from < :order_total'); //29 logic ok
        //   OR ((price_from >= :order_total) AND price_to <= 99999999999)


        //$bind[':order_weight'] = 30;
        //$bind[':order_total'] = 300;

        //$bind[':order_total_two'] = (int)300;


        $this->logger->debug('SQL Select: ', $select->getPart('where'));
        $this->logger->debug('Bindings: ', $bind);
        $results = $adapter->fetchAll($select, $bind);
        $sql = $select->__toString();
        $sql = strtr($sql, $bind );
        //  echo "<pre>";
        //  print_r($bind);
        //echo $select."";
        if (!empty($results)) {

            //   echo "<pre>";
            //print_r($results);
            //
            //   echo "$sql\n";
            $sql = strtr($sql, $bind );
            //          echo "$sql\n";

            //      print_r($select->getPart('where'));
            //      print_r($bind);

            $this->logger->debug('SQL Results: ', $results);
            $too_many_rates = 0;

            foreach ($results as $data) {
                $too_many_rates++;
                if($too_many_rates > 50){

                    break;
                }


                $shippingData[$data['customer_shipping_group']."_".$data['shipping_code_group']][$data['pk']]=$data;
            }
            //break;
        }
        else{


        }
    }
    return $shippingData;
}
    /**
     * Upload table rate file and import data from it
     *
     * @param \Magento\Framework\DataObject $object
     *
     * @return \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function uploadAndImport(\Magento\Framework\DataObject $object)
    {
        //M2-24
        $importFieldData = $object->getFieldsetDataValue('import');
        if (empty($importFieldData['tmp_name'])) {
            return $this;
        }

        $website = $this->storeManager->getWebsite($object->getScopeId());
        $csvFile = $importFieldData['tmp_name'];

        $this->importWebsiteId = (int)$website->getId();
        $this->importUniqueHash = [];
        $this->importErrors = [];
        $this->importedRows = 0;

        //M2-20
        $tmpDirectory = ini_get('upload_tmp_dir')? $this->readFactory->create(ini_get('upload_tmp_dir'))
            : $this->filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        $path = $tmpDirectory->getRelativePath($csvFile);
        $stream = $tmpDirectory->openFile($path);

        // check and skip headers
        $this->importHeaders = array_flip($stream->readCsv());
        if ($this->importHeaders === false || count($this->importHeaders) < 5) {
            $stream->close();
            throw new \Magento\Framework\Exception\LocalizedException(__('Please correct Matrix Rates File Format.'));
        }

        // check these headers
        //print_r($headers);
        /* country
        region_state
        city
        zip_postcode_from
        zip_postcode_to
        weight_over
        weight_under_and_equal
        price_under_and_equal
        price_over
        product_shipping_group
        customer_shipping_group
        rate_type
        shipping_price
        shipping_method
        product_sku
        delivery_note

*/



        if ($object->getData('groups/matrixrate/fields/condition_name/inherit') == '1') {
            $conditionName = (string)$this->coreConfig->getValue('carriers/matrixrate/condition_name', 'default');
        } else {
            $conditionName = $object->getData('groups/matrixrate/fields/condition_name/value');
        }
        $this->importConditionName = $conditionName;

        $adapter = $this->getConnection();
        $adapter->beginTransaction();

        try {
            $rowNumber = 1;
            $importData = [];

            $this->_loadDirectoryCountries();
            $this->_loadDirectoryRegions();

            // delete old data by website and condition name
            $condition = [
                'website_id = ?' => $this->importWebsiteId
            ];
          //  $adapter->delete($this->getMainTable(), $condition);
            $adapter->delete($this->getMainTable(), '');
            while (false !== ($csvLine = $stream->readCsv())) {
                $rowNumber++;

                if (empty($csvLine)) {
                    continue;
                }



                //comma seperated countries - make sure individula line is processed
                $countries_exp = explode(',', $csvLine[$this->importHeaders['country']]);
                foreach($countries_exp AS $key => $val){
                    $csvLine[0] =  $val;


                    $row = $this->_getImportRow($csvLine, $rowNumber);
                    if ($row !== false) {
                        $importData[] = $row;
                    }


                    if (count($importData) == 5000) {
                        $this->_saveImportData($importData);
                        $importData = [];
                    }
                }






            }
            $this->_saveImportData($importData);
            $stream->close();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $adapter->rollback();
            $stream->close();
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        } catch (\Exception $e) {
            $adapter->rollback();
            $stream->close();
            $this->logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing matrix rates.')
            );
        }

        $adapter->commit();

        if ($this->importErrors) {
            $error = __(
                'We couldn\'t import this file because of these errors: %1',
                implode(" \n", $this->importErrors)
            );
            throw new \Magento\Framework\Exception\LocalizedException($error);
        }

        return $this;
    }

    /**
     * Load directory countries
     *
     * @return \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate
     */
    protected function _loadDirectoryCountries()
    {
        if ($this->importIso2Countries !== null && $this->importIso3Countries !== null) {
            return $this;
        }

        $this->importIso2Countries = [];
        $this->importIso3Countries = [];

        /** @var $collection \Magento\Directory\Model\ResourceModel\Country\Collection */
        $collection = $this->countryCollectionFactory->create();
        foreach ($collection->getData() as $row) {
            $this->importIso2Countries[$row['iso2_code']] = $row['country_id'];
            $this->importIso3Countries[$row['iso3_code']] = $row['country_id'];
        }

        return $this;
    }

    /**
     * Load directory regions
     *
     * @return \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate
     */
    protected function _loadDirectoryRegions()
    {
        if ($this->importRegions !== null) {
            return $this;
        }

        $this->importRegions = [];

        /** @var $collection \Magento\Directory\Model\ResourceModel\Region\Collection */
        $collection = $this->regionCollectionFactory->create();
        foreach ($collection->getData() as $row) {
            $this->importRegions[$row['country_id']][$row['code']] = (int)$row['region_id'];
        }

        return $this;
    }

    /**
     * Return import condition full name by condition name code
     *
     * @param string $conditionName
     * @return string
     */
    protected function getConditionFullName($conditionName)
    {
        if (!isset($this->conditionFullNames[$conditionName])) {
            $name = $this->carrierMatrixrate->getCode('condition_name_short', $conditionName);
            $this->conditionFullNames[$conditionName] = $name;
        }

        return $this->conditionFullNames[$conditionName];
    }

    /**
     * Validate row for import and return table rate array or false
     * Error will be add to importErrors array
     *
     * @param array $row
     * @param int $rowNumber
     * @return array|false
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getImportRow($row, $rowNumber = 0)
    {

        // validate row
        if (count($row) < 7) {
            $this->importErrors[] =
                __('Please correct Matrix Rates format in Row #%1. Invalid Number of Rows', $rowNumber);
            return false;
        }


        // strip whitespace from the beginning and end of each row
        foreach ($row as $k => $v) {
            $row[$k] = trim($v);
        }



        // validate country
        if (isset($this->importIso2Countries[$row[$this->importHeaders['country']]])) {
            $countryId = $this->importIso2Countries[$row[$this->importHeaders['country']]];
        } elseif (isset($this->importIso3Countries[$row[$this->importHeaders['country']]])) {
            $countryId = $this->importIso3Countries[$row[$this->importHeaders['country']]];
        } elseif ($row[$this->importHeaders['country']] == '*' || $row[$this->importHeaders['country']] == '') {
            $countryId = '0';
        } else {
            $this->importErrors[] = __('Please correct Country "%1" in Row #%2.', $row[$this->importHeaders['country']], $rowNumber);
            return false;
        }

        // validate region
        if ($countryId != '0' && isset($this->importRegions[$countryId][$row[$this->importHeaders['region_state']]])) {
            $regionId = $this->importRegions[$countryId][$row[$this->importHeaders['region_state']]];
        } elseif ($row[$this->importHeaders['region_state']] == '*' || $row[$this->importHeaders['region_state']] == '') {
            $regionId = 0;
        } else {
            $this->importErrors[] = __('Please correct Region/State "%1" in Row #%2.', $row[$this->importHeaders['region_state']], $rowNumber);
            return false;
        }

        // detect city
        if ($row[$this->importHeaders['city']] == '*' || $row[$this->importHeaders['city']] == '') {
            $city = '*';
        } else {
            $city = $row[$this->importHeaders['city']];
        }

        // detect zip code
        if ($row[$this->importHeaders['zip_postcode_from']] == '*' || $row[$this->importHeaders['zip_postcode_from']] == '') {
            $zipCode = '*';
        } else {
            $zipCode = $row[$this->importHeaders['zip_postcode_from']];
        }

        //zip to
        if ($row[$this->importHeaders['zip_postcode_to']] == '*' || $row[$this->importHeaders['zip_postcode_to']] == '') {
            $zip_to = '';
        } else {
            $zip_to = $row[$this->importHeaders['zip_postcode_to']];
        }





        // validate condition from value
        $weight_over = $row[$this->importHeaders['weight_over']] == '*' ? -1 : $this->_parseDecimalValue($row[$this->importHeaders['weight_over']]);

        // validate conditionto to value
        $weight_under_and_equal = $row[$this->importHeaders['weight_under_and_equal']] == '*' ? 10000000 :$this->_parseDecimalValue($row[$this->importHeaders['weight_under_and_equal']]);

        $item_price_over = $row[$this->importHeaders['item_price_over']] == '*' ? -1 : $this->_parseDecimalValue($row[$this->importHeaders['item_price_over']]);

        // validate conditionto to value
        $item_price_under_and_equal = $row[$this->importHeaders['item_price_under_and_equal']] == '*' ? 10000000 :$this->_parseDecimalValue($row[$this->importHeaders['item_price_under_and_equal']]);

        $order_total_over = $row[$this->importHeaders['order_total_over']] == '*' ? -1 : $this->_parseDecimalValue($row[$this->importHeaders['order_total_over']]);

        // validate conditionto to value
        $order_total_under_and_equal = $row[$this->importHeaders['order_total_under_and_equal']] == '*' ? 10000000 :$this->_parseDecimalValue($row[$this->importHeaders['order_total_under_and_equal']]);



        $product_shipping_group = $row[$this->importHeaders['product_shipping_group']];
        $customer_shipping_group = $row[$this->importHeaders['customer_shipping_group']];
        $attribute_match = $row[$this->importHeaders['attribute_match']];
        $position = $row[$this->importHeaders['position']];

        $product_sku = $row[$this->importHeaders['product_sku']];
        $delivery_note = $row[$this->importHeaders['delivery_note']];

        $rate_type = $row[$this->importHeaders['rate_type']];
        $shipping_code_group = $row[$this->importHeaders['shipping_code_group']];
        $shippingMethod = '';
        // validate price
        $price = $this->_parseDecimalValue($row[$this->importHeaders['shipping_price']]);
        if ($price === false) {
            $this->importErrors[] = __('Please correct Shipping Price "%1" in Row #%2.', $row[$this->importHeaders['shipping_price']], $rowNumber);
            return false;
        }

        // validate shipping method
    //    if ($row[$this->importHeaders['shipping_method']] == '*' || $row[$this->importHeaders['shipping_method']] == '') {
       //     $this->importErrors[] = __('Please correct Shipping Method "%1" in Row #%2.', $row[$this->importHeaders['shipping_method']], $rowNumber);
      //      return false;
      //  } else {
       //     $shippingMethod = $row[$this->importHeaders['shipping_method']];
       // }

        // protect from duplicate
        /*
        $hash = sprintf(
            "%s-%s-%s-%s-%F-%F-%s",
            $countryId,
            $city,
            $regionId,
            $zipCode,
            $shippingMethod
        );
        if (isset($this->importUniqueHash[$hash])) {
            $this->importErrors[] = __(
                'Duplicate Row #%1 (Country "%2", Region/State "%3", City "%4", Zip from "%5", Zip to "%6", From Value "%7", To Value "%8", and Shipping Method "%9")',
                $rowNumber,
                $this->importHeaders['country'],
                $this->importHeaders['region_state'],
                $city,
                $zipCode,
                $zip_to,
                $shippingMethod
            );
            return false;
        }

        $this->importUniqueHash[$hash] = true;
*/


        return [
            $this->importWebsiteId,    // website_id
            $countryId,                 // dest_country_id
            $regionId,                  // dest_region_id,
            $city,                      // city,
            $zipCode,                   // dest_zip
            $zip_to,                    //zip to
            // validate condition from value
            $weight_over,
            // validate conditionto to value
            $weight_under_and_equal,
            $order_total_over,
            $order_total_under_and_equal,
$item_price_over,
    $item_price_under_and_equal,
            $product_shipping_group,
            $customer_shipping_group,
            $attribute_match,
            $product_sku,
            //   $delivery_note,
            $price,                     // price
            $shippingMethod,
            $rate_type,
            $shipping_code_group,
            $position
        ];
    }

    /**
     * Save import data batch
     *
     * @param array $data
     *
     * @return \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\Matrixrate
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _saveImportData(array $data)
    {
        if (!empty($data)) {
            $columns = [
                'website_id',
                'dest_country_id',
                'dest_region_id',
                'dest_city',
                'dest_zip',
                'dest_zip_to',
                'weight_from',
                'weight_to',

                'order_total_over',
            'order_total_under_and_equal',
'item_price_over',
    'item_price_under_and_equal',
                'product_shipping_group',
                'customer_shipping_group',

                'attribute_match',
                'sku',
                // 'delivery_note',
                'price',
                'shipping_method',
                'rate_type',
                'shipping_code_group',
                'position'
            ];

            // echo "<pre>";

            try{
                $this->getConnection()->insertArray($this->getMainTable(), $columns, $data);
                $this->importedRows += count($data);


            }
            catch(Exception $e){

                print_r($e->getMessage());
            }



        }

        return $this;
    }

    /**
     * Parse and validate positive decimal value
     * Return false if value is not decimal or is not positive
     *
     * @param string $value
     * @return bool|float
     */
    protected function _parseDecimalValue($value)
    {
        if (!is_numeric($value)) {
            return false;
        }
        $value = (double)sprintf('%.4F', $value);
        if ($value < 0.0000) {
            return false;
        }
        return $value;
    }
}
