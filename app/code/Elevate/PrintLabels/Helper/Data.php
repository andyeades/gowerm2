<?php

namespace Elevate\PrintLabels\Helper;

use DateInterval;
use DatePeriod;
use DateTime;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

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
     * @var int
     */
    protected $printNodeGiftmessagePrinterId;

    /**
     * @var int
     */
    protected $addressSplitLine;

    /**
     * @var int
     */
    protected $addressTrim;

    /**
     * @var int
     */
    protected $addressTrim2;

    /**
     * @var int
     */
    protected $nameTrim;

    /**
     * @var int
     */
    protected $companyTrim;

    /**
     * @var int
     */
    protected $townTrim;

    /**
     * @var int
     */
    protected $countyTrim;

    /**
     * @var int
     */
    protected $teleTrim;

    /**
     * @var int
     */
    protected $printGiftMessage;

    /**
     * @var int
     */
    protected $printPackingList;

    /**
     * @var int
     */
    protected $printInvoice;

    /**
     * @var int
     */
    protected $packageWeightLimit;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var array
     */
    protected $validpostcodes;

    /**
     * @var mixed
     */
    protected $giftmessagepdfcssstyles;

    /**
     * @var mixed
     */
    protected $printgiftmessagelabelperitem;

    /**
     * @var string
     */
    protected $stored_geosession;

    /**
     * @var string
     */
    protected $alternativedeloption_postcodes;

    /**
     * @var string
     */
    protected $standardukpostcodes;

    /**
     * @var \Elevate\PrintLabels\Api\PrintlabelsApiRepositoryInterface
     */
    protected $printlabelsApi;
    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Elevate\PrintLabels\Api\PrintlabelsApiRepositoryInterface $printlabelsApi
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Elevate\PrintLabels\Api\PrintlabelsApiRepositoryInterface $printlabelsApi
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->printlabelsApi = $printlabelsApi;
        $this->printNodeApiKey = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeapikey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodeInvoicePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeinvoiceprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodePackingListPrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodepackinglistprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodeGiftmessagePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodegiftmessageprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
        $this->addressSplitLine = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/address_split_line', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->addressTrim = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/address_trim', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->addressTrim2 = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/address_trim_2', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->nameTrim = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/name_trim', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->companyTrim = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/company_trim', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->townTrim = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/town_trim', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->countyTrim = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/county_trim', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->teleTrim = $this->scopeConfig->getValue('elevate_printlabels/addressvalidation/tele_trim', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->packageWeightLimit = $this->scopeConfig->getValue('elevate_printlabels/packagedetails/packageweightlimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printGiftMessage = $this->scopeConfig->getValue('elevate_printlabels/printoptions/print_giftmessage', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printPackingList = $this->scopeConfig->getValue('elevate_printlabels/printoptions/print_packinglist', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printInvoice = $this->scopeConfig->getValue('elevate_printlabels/printoptions/print_invoice', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->validpostcodes = [
            'AL',
            'B',
            'BA',
            'BB',
            'BD',
            'BH',
            'BL',
            'BN',
            'BR',
            'BS',
            'BT',
            'CA',
            'CB',
            'CF',
            'CH',
            'CM',
            'CO',
            'CR',
            'CT',
            'CV',
            'CW',
            'DA',
            'DD',
            'DE',
            'DG',
            'DH',
            'DL',
            'DN',
            'DT',
            'DY',
            'E',
            'EC',
            'EH',
            'EN',
            'EX',
            'FK',
            'FY',
            'G',
            'GL',
            'GU',
            'HA',
            'HD',
            'HG',
            'HP',
            'HR',
            'HS',
            'HU',
            'HX',
            'IG',
            'IP',
            'IV',
            'KA',
            'KT',
            'KW',
            'KY',
            'L',
            'LA',
            'LD',
            'LE',
            'LL',
            'LN',
            'LS',
            'LU',
            'M',
            'ME',
            'MK',
            'ML',
            'N',
            'NE',
            'NG',
            'NN',
            'NP',
            'NR',
            'NW',
            'OL',
            'OX',
            'PA',
            'PE',
            'PH',
            'PL',
            'PO',
            'PR',
            'RG',
            'RH',
            'RM',
            'S',
            'SA',
            'SE',
            'SG',
            'SK',
            'SL',
            'SM',
            'SN',
            'SO',
            'SP',
            'SR',
            'SS',
            'ST',
            'SW',
            'SY',
            'TA',
            'TD',
            'TF',
            'TN',
            'TQ',
            'TR',
            'TS',
            'TW',
            'UB',
            'W',
            'WA',
            'WC',
            'WD',
            'WF',
            'WN',
            'WR',
            'WS',
            'WV',
            'YO',
            'ZE'
        ];
        $this->giftmessagepdfcssstyles = $this->scopeConfig->getValue('elevate_printlabels/giftmessage/giftmessagepdfcssstyles', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printgiftmessagelabelperitem = $this->scopeConfig->getValue('elevate_printlabels/giftmessage/printgiftmessagelabelperitem', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->stored_geosession = $printlabelsApi->getById(1)->getGeosession();
        $this->standardukpostcodes = $this->scopeConfig->getValue('elevate_printlabels/postcodes/standardukpostcodes', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->alternativedeloption_postcodes  = $this->scopeConfig->getValue('elevate_printlabels/postcodes/alternativedeloption_postcodes', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getApiUsername()
    {
        return $this->apiUsername;
    }

    /**
     * @return mixed
     */
    public function getApiPassword()
    {
        return $this->apiPassword;
    }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @return mixed
     */
    public function getApiAccountNumber()
    {
        return $this->apiAccountNumber;
    }

    /**
     * @return mixed
     */
    public function getSenderContactName()
    {
        return $this->senderContactName;
    }

    /**
     * @return mixed
     */
    public function getSenderTelephone()
    {
        return $this->senderTelephone;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgName()
    {
        return $this->senderOrgName;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgStreet()
    {
        return $this->senderOrgStreet;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgStreet2()
    {
        return $this->senderOrgStreet2;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgTownCity()
    {
        return $this->senderOrgTownCity;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgCounty()
    {
        return $this->senderOrgCounty;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgPostcode()
    {
        return $this->senderOrgPostcode;
    }

    /**
     * @return mixed
     */
    public function getSenderOrgCountryCode()
    {
        return $this->senderOrgCountryCode;
    }

    /**
     * @return mixed
     */
    public function getCollectionTime()
    {
        return $this->collectionTime;
    }

    /**
     * @return mixed
     */
    public function getCollectionCutOffTime()
    {
        return $this->collectionCutOffTime;
    }

    /**
     * @return mixed
     */
    public function getCollectionDays()
    {
        return $this->collectionDays;
    }

    /**
     * @return mixed
     */
    public function getPrintNodeApiKey()
    {
        return $this->printNodeApiKey;
    }

    /**
     * @return mixed
     */
    public function getPrintNodePrinterId()
    {
        return $this->printNodePrinterId;
    }

    /**
     * @return integer
     */
    public function getPrintNodePackingListPrinterId()
    {
        return $this->printNodePackingListPrinterId;
    }

    /**
     * @return integer
     */
    public function getPrintNodeInvoicePrinterId()
    {
        return $this->printNodeInvoicePrinterId;
    }

    /**
     * @return integer
     */
    public function getPrintNodeGiftmessagePrinterId()
    {
        return $this->printNodeGiftmessagePrinterId;
    }

    /**
     * @return integer
     */
    public function getPackageWeightLimit()
    {
        return $this->packageWeightLimit;
    }

    /**
     * @return int
     */
    public function getAddressSplitLine()
    {
        return $this->addressSplitLine;
    }

    /**
     * @return int
     */
    public function getAddressTrim()
    {
        return $this->addressTrim;
    }

    /**
     * @return int
     */
    public function getAddressTrim2()
    {
        return $this->addressTrim2;
    }

    /**
     * @return int
     */
    public function getNameTrim()
    {
        return $this->_get(self::NAMETRIM);
    }

    /**
     * @return int
     */
    public function getCompanyTrim()
    {
        return $this->companyTrim;
    }

    /**
     * @return int
     */
    public function getTownTrim()
    {
        return $this->townTrim;
    }

    /**
     * @return int
     */
    public function getCountyTrim()
    {
        return $this->countyTrim;
    }

    /**
     * @return int
     */
    public function getTeleTrim()
    {
        return $this->teleTrim;
    }

    /**
     * @return int
     */
    public function getPrintGiftMessage()
    {
        return $this->printGiftMessage;
    }

    /**
     * @return int
     */
    public function getPrintPackingList()
    {
        return $this->printPackingList;
    }

    /**
     * @return int
     */
    public function getPrintInvoice()
    {
        return $this->printInvoice;
    }

    /**
     * @return array
     */
    public function getValidPostcodes()
    {
        return $this->validpostcodes;
    }

    /**
     * @return mixed
     */
    public function getPrintgiftmessagelabelperitem()
    {
        return $this->printgiftmessagelabelperitem;
    }

    /**
     * @return mixed
     */
    public function getGiftmessagepdfcssstyles()
    {
        return $this->giftmessagepdfcssstyles;
    }

    /**
     * @return string
     */
    public function getStoredGeosession()
    {
        return $this->stored_geosession;
    }

    /**
     * @return string
     */
    public function getAlternativedeloptionPostcodes()
    {
        return $this->alternativedeloption_postcodes;
    }

    /**
     * @return string
     */
    public function getStandardukpostcodes()
    {
        return $this->standardukpostcodes;
    }

    /**
     * @param $shipping_array
     *
     * @return string
     */
    public function getShippingOptionsSelect(
        $shipping_array,
        $order
    ) {
        $output = '';

        $details_array = [];

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

        $temp_array = [];
        $temp_array2 = [];

        $hideOptionsNotNormallyUsed = [
            "2^03",
            "2^04",
            "2^05",
            "2^06",
            "2^47",
            "2^08",
            "2^09",
            "2^13",
            "2^15",
            "2^17",
            "2^22",
            "2^75",
            "2^91",
            "2^28",
            "2^29",
            "2^33",
            "2^37",
            "2^51",
            "2^77",
            "2^45",
            "2^65",
            "2^69",
            "2^74",
            "2^78",
            "2^82",
            "2^83",
            "2^87"
        ];

        $expresspak1 = [
            "2^03",
            "2^04",
            "2^05",
            "2^06",
            "2^47",
            "2^68",
            "2^72",
            "2^76"
        ];

        $expresspak5 = [
            "2^08",
            "2^09",
            "2^13",
            "2^15",
            "2^17",
            "2^22",
            "2^75",
            "2^91",
            "2^28",
            "2^29",
            "2^33",
            "2^37",
            "2^51",
            "2^77",
            "2^45",
            "2^65",
            "2^69",
            "2^74",
            "2^78",
            "2^82",
            "2^83",
            "2^87"
        ];

        $weight = $order->getWeight();

        $default_option = '2^32'; // ExpressPak 5 - Next Day;

        if ($weight <= 1) {
            // Expresspak 1 - Next Day;

            $default_option = '2^68'; // ExpressPak 1 - Next Day;
        } elseif ($weight < 5) {
            // Expresspak 5 - Next Day
            // Unless Qty >= Something meaning more than for option.

            $default_option = '2^32'; // ExpressPak 5 - Next Day;
        } else {
            // Parcel Next Day

            $default_option = '2^12'; // Parcel - Next Day;
        }

        foreach ($shipping_array['data'] as $key => $val) {
            $productCode = $val['product']['productCode'];
            $productDescription = $val['product']['productDescription'];

            $temp_array2[$productDescription][] = $val;
        }

        foreach ($temp_array2 as $key => $val) {
            foreach ($val as $item) {
                $network_code = $item['network']['networkCode'];
                $network_description = $item['network']['networkDescription'];
                $selected = '';
                $class = '';
                $data_not_normally = 0;
                if (in_array($network_code, $hideOptionsNotNormallyUsed)) {
                    $class = 'hide-me';
                    $data_not_normally = 1;
                } else {
                    if (strcmp($network_code, $default_option) === 0) {
                        $selected = 'selected="true"';
                    }
                }
                // Add Admin Option To Hide Options Not Likely To Be Used and add to if statement
                if ($data_not_normally === 1) {
                    // Don't Output (Safari/IE issue where they show options with display none
                } else {
                    $output .= '<option ' . $selected . ' class="' . $class . '" data-notnormally="' . $data_not_normally . '" data-name="" data-networkcode="' . $network_code . '" value="' . $network_code . '">' . $network_description . "</option>";
                }
            }
        }

        return (string)$output;
    }

    // RJ Elevate - 17/1/2017

    /**
     * Returns every date between two dates as an array
     *
     * @param string $startDate the start of the date range
     * @param string $endDate   the end of the date range
     * @param string $format    DateTime format, default is Y-m-d
     *
     * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
     */
    public function createDateRange(
        $startDate,
        $endDate,
        $format = "Y-m-d"
    ) {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        //Set Time of End Date to past midnight or it wont include the date >_<
        $end->setTime(0, 0, 1);
        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }

        return $range;
    }
    /**
     * Checks Date Suitability
     *
     * @param $date
     * @param $timeslot_non_deliverydays
     * @param $seperateholidaydates
     */
    public static function checkDateSuitability(
        $date,
        $timeslot_non_deliverydays,
        $seperateholidaydates
    ) {
        $date_suitable = 0;

        // Check to See if we date is suitable if we don't then lets find a day

        while ($date_suitable != 1) {
            if (!in_array($date->format('N'), $timeslot_non_deliverydays)) {
                // Ok so the delivery_date seems suitable, that's awesome, lets check it's not a Holiday!

                if (in_array($date->format('Y-m-d'), $seperateholidaydates)) {
                    // Uh-oh It's a holiday
                    $date->modify('+1 day');
                    continue;
                }
                // Good to Go!
                $date_suitable = 1;
            } else {
                $date->modify('+1 day');
                // We don't deliver via this method on this day, lets find ourselves the next good date.
            }
        }
    }
}
