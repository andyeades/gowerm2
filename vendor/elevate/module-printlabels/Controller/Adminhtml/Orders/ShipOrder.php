<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Orders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
//use PrintNode\Credentials;

use \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;

/**
 * Class ShipOrder
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class ShipOrder extends \Magento\Backend\App\Action {
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

    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;



    /**
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;

    protected $addressRepository;

    protected $orderAddressRepository;

    protected $orderAddressRepo;

    protected $orderModel;

    protected $orderConvertModel;

    protected $shipmentRepository;

    protected $shipmentFactory;

    protected $orderClass;

    /**
     * @var \Magento\GiftMessage\Api\OrderRepositoryInterface
     */
    protected $giftMessageRepository;

    /**
     * Index constructor.
     *
     * @param Context                                             $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface         $orderRepository
     * @param \Magento\Customer\Api\AccountManagementInterface    $accountManagement
     * @param \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService
     * @param \Magento\Framework\Api\SearchCriteriaBuilder        $searchCriteriaBuilder
     * @param \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory
     * @param \Magento\Customer\Api\AddressRepositoryInterface           $addressRepository
     * @param \Magento\Sales\Model\Order\AddressRepository               $orderAddressRepository
     * @param \Magento\Sales\Api\OrderAddressRepositoryInterface         $orderAddressRepo
     * @param \Magento\Sales\Model\Order                                 $orderModel
     * @param \Magento\Sales\Model\Convert\Order                         $orderConvertModel
     * @param \Magento\Sales\Api\ShipmentRepositoryInterface      $shipmentRepository
     * @param \Magento\Sales\Model\Order\ShipmentFactory          $shipmentFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param \Magento\GiftMessage\Api\OrderRepositoryInterface   $giftMessageRepository
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Orders\GetOrders $orderClass
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
     */
    public function __construct(
        Context $context,

        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Sales\Model\Convert\Order                         $orderConvertModel,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
        \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation,
        \Elevate\PrintLabels\Controller\Adminhtml\Orders\GetOrders $orderClass,
        \Magento\GiftMessage\Api\OrderRepositoryInterface   $giftMessageRepository
    ) {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->accountManagement = $accountManagement;
        $this->scopeConfig = $scopeConfig;

        $this->dpdAuthorisation = $dpdAuthorisation;
        $this->addressRepository = $addressRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderModel = $orderModel;
        $this->orderConvertModel = $orderConvertModel;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentFactory = $shipmentFactory;
        $this->orderClass = $orderClass;



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

        // Try and Ship The Order

        $order_id = $this->getRequest()->getParam('order_id');

        // Load the order increment ID
        $order = $this->orderModel->loadByIncrementID($order_id);

        $response = array();

        // Check if order can be shipped or has already shipped
        if (! $order->canShip()) {

            // Send Json back instead of this


            $response = array(
              'error' => 1,
              'success' => 0,
              'error_message' => "Can't Ship Order",
              'order_id' => $order_id
            );

        } else {

            // can Ship

            $shipment = $this->shipmentFactory->create($order);

            // Save Shipment
            $this->shipmentRepository->save($shipment);

            $response = array(
                'error' => 0,
                'success' => 1,
                'error_message' => "Successfully Shipped Order",
                'order_id' => $order_id
            );
        }



        return $this->resultJsonFactory->create()->setData($response);
    }
    public function getOrders() {
        return $this->orderClass->getOrders();
    }
    /**
     *
     */
    public function dpdPrint($order, array $order_grid_data) {
        try {




            $order_increment_id = $order->getIncrementId();
            $shipping_address = $order->getShippingAddress();

            $order_increment_id = $order->getIncrementId();

            $shipping_customer_email = $order->getShippingAddress()->getEmail();
            $shipping_firstname = $order->getShippingAddress()->getFirstname();
            $shipping_lastname = $order->getShippingAddress()->getLastname();
            $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;
            $shipping_telephone = $order->getShippingAddress()->getTelephone();


            $shipping_address_organisation = $order->getShippingAddress()->getCompany();
            $shipping_address_countryCode = $order->getShippingAddress()->getCountryId(); //2 letter iso - e.g GB
            $shipping_address_postcode = $order->getShippingAddress()->getPostcode();
            $shipping_address_street = $order->getShippingAddress()->getData('street');


            $shipping_address_locality = ''; // What to do here?  Split Street?

            $shipping_address_town = $order->getShippingAddress()->getCity();
            $shipping_address_county = $order->getShippingAddress()->getRegion();

            // Validation Required
            $shipping_networkCode = $order_grid_data[$order_increment_id]['network_code_id'];
            $shipping_numberOfParcels = $order_grid_data[$order_increment_id]['number_of_packages'];

            $shipping_totalWeight = $order->getWeight();
            $shipping_deliveryInstructions = // Again What to do here?


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
                                'contactName' => $shipping_customer_name,
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
                                'email'  => $shipping_customer_email,
                                'mobile' => $shipping_telephone
                            ]
                        ],
                        'networkCode'          => $shipping_networkCode,
                        'numberOfParcels'      => intval($shipping_numberOfParcels),
                        'totalWeight'          => $shipping_totalWeight,
                        'shippingRef1'         => $order_increment_id,
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
            $createdLabel = $this->dpdAuthorisation->insertShipping($shippingArray,'application/json');
            $dpd_response_array = array();

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
                //print_r($json_array);
                //echo json_encode($reponse_array);
                //exit;
            } else {
                // Created Ok
                $shipment_id = $createdLabel['data']['shipmentId'];
                $dpd_response_array = array(
                    'created_label_data' => $createdLabel,
                    'shipment_id' => $shipment_id,
                    'success' => '1'
                );
            }

            $shipmentId = $createdLabel['data']['shipmentId'];
            $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];;
            $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];


            //YES THIS GETS THE LABEL in HTML
            //echo $shippingObject->getLabel('203081895', 'text/html');

            $dpdLabel = $this->dpdAuthorisation->getLabel($shipmentId, 'text/vnd.citizen-clp');

            $credentials = new \PrintNode\Credentials\ApiKey($this->printNodeApiKey);

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

            // Trim this! (spaces at front!)
            $printJob->printer = $this->printNodePrinterId; // 271989
            $printJob->contentType = 'raw_base64';

            $printJob->content = base64_encode($dpdLabel);

            //echo "<br>";
            //echo base64_encode($dpdLabel);

            $printJob->source = 'My App/1.0';
            $printJob->title = 'DPD Label Job: '.$order_increment_id.'/1.0';
            // Hint: The PrintNode PHP API comes complete with PHPDoc comments.
            // If you have an editor that supports PHPDoc code completion, you should see hints
            // for the properties and method names on each of the objects.
            // Once you have populated the object, all that's left to do is submit it:
            $print_node_response = $request->post($printJob);
            // The response returned from the post method is an instance of PrintNode\Response.
            // It contains methods for retrieving the response headers, body and HTTP status-code and message.
            // Returns the HTTP status code.
            $statusCode = $print_node_response->getStatusCode();
            //echo $statusCode;
            // Returns the HTTP status message.
            //echo "<br />";
            $statusMessage = $print_node_response->getStatusMessage();
           // echo $statusMessage;
            // Returns an array of HTTP headers.
            $headers = $print_node_response->getHeaders();
            // Return the response body.
            $content = $print_node_response->getContent();
            //print_r($content);

            $order_response = array(
                "order_id" => $order_increment_id,
                "success" => '1',
                "print_node_response" => $print_node_response,
                "dpd_response" => $dpd_response_array,
            );

            return $order_response;

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
