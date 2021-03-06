<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Orders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
//use PrintNode\Credentials;

use \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;

/**
 * Class Info
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Info extends \Magento\Backend\App\Action {
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
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptorInterface;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Elevate\PrintLabels\Helper\Data
     */
    protected $helper;

    /**
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;

    protected $addressRepository;

    protected $orderAddressRepository;

    protected $orderAddressRepo;

    protected $orderModel;

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
     * @param \Magento\Framework\Encryption\EncryptorInterface    $encryptorInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param \Elevate\PrintLabels\Helper\Data                    $helper
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Model\Order $orderModel,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
    ) {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->accountManagement = $accountManagement;
        $this->encryptorInterface = $encryptorInterface;
        $this->scopeConfig = $scopeConfig;

        $this->addressRepository = $addressRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderModel = $orderModel;
        $this->orderAddressRepo = $orderAddressRepo;

        $this->helper = $helper;
        $this->dpdAuthorisation = $dpdAuthorisation;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute() {
        $message_return = array();

        $order_id_col_id = 0;
        $packages_col_id = 1;
        $network_code_col_id = 2;

        /*
        we need a paperwork router
        Selects which paperwork and how to print it, in which order
        can we guarantee print - if everything via printnode
        */

        $params = $this->getRequest()->getParams();
        $formdata = $this->getRequest()->getParam('formdata');

        // Loop over Form Data.
        // Split array by however many inputs per row

        $order_grid_data = array_chunk($formdata,3);

        $order_grid_data_workable = array();

        $order_ids = array();
        foreach($order_grid_data as $data) {
            // Order id
            $order_id =  $data[$order_id_col_id]['value'];
            $number_of_packages = $data[$packages_col_id]['value'];
            $network_code_id = $data[$network_code_col_id]['value'];

            $data_array = array(
                'order_id' => $order_id ,
                'number_of_packages' => intval($number_of_packages),
                'network_code_id' => (string) $network_code_id
            );

            $order_ids[] = $order_id;
            $order_grid_data_workable[$order_id] = $data_array;
            unset($data_array);
        }


        // Get Actual Order Data (don't have all of it from the form on the grid)

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                'increment_id',
                $order_ids,
                'IN'
            )->create();

        $orders = $this->orderRepository->getList($searchCriteria);

        // Lets try to ship some orders!
        $response_array = array();
        foreach($orders as $order) {
            $increment_id = $order->getIncrementId();
            $info = $this->dpdPrint($order, $order_grid_data_workable);
            $response_array[$increment_id] = $info;

        }




        //$response = json_encode($response_array);
        $response = $response_array;
        return $this->resultJsonFactory->create()->setData($response);
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
                                'contactName' => $this->helper->getSenderContactName(),
                                'telephone'   => $this->helper->getSenderTelephone()
                            ],
                            'address'        => [
                                'organisation' => $this->helper->getSenderOrgName(),
                                'countryCode'  => $this->helper->getSenderOrgCountryCode(),
                                'postcode'     => $this->helper->getSenderOrgPostcode(),
                                'street'       => $this->helper->getSenderOrgStreet(),
                                'locality'     => $this->helper->getSenderOrgStreet2(),
                                'town'         => $this->helper->getSenderOrgTownCity(),
                                'county'       => $this->helper->getSenderOrgCounty()
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
