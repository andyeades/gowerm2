<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Edit;

use DateTime;
use Elevate\PrintLabels\Helper\Data;

class DPDAuthorisation
{

    // Initialize varables
    private $version = "Interlink express API class v1.0c";
    private $url;
    private $timeout;
    private $ch;
    private $headers;
    private $username;
    private $password;
    private $accountNo;
    private $jsonSize = 0;
    private $returnFormat = 'application/json';
    private $contentType = 'application/json';
    private $isPrintjob = 0;

    private $geoSession;

    /**
     * @var string
     */
    protected $storedGeosession;

    /**
     * @var \Elevate\PrintLabels\Api\PrintlabelsApiRepositoryInterface
     */
    protected $printlabelsApi;

    /**
     * @var \Elevate\PrintLabels\Model\PrintlabelsApiRepository
     */
    protected $printlabelsApiRepo;

    /**
     * @var \Elevate\PrintLabels\Helper\Data
     */
    protected $helper;

    /**
     * DPDAuthorisation constructor.
     *
     * @param \Elevate\PrintLabels\Helper\Data $helper
     */
    // Construct object
    public function __construct(
        \Elevate\PrintLabels\Helper\Data $helper,
        \Elevate\PrintLabels\Api\PrintlabelsApiRepositoryInterface $printlabelsApi,
        \Elevate\PrintLabels\Model\PrintlabelsApiRepository $printlabelsApiRepo
    ) {
        $this->helper = $helper;
        $this->printlabelsApi = $printlabelsApi;
        $this->printlabelsApiRepo = $printlabelsApiRepo;
        $this->url = $this->helper->getApiUrl();
        $this->username = $this->helper->getApiUsername();
        $this->password = $this->helper->getApiPassword();
        $this->accountNo = $this->helper->getApiAccountNumber();
        $this->ch = curl_init();
        $this->geoSession = $this->authenticate();
    }

    // Do authentication
    private function authenticate(
        $timeout = '5',
        $headers = []
    ) {

        //return $this->originalAuthenticate($timeout, $headers);


             // Check if We have a GeoSession that is valid from today
        // Get Record Should only be 1

        $currentDateTime = new DateTime('NOW');

        try {
            $printLabelsApiRecord = $this->printlabelsApi->getById(1);
            $stored_geosession =  $printLabelsApiRecord->getGeosession();
            $stored_geosession_last_checked = $printLabelsApiRecord->getLastChecked();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $stored_geosession = false;
            $stored_geosession_last_checked = false;
        }
        $geosession = '';

        if (!empty($stored_geosession_last_checked)) {
            $compared_date = date('Y-m-d H:i:s', strtotime($stored_geosession_last_checked));

            $storedDateTime = new DateTime($compared_date);

            $compare = $currentDateTime->diff($storedDateTime);

            $timebetween_days = $compare->d;
            $timebetween_hours = $compare->h;

            if (empty($timebetween_days)) {
                // Geo Session Ok!
                if (!empty($stored_geosession)) {
                    // Assume GeoSession is good
                   $geosession = $stored_geosession;
                } else {
                    // Weird It would Get here But
                    $geosession = $this->setDbGeosession($timeout, $headers, $currentDateTime);
                }
            } else {
                $geosession = $this->setDbGeosession($timeout, $headers, $currentDateTime);

            }
        } else {
            // First Time ever Running?

            $geosession = $this->setDbGeosession($timeout, $headers, $currentDateTime);


        }
        return $geosession;
    }

    protected function setDbGeosession($timeout, $headers, $currentDateTime)
    {
        $geosession = $this->originalAuthenticate($timeout, $headers);

        $lastchecked_date = $currentDateTime->format('Y-m-d H:i:s');

        $printLabelsApiRecord = $this->printlabelsApi->create();

        $printLabelsApiRecord->setPrintlabelsApiId(1);
        $printLabelsApiRecord->setGeosession($geosession);
        $printLabelsApiRecord->setLastChecked($lastchecked_date);

        $this->printlabelsApiRepo->save($printLabelsApiRecord);
        return $geosession;
    }

    private function originalAuthenticate(
        $timeout = '5',
        $headers = []
    ) {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->username . ':' . $this->password),
            'GEOClient: ' . $this->username . '/' . $this->accountNo,
            //'Content-Length: 0'
        ];
        curl_setopt_array(
            $this->ch,
            [
                         CURLOPT_URL            => $this->url . '/user/?action=login',
                         CURLOPT_RETURNTRANSFER => true,
                         CURLOPT_CONNECTTIMEOUT => $timeout,
                         CURLOPT_USERAGENT      => $this->version,
                         CURLOPT_HTTPHEADER     => $headers,
                         CURLOPT_CUSTOMREQUEST  => 'POST'
                     ]
        );
        $authPost = curl_exec($this->ch);
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $data = json_decode($authPost, true);
        if (curl_errno($this->ch)) {
            $data['data']['error'] = true;
            $data['data']['error_message'] = "Error connecting to API: ' curl_error($this->ch)";

            return $data;
        //throw new Exception('Error connecting to API: ' . curl_error($this->ch));
        } elseif ($httpCode === 401 || $httpCode === 403 || $httpCode === 404 || $httpCode === 500 || $httpCode === 503) {
            $error_message = $this->getHttpErrorMessage($httpCode);

            $data['data']['error'] = true;
            $data['data']['error_message'] = $error_message;

            return $data;
        } else {
            return $data['data']['geoSession'];
        }
    }

    // Construct headers for data transfer
    private function constructHeaders($headers = [])
    {
        return [
            'Content-Type: ' . $this->contentType,
            'Accept: ' . $this->returnFormat,
            'GEOClient: account/' . $this->accountNo,
            'GEOSession: ' . $this->geoSession,
            'Content-Length: ' . $this->jsonSize
        ];
    }

    // List shipping countries
    public function listCountry()
    {
        $method = "GET";
        $reqStr = "/shipping/country";
        $query = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    // Custom Get
    public function customGet($str)
    {
        $method = "GET";
        $query = $this->doQuery($method, $str);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //Get Shipping
    public function getShipping($data)
    {
        $method = "GET";
        // Needs cleaning up but regex is like Chinese to me !
        $data = str_replace('%5D', '', str_replace('%5B', '.', http_build_query($data)));
        $reqStr = "/shipping/network/?" . $data;
        $query = $this->doQuery($method, $reqStr);

        return $query;

        /*
         if (isset($query['error'])) {
             return $query;
         } else {
             return $query;
         }
            */ //return isset($query['error']) ? $this->apiError($query['error']): $query;
        //return isset($query['error']) ? $this->apiError($query['error']): $query;
    }

    // Get country
    public function getCountry($country)
    {
        $method = "GET";
        $reqStr = "/shipping/country/";
        $query = $this->doQuery($method, $reqStr . $country);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //Get Network Code
    public function getNetcode($geoCode)
    {
        $method = "GET";
        $reqStr = "/shipping/network/" . $geoCode;
        $query = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //Insert Shipping
    public function insertShipping(
        $payload,
        $dataype
    ) {
        $method = "POST";
        $reqStr = "/shipping/shipment";
        $this->encodePayload($payload);
        $this->contentType = 'application/json';
        $this->returnFormat = 'application/json';
        $this->constructHeaders();
        $query = $this->doQuery($method, $reqStr);

        return isset($query['error']) ? $query : $query;
    }

    //Get Label
    public function getLabel(
        $shipmentId,
        $dataType
    ) {
        $method = "GET";
        $previousreturnFormat = $this->returnFormat;

        $this->returnFormat = $dataType;
        $reqStr = "/shipping/shipment/" . $shipmentId . "/label/";
        $this->isPrintjob = 1;
        $this->contentType = 'Accept';

        $query = $this->doQuery($method, $reqStr);

        // Reset Back to Normal!
        $this->contentType = 'application/json';
        $this->returnFormat = $previousreturnFormat;
        $this->isPrintjob = 0;

        return isset($query['error']) ? $this->apiError($query['error']) : $query;
    }

    //doQuery
    private function doQuery(
        $method,
        $reqStr
    ) {

        //echo $this->url.$reqStr."<br />";

        $this->headers = $this->constructHeaders();
        curl_setopt_array(
            $this->ch,
            [
                         CURLOPT_URL            => $this->url . $reqStr,
                         CURLOPT_RETURNTRANSFER => true,
                         CURLOPT_CONNECTTIMEOUT => $this->timeout,
                         CURLOPT_USERAGENT      => $this->version,
                         CURLOPT_HTTPHEADER     => $this->headers,
                         //    CURLINFO_HEADER_OUT => true,
                         CURLOPT_CUSTOMREQUEST  => $method,
                         CURLOPT_POSTFIELDS     => (isset($this->payload)) ? $this->payload : null
                     ]
        );
        $data = curl_exec($this->ch);
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        //If print job don't decode.
        if ($this->isPrintjob == 0) {
            $response = json_decode($data, true);
        } else {
            $response = $data;
        }

        $info = curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
        //echo "<textarea>";
        //ech($info); echo "</textarea>";
        if (curl_errno($this->ch)) {
            throw new Exception('Error connecting to API: ' . curl_error($this->ch));
        } elseif ($httpCode === 401 || $httpCode === 403 || $httpCode === 404 || $httpCode === 500 || $httpCode === 503) {
            $this->httpError($httpCode);
        } else {
            return $response;
        }
    }

    // Encode payload
    private function encodePayload($payload)
    {
        $this->payload = json_encode($payload);
        $this->jsonSize = strlen($this->payload);
    }

    // Handle HTTP errors (Old way)
    public function httpError($httpCode)
    {
        switch ($httpCode) {
            case '401':
                throw new Exception('Username / Password incorrect');
                break;
            case '403':
                throw new Exception('Geosession header not found or invalid');
                break;
            case '404':
                throw new Exception('An attempt was made to call an API in which the URL cannot be found');
                break;
            case '500':
                throw new Exception('The ESG server had an internal error');
                break;
            case '503':
                throw new Exception('The API being called is temporary out of service');
                break;
        }
    }

    // Handle HTTP errors
    public function getHttpErrorMessage($httpCode)
    {
        switch ($httpCode) {
            case '401':
                return 'EXTERNAL API ERROR - Username / Password incorrect';
            case '403':
                return 'EXTERNAL API ERROR - Geosession header not found or invalid';
            case '404':
                return 'EXTERNAL API ERROR - An attempt was made to call an API in which the URL cannot be found';

            case '500':
                return 'EXTERNAL API ERROR - The ESG server had an internal error';

            case '503':
                return 'EXTERNAL API ERROR - The API being called is temporary out of service';

        }
    }

    // Return API error to front end for various reasons
    public function apiErrorMessage($err)
    {
        // Wan't it to return to front end what is happening! so order can be added to problem orders;

        if (isset($err[0])) {
            return 'API Error! Code: ' . $err[0]['errorCode'] . ' Type: ' . $err[0]['errorType'] . ' Message: ' . $err[0]['obj'] . ' / ' . $err[0]['errorMessage'];
        } else {
            return 'API Error! Code: ' . $err['errorCode'] . ' Type: ' . $err['errorType'] . ' Message: ' . $err['obj'] . ' / ' . $err['errorMessage'];
        }
    }

    // Handle API errors
    public function apiError($err)
    {
        // Probably not the ideal sollution but works and I'm not really a PHP dev. Please clean me !! :D
        if (isset($err[0])) {
            throw new Exception('API Error! Code: ' . $err[0]['errorCode'] . ' Type: ' . $err[0]['errorType'] . ' Message: ' . $err[0]['obj'] . ' / ' . $err[0]['errorMessage']);
        } else {
            throw new Exception('API Error! Code: ' . $err['errorCode'] . ' Type: ' . $err['errorType'] . ' Message: ' . $err['obj'] . ' / ' . $err['errorMessage']);
        }
    }

    // Destruct object
    public function __destruct()
    {
        curl_close($this->ch);
    }
}
