<?php
namespace Elevate\PrintLabels\Helper;
 
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
 
class Data extends AbstractHelper
{

    /**
     * @var mixed
     */
    protected $apiUsername;

    /**
     * @var mixed
     */
    protected $apiPassword;

    /**
     * @var mixed
     */
    protected $apiUrl;

    /**
     * @var mixed
     */
    protected $apiAccountNumber;

    /**
     * @var mixed
     */
    protected $senderContactName;

    /**
     * @var mixed
     */
    protected $senderTelephone;

    /**
     * @var mixed
     */
    protected $senderOrgName;

    /**
     * @var mixed
     */
    protected $senderOrgStreet;

    /**
     * @var mixed
     */
    protected $senderOrgStreet2;

    /**
     * @var mixed
     */
    protected $senderOrgTownCity;

    /**
     * @var mixed
     */
    protected $senderOrgCounty;

    /**
     * @var mixed
     */
    protected $senderOrgPostcode;

    /**
     * @var mixed
     */
    protected $senderOrgCountryCode;

    /**
     * @var mixed
     */
    protected $collectionTime;

    /**
     * @var mixed
     */
    protected $collectionCutOffTime;

    /**
     * @var mixed
     */
    protected $collectionDays;

    /**
     * @var mixed
     */
    protected $printNodeApiKey;



    /**
     * @var mixed
     */
    protected $printNodePrinterId;

    /**
     * @var int
     */
    protected $printNodeInvoicePrinterId;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;


    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->printNodeApiKey = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeapikey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodeInvoicePrinterId = 68967868; // Temp
        //$this->printNodeInvoicePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeinvoiceprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiAccountNumber = $this->scopeConfig->getValue('elevate_printlabels/details/api_accountnumber', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiUsername = $this->scopeConfig->getValue('elevate_printlabels/details/api_username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiPassword = $this->scopeConfig->getValue('elevate_printlabels/details/api_password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiUrl = $this->scopeConfig->getValue('elevate_printlabels/details/api_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderContactName = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/contact_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderTelephone = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/contact_telephone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgName = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgStreet = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_street', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgStreet2 = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_locality', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgTownCity = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_towncity', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgCounty = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_county', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgPostcode = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_postcode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgCountryCode = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_countrycode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->collectionTime = $this->scopeConfig->getValue('elevate_printlabels/collection/collection_time', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->collectionCutOffTime = $this->scopeConfig->getValue('elevate_printlabels/collection/collection_cutofftime', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->collectionDays = $this->scopeConfig->getValue('elevate_printlabels/collection/collection_days', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
 
    }

    /**
     * @return mixed
     */
    public function getApiUsername() {
        return $this->apiUsername;
    }

    /**
     * @return mixed
     */
    public function getApiPassword() {
        return $this->apiPassword;
    }

    /**
     * @return mixed
     */
    public function getApiUrl() {
        return $this->apiUrl;
    }

    /**
     * @return mixed
     */
    public function getApiAccountNumber() {
        return $this->apiAccountNumber;
    }

    /**
     * @return mixed
     */
    public function getSenderContactName() {
        return $this->senderContactName;
    }

    /**
     * @return mixed
     */
    public function getSenderTelephone() {
        return $this->senderTelephone;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgName() {
        return $this->senderOrgName;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgStreet() {
        return $this->senderOrgStreet;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgStreet2() {
        return $this->senderOrgStreet2;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgTownCity() {
        return $this->senderOrgTownCity;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgCounty() {
        return $this->senderOrgCounty;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgPostcode() {
        return $this->senderOrgPostcode;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgCountryCode() {
        return $this->senderOrgCountryCode;
    }

    /**
     * @return mixed
     */
    public function getCollectionTime() {
        return $this->collectionTime;
    }

    /**
     * @return mixed
     */
    public function getCollectionCutOffTime() {
        return $this->collectionCutOffTime;
    }

    /**
     * @return mixed
     */
    public function getCollectionDays() {
        return $this->collectionDays;
    }

    /**
     * @return mixed
     */
    public function getPrintNodeApiKey() {
        return $this->printNodeApiKey;
    }

    /**
     * @return mixed
     */
    public function getPrintNodePrinterId() {
        return $this->printNodePrinterId;
    }


    /**
     * @return integer
     */
    public function getPrintNodeInvoicePrinterId() {
        return $this->printNodeInvoicePrinterId;
    }

    /**
     * @param $shipping_array
     *
     * @return string
     */
    public function getShippingOptionsSelect($shipping_array, $order) {

        $output = '';

        $details_array = array();

        $example = '<select name="shipping_networkCode">
<option class="" data-name="" data-notnormally="0" data-networkcode="2^03" value="2^03">Expak 1 By 12</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^04" value="2^04">Expak 1 By 1030</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^05" value="2^05">Expak 1 Sat By 12</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^06" value="2^06">Expak 1 Sat By 1030</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^47" value="2^47">Expak 1 Sun By 12</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^68" value="2^68">Expak 1 Next Day</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^72" value="2^72">Expak 1 Saturday</option>
<option class="" data-name="" data-notnormally="0" data-networkcode="2^76" value="2^76">Expak 1 Sunday</option>
    <option value="2^08">Parcel By 1030</option>
    <option value="2^09">Parcel Sat By 1030</option>
    <option value="2^12">Parcel Next Day</option>
    <option value="2^13">Parcel By 12</option>
    <option value="2^15">Parcel Sun By 12</option>
    <option value="2^17">Parcel Sat By 12</option>
    <option value="2^22">Parcel Return to Shop</option>
    <option value="2^71">Parcel Saturday</option>
    <option value="2^75">Parcel Sunday</option>
    <option value="2^91">Parcel Ship to Shop</option>
    <option value="2^28">Expak 5 By 1030</option>
    <option value="2^29">Expak 5 Sat By 1030</option>
    <option value="2^32">Expak 5 Next Day</option>
    <option value="2^33">Expak 5 By 12</option>
    <option value="2^37">Expak 5 Sat By 12</option>
    <option value="2^51">Expak 5 Sun By 12</option>
    <option value="2^73">Expak 5 Saturday</option>
    <option value="2^77">Expak 5 Sunday</option>
    <option value="2^45">Freight Sun By 12</option>
    <option value="2^65">Freight By 1030</option>
    <option value="2^69">Freight Sat By 1030</option>
    <option value="2^74">Freight Saturday</option>
    <option value="2^78">Freight Sunday</option>
    <option value="2^82">Freight Next Day</option>
    <option value="2^83">Freight By 12</option>
    <option value="2^87">Freight Sat By 12</option>
  </select>';

        $temp_array = array();
        $temp_array2 = array();

        $hideOptionsNotNormallyUsed = array("2^03","2^04","2^05","2^06","2^47","2^08","2^09","2^13","2^15","2^17","2^22","2^75","2^91","2^28","2^29","2^33","2^37","2^51","2^77","2^45","2^65","2^69","2^74","2^78","2^82","2^83","2^87");


        $expresspak1 = array("2^03","2^04","2^05","2^06","2^47","2^68","2^72","2^76");

        $expresspak5 = array("2^08","2^09","2^13","2^15","2^17","2^22","2^75","2^91","2^28","2^29","2^33","2^37","2^51","2^77","2^45","2^65","2^69","2^74","2^78","2^82","2^83","2^87");

        $weight = $order->getWeight();

        $default_option = '2^32'; // ExpressPak 5 - Next Day;

        if ($weight < 1) {
            // Expresspak 1 - Next Day;

            $default_option = '2^68'; // ExpressPak 1 - Next Day;

        } else if ($weight < 5) {
            // Expresspak 5 - Next Day
            // Unless Qty >= Something meaning more than for option.

            $default_option = '2^32'; // ExpressPak 5 - Next Day;

        } else {
            // Parcel Next Day

            $default_option = '2^12'; // Parcel - Next Day;

        }

        foreach ($shipping_array['data'] AS $key => $val) {

            $productCode = $val['product']['productCode'];
            $productDescription = $val['product']['productDescription'];

            $temp_array2[$productDescription][] = $val;



        }

        foreach ($temp_array2 as $key => $val) {
            foreach($val as $item) {
                $network_code = $item['network']['networkCode'];
                $network_description = $item['network']['networkDescription'];
                $selected = '';
                $class = '';
                $data_not_normally = 0;
                if (in_array($network_code, $hideOptionsNotNormallyUsed)) {
                    $class = 'hide-me';
                    $data_not_normally = 1;
                } else {
                    if (strcmp($network_code, $default_option) === 0){
                        $selected = 'selected="true"';
                    }
                }
                // Add Admin Option To Hide Options Not Likely To Be Used and add to if statement
                if ($data_not_normally === 1) {
                    // Don't Output (Safari/IE issue where they show options with display none

                } else {
                    $output .= '<option '.$selected.' class="'.$class.'" data-notnormally="'.$data_not_normally.'" data-name="" data-networkcode="'.$network_code.'" value="' . $network_code . '">' .$network_description. "</option>";
                }


            }
        }


        return (string)$output;
    }

}