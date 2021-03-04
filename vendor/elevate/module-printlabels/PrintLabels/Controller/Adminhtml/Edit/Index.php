<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Edit;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
//use PrintNode\Credentials;

use \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;

/**
 * Class Index
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Index extends Action {
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    protected $orderCustomerService;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptorInterface;

   /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $scopeConfig;

    /**
     * @var \PrintNode\Credentials
     */
    protected $printNodeCredentials;

    /**
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;

    
    /**
     * Index constructor.
     *
     * @param Context                                             $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface         $orderRepository
     * @param \Magento\Customer\Api\AccountManagementInterface    $accountManagement
     * @param \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService
     * @param \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory
     * @param \Magento\Framework\Encryption\EncryptorInterface    $encryptorInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param \PrintNode\Credentials                              $printNodeCredentials
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \PrintNode\Credentials $printNodeCredentials,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
    ) {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->accountManagement = $accountManagement;
        $this->encryptorInterface = $encryptorInterface;
        $this->scopeConfig = $scopeConfig;
        $this->printNodeCredentials = $printNodeCredentials;
        $this->dpdAuthorisation = $dpdAuthorisation;
        $this->printNodeApiKey = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeapikey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute() {

        /*
        we need a paperwork router
        Selects which paperwork and how to print it, in which order
        can we guarantee print - if everything via printnode
        */

        $this->dpdPrint();
        exit;


        $request = $this->getRequest();
        $orderId = $request->getPost('order_id', NULL);
        $emailAddress = $request->getPost('email', NULL);
        $oldEmailAddress = $request->getPost('old_email', NULL);

        $resultJson = $this->resultJsonFactory->create();


        // RJ - What's this code for?

        if ($orderId) {
            /** @var  $order \Magento\Sales\Api\Data\OrderInterface */
            $order = $this->orderRepository->get($orderId);

            if ($order->getEntityId() && \Zend_Validate::is($emailAddress, 'EmailAddress')) {
                try {
                    $order = $this->orderRepository->get($orderId);
                    $order->setCustomerEmail($emailAddress);
                    $this->orderRepository->save($order);

                    //if update customer email
                    if ($this->accountManagement->isEmailAvailable($emailAddress)) {
                    }

                    $this->messageManager->addSuccessMessage(__('Order was successfully converted.'));

                    return $resultJson->setData(
                        [
                            'error'   => false,
                            'message' => __('Email address successfully changed.')
                        ]
                    );
                } catch(\Exception $e) {
                    return $resultJson->setData(
                        [
                            'error'   => true,
                            'message' => $e->getMessage()
                        ]
                    );
                }
            } else {
                return $resultJson->setData(
                    [
                        'error'   => true,
                        'message' => __('Invalid Email address.')
                    ]
                );
            }
        } else {
            return $resultJson->setData(
                [
                    'error'   => true,
                    'message' => __('Invalid order id.')
                ]
            );
        }
    }

    /**
     *
     */
    public function dpdTest() {

        $adminUrl = "https://api.interlinkexpress.com/user/?action=login";

        $ch = curl_init();
        $data = array(
            "username" => "crucialfitness",
            "password" => "Benchpress150"
        );
        $data_string = json_encode($data);
        $ch = curl_init($adminUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                   'Content-Type: application/json',
                   //   'Content-Length: ' . strlen($data_string)),
                   'Authorization: Basic Y3J1Y2lhbGZpdG5lc3M6QmVuY2hwcmVzczE1MA==',
                   'Accept: application/json'
               )
        );
        $token = curl_exec($ch);
        $token = json_decode($token);
        print_r($token);
        $geosession = $token->data->geoSession;

        $adminUrl = "https://api.interlinkexpress.com/shipping/network/?collectionDetails.address.locality=Birmingham&collectionDetails.address.county=West%20Midlands&collectionDetails.address.postcode=B661BY&collectionDetails.address.countryCode=GB&deliveryDetails.address.locality=Birmingham&deliveryDetails.address.county=Midlands&deliveryDetails.address.postcode=B11AA&deliveryDetails.address.countryCode=GB&deliveryDirection=1&numberOfParcels=1&totalWeight=5&shipmentType=0";
        echo "<br />" . $adminUrl;
        //GET http://<host>/rest/default/V1/products/24-MB01?fields=sku,price,name

        $ch = curl_init();
        $data = array(
            "username" => "crucialfitness",
            "password" => "Benchpress150"
        );
        $data_string = json_encode($data);
        $ch = curl_init($adminUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                   'Content-Type: application/json',
                   //   'Content-Length: ' . strlen($data_string)),
                   'GeoClient: account/2249368,',
                   'GeoSession: ' . $geosession . '',
                   'Accept: application/json'
               )
        );
        $token = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Request Error:' . curl_error($ch);
        }
        $token = json_decode($token);
        echo "<pre>";
        print_r($token);
        /*HTTP/1.1
       Host: api.interlinkexpress.com
       Accept: application/json
       GeoClient: account/123456
       GeoSession: 1234567890ABCDEFGHIJK
         */
        exit;
    }


    /**
     *
     */
    public function dpdPrint() {
        try {

            $shippingObject = $this->dpdAuthorisation;

            $request = $this->getRequest();
            $orderId = $request->getPost('order_id', NULL);
            $emailAddress = $request->getPost('email', NULL);
            $shipping_contactName = $request->getPost('shipping_contactName', NULL);
            $shipping_telephone = $request->getPost('shipping_telephone', NULL);
            $shipping_address_organisation = $request->getPost('shipping_address_organisation', NULL);
            $shipping_address_countryCode = $request->getPost('shipping_address_countryCode', NULL);
            $shipping_address_postcode = $request->getPost('shipping_address_postcode', NULL);
            $shipping_address_street = $request->getPost('shipping_address_street', NULL);
            $shipping_address_locality = $request->getPost('shipping_address_locality', NULL);
            $shipping_address_town = $request->getPost('shipping_address_town', NULL);
            $shipping_address_county = $request->getPost('shipping_address_county', NULL);
            $shipping_networkCode = $request->getPost('shipping_networkCode', NULL);
            $shipping_numberOfParcels = $request->getPost('shipping_numberOfParcels', NULL);
            $shipping_totalWeight = $request->getPost('shipping_totalWeight', NULL);
            $shipping_deliveryInstructions = $request->getPost('shipping_deliveryInstructions', NULL);


            $collectionDate = ''; //Date the courrier picks up this order
            // How are we determining this? Cut off admin Time? if after X then Y?, Days Collection Occurs?
            // 'collectionDate'       => '2019-02-20T16:00:00',

            $collectionDate = $this->getCollectionDate();


            $contactName = '';
            $contactTelephone = '';
            $collectionOrganisation = ''; //should we get this from dpd, can we exclude it?


            //create shipment

            $shippingArray = array(
                'jobId'               => NULL,
                'collectionOnDelivery' => FALSE,
                'invoice'              => NULL,
                'collectionDate'       => $collectionDate,
                'consolidate'          => FALSE,
                'consignment'          => [
                    [
                        'consignmentNumber'    => NULL,
                        'consignmentRef'       => NULL,
                        'parcel'               => [],
                        'collectionDetails'    => [
                            'contactDetails' => [
                                'contactName' => $this->senderContactName,
                                'telephone'   => $this->senderTelephone
                            ],
                            'address'        => [
                                'organisation' => $this->senderOrgName,
                                'countryCode'  => $this->senderOrgCountryCode,
                                'postcode'     => $this->senderOrgPostcode,
                                'street'       => $this->senderOrgStreet,
                                'locality'     => $this->senderOrgStreet2,
                                'town'         => $this->senderOrgTownCity,
                                'county'       => $this->senderOrgCounty
                            ]
                        ],
                        'deliveryDetails'      => [
                            'contactDetails'      => [
                                'contactName' => $shipping_contactName,
                                'telephone'   => $shipping_telephone
                            ],
                            'address'             => [
                                'organisation' => $shipping_address_organisation,
                                'countryCode'  => $shipping_address_countryCode,
                                'postcode'     => $shipping_address_postcode,
                                'street'       => $shipping_address_street,
                                'locality'     => $shipping_address_locality,
                                'town'         => $shipping_address_town,
                                'county'       => $shipping_address_county
                            ],
                            'notificationDetails' => [
                                'email'  => $emailAddress,
                                'mobile' => $shipping_telephone
                            ]
                        ],
                        'networkCode'          => $shipping_networkCode,
                        'numberOfParcels'      => intval($shipping_numberOfParcels),
                        'totalWeight'          => intval($shipping_totalWeight),
                        'shippingRef1'         => $orderId,
                        'shippingRef2'         => '',
                        'shippingRef3'         => '',
                        'customsValue'         => NULL,
                        'deliveryInstructions' => $shipping_deliveryInstructions,
                        'parcelDescription'    => NULL,
                        'liabilityValue'       => NULL,
                        'liability'            => FALSE
                    ]
                ]
            );


            //actually Works!! Needs some real checking though
            $createdLabel = $shippingObject->insertShipping($shippingArray);

            if (!empty($createdLabel['error'])) {

                foreach ($createdLabel['error'] AS $key => $val) {

                    $error[] = array(
                        'error_code'    => $val['errorCode'],
                        'error_type'    => $val['errorType'],
                        'error_message' => $val['errorMessage']
                    );

                }

                $reponse_array['error'] = 1;
                $reponse_array['error_details'] = $error;
                $json_array = json_encode($reponse_array);
                print_r($json_array);
                echo json_encode($reponse_array);
                exit;
            }

            $shipmentId = $createdLabel['data']['shipmentId'];
            $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];;
            $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];

            echo "<p><strong>Created Label</strong></p>";
            echo "<pre>";
            print_r($createdLabel);
            echo "</pre>";

            //needs the shipment id - dont know how to get from tracking
            /*
            (
             [error] =>
             [data] => Array
                 (
                     [shipmentId] => 203049939
                     [consolidated] =>
                     [consignmentDetail] => Array
                         (
                             [0] => Array
                                 (
                                     [consignmentNumber] => 6253042059
                                     [parcelNumbers] => Array
                                         (
                                             [0] => 15976675792750
                                         )

                                 )

                         )

                 )

         )
            */ //YES THIS GETS THE LABEL in HTML
            //echo $shippingObject->getLabel('203081895', 'text/html');

            $output = $shippingObject->getLabel($shipmentId, 'text/vnd.citizen-clp');

            // $output =  $shippingObject->getLabel('203081895', 'text/vnd.eltron-epl');

            echo "<p><strong>Get Label</strong></p>";
            //echo $output;


            $credentials = $this->printNodeCredentials;
            $credentials->setApiKey($this->printNodeApiKey);

            // Hint: Your API username is in the format description.integer, where description
            // is the name given to the API key when you created it, followed by a dot (.) and an integer.
            // All this information is provided for you when you create your API Key.
            // Step 3: Get a list of computers, printers or printjobs which are available.
            // To get a list of computers, printers or printjobs, create a new PrintNode\Request
            // object, passing it your credentials as the argument to it's constructor.
            $request = new \PrintNode\Request($credentials);
            // Hint: Before you can get a list of computers or printers, you must have successfully
            // connected using the PrintNode Client software. If you have not yet connected with
            // the client software you will not receive any results from the API.
            // Call the getComputers, getPrinters() or getPrintJobs() method on the object:
            $computers = $request->getComputers();
            $printers = $request->getPrinters();
            $printJobs = $request->getPrintJobs();
            // Hint: The return value from these methods is always an array containing 0 or more
            // instances of PrintNode\Computer, PrintNode\Printer or PrintNode\PrintJob depending
            // on the method called. You can iterate over this array however you please, for example
            // you might use a while or foreach loop.
            // Step 4: Send a PrintJob to Printnode.
            // PrintNode currently only accepts PDF documents.
            // To print something, you need to create a new instance of PrintNode\PrintJob:
            $printJob = new \PrintNode\PrintJob();
            // You can then populate this object with the information about the print-job
            // and add the base64-encoded content of, or the URI to your PDF. To do this use the properties
            // as defined on the object.
            //
            //  echo $printers[0];

            // In this example, we're going to print a a base64-encoded PDF named invoice.pdf:

            // Trim this! (spaces at front!)
            $printJob->printer = $this->printNodePrinterId; // 271989
            $printJob->contentType = 'raw_base64';

            //echo "CHECK";

            $printJob->content = base64_encode($output);

            echo "<br>";
            echo base64_encode($output);

            $printJob->source = 'My App/1.0';
            $printJob->title = 'Test PrintJob from My App/1.0';
            // Hint: The PrintNode PHP API comes complete with PHPDoc comments.
            // If you have an editor that supports PHPDoc code completion, you should see hints
            // for the properties and method names on each of the objects.
            // Once you have populated the object, all that's left to do is submit it:
            $response = $request->post($printJob);
            // The response returned from the post method is an instance of PrintNode\Response.
            // It contains methods for retrieving the response headers, body and HTTP status-code and message.
            // Returns the HTTP status code.
            $statusCode = $response->getStatusCode();
            echo $statusCode;
            // Returns the HTTP status message.
            echo "<br />";
            $statusMessage = $response->getStatusMessage();
            echo $statusMessage;
            // Returns an array of HTTP headers.
            $headers = $response->getHeaders();
            // Return the response body.
            $content = $response->getContent();
            print_r($content);

            exit;

            echo "<textarea>$output</textarea>";

            exit;

            //client id = 221651195451-hcpgtjt72qv0s5gfej3diab9erhvi7uo.apps.googleusercontent.com
            //client secret = _AtvHt-l5hR2LJ14GlFJiCVt

            $printerId = 'd9c95492-88a0-10d2-f408-1d89fc9704f4';
            $privateKeyPath = 'crucial.json';
            $client_email = 'crucial@crucial-cloud-print.iam.gserviceaccount.com';

            $privateKey = file_get_contents($privateKeyPath);
            $scopes = array('https://www.googleapis.com/auth/cloudprint');

            $client = new \Google_Client();
            $client->addScope("https://www.googleapis.com/auth/cloudprint");

            $client->setAuthConfig($privateKeyPath);

            $client->useApplicationDefaultCredentials();
            //$client->setAssertionCredentials($credentials);

            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithAssertion();
            }
            $accessToken = $client->getAccessToken();
            echo "ACC:";
            print_r($accessToken);

            $content = 'Any HTML body.';
            $gcp = new DPDShipment($accessToken['access_token']);

            $order = "12345";
            $tag = 'tag';
            $printerId = 'd9c95492-88a0-10d2-f408-1d89fc9704f4';

            //process invite needs to say TRUE

            /*
          $response = $gcp->processinvite(array(
              'printerid'   => $printerId,
            'accept' => 'true'
          ));

          */
            //$response = $gcp->search();
            print_r($response);
            echo "<br /><br /><br />=================<br /><br /><br />";

            $content = $output;
            $response = $gcp->submit(
                array(
                    'printerid'   => $printerId,
                    'title'       => (string)$order,
                    'content'     => $content,
                    'contentType' => 'text/html',
                    'tag'         => $tag,
                    'ticket'      => '{
        "version": "1.0",
        "print": {
            "page_orientation": { 
                "type":"PORTRAIT",
            },
            "margins":{
                "top_microns": 0,
                "right_microns": 0, 
                "bottom_microns": 0, 
                "left_microns": 0
            },
            "fit_to_page": {
                "type": "NO_FITTING"
                },
            "copies":{
                "copies":1
            },
            "dpi": {
                "vertical_dpi":300,
                "horizontal_dpi":300,
                "vendor_id":"cijns:High"
            },
            "fit_to_page": {
                "type": 1 
            },
            "media_size": {
                "height_microns":100000,
                "width_microns":100000
            },
        }
    }'

                )
            );
            if (!$response->success) {
                echo "ERROR";
                //throw new \Glavweb\GoogleCloudPrint\Exception('An error occured while printing the doc. Error code:' . $response->errorCode . ', Message:' . $response->message);
            }
            echo $response->job->id;

            exit;

            $status = new DPDParcelStatus($authorisation);

            // Retrieve the parcel's status by it's awb number
            $parcelStatus = $status->getStatus('6251465066');

            echo '<pre>';
            print_r($parcelStatus);
            echo '</pre>';

        } catch(Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Generate data for Request based on number of parcels for shipment
     *
     * @param $numberOfParcels
     *
     * @return array
     */
    public function getParcels($numberOfParcels) {
        $output = [];
        for($i = 1; $i <= $numberOfParcels; $i++) {
            $temp_array = [
                'auditInfo'     => NULL,
                'isVoided'      => false,
                'labelNumber'   => NULL,
                'packageNumber' => $i,
                'parcelNumber'  => NULL
            ];
            $output[] = $temp_array;
        }
        return $output;
    }

    /**
     * Generate Collection Date Based on Factors
     *
     * @param $numberOfParcels
     *
     * @return mixed
     */
    public function getCollectionDate() {
        $collectionDate = '';

        $currentDateTime = new \DateTime('NOW');

        $collectionDate = new \DateTime('NOW');

        $collectionTime = strtotime($this->collectionTime);

        $collectionCutOffTime = strtotime($this->collectionCutOffTime);

        $collectionDays = $this->collectionDays;

        $collectionDaysArray = explode(',',$collectionDays);

        $currentTime = $currentDateTime->format('H:i');

        $currentTimeStr = strtotime($currentTime);


        if ($currentTimeStr <= $collectionCutOffTime) {
            // Can Ship Today


        } else {
            // Next Available Day
            $collectionDate->modify("+1 day");
            $collectionDateCheck = $collectionDate->format('N');

            if (in_array($collectionDateCheck, $collectionDaysArray)) {
                // Ok this day is good
            } else {
                // All Days in date ('N') format - 0 Sunday
                $allDays = array(0,1,2,3,4,5,6);

                $nonDeliveryDays = array_diff($allDays,$collectionDays);
                $seperateholidaydates = []; //Need to Add For Bank holidays/etc
                $this->checkDateSuitability($collectionDate,$nonDeliveryDays,$seperateholidaydates);


            }

        }

        $collectDateOutput = $collectionDate->format('Y-m-d\TH:i:s');

        return $collectDateOutput;
    }

    public static function checkDateSuitability($date, $timeslot_non_deliverydays, $seperateholidaydates) {
        $date_suitable = 0;

        // Check to See if we date is suitable if we don't then lets find a day

        while($date_suitable != 1) {
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

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed() {
        return true;

        return $this->_authorization->isAllowed('Elevate_PrintLabels::elevate_printlabels');
    }
}
