<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Test;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Api\Data\ShipmentTrackInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;

/**
 * Class Test
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Test extends \Magento\Backend\App\Action {
  /**
   * @var \Magento\Sales\Api\OrderRepositoryInterface
   */
  protected $orderRepository;

  /**
   * @var \Magento\Sales\Api\InvoiceRepositoryInterface
   */
  protected $invoiceRepository;

  /**
   * @var \Magento\Shipping\Model\ShipmentNotifier
   */
  protected $shipmentNotifier;

  /**
   * @var \Magento\Framework\Api\SearchCriteriaBuilder
   */
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
   * @var \Magento\Sales\Api\OrderAddressRepositoryInterface
   */
  protected $orderAddressRepo;

  /**
   * @var \Magento\Sales\Model\Convert\Order
   */
  protected $orderConvertModel;

  /**
   * @var \Magento\Sales\Api\ShipmentRepositoryInterface
   */
  protected $shipmentRepository;

  /**
   * @var \Magento\Sales\Api\Data\ShipmentTrackInterface
   */
  protected $trackRepository;

  /**
   * @var \Magento\Sales\Model\Order\Pdf\Invoice
   */
  protected $invoicePdf;

  /**
   * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
   */
  protected $dpdAuthorisation;

  /**
   * @var \PrintNode\Credentials
   */
  protected $printNodeCredentials;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;



  protected $addressSplitLine;
  protected $addressTrim;
  protected $address2Trim;
  protected $nameTrim;
  protected $companyTrim;
  protected $countyTrim;
  protected $townTrim;
  protected $teleTrim;

  /**
   * Index constructor.
   *
   * @param Context                                                         $context
   * @param \Magento\Sales\Api\OrderRepositoryInterface                     $orderRepository
   * @param \Magento\Sales\Api\InvoiceRepositoryInterface                   $invoiceRepository
   * @param \Magento\Shipping\Model\ShipmentNotifier                        $shipmentNotifier
   * @param \Magento\Framework\Api\SearchCriteriaBuilder                    $searchCriteriaBuilder
   * @param \Magento\Framework\Controller\Result\JsonFactory                $resultJsonFactory
   * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
   * @param \Magento\Sales\Api\OrderAddressRepositoryInterface              $orderAddressRepo
   * @param \Magento\Sales\Model\Convert\Order                              $orderConvertModel
   * @param \Magento\Sales\Api\ShipmentRepositoryInterface                  $shipmentRepository
   * @param \Magento\Sales\Api\Data\ShipmentTrackInterface                  $trackRepository
   * @param \Magento\Sales\Model\Order\Pdf\Invoice                          $invoicePdf
   * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
   * @param \PrintNode\Credentials                                          $printNodeCredentials
   * @param \Psr\Log\LoggerInterface                                        $logger
   *
   */
  public function __construct(
    Context $context,
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository,
    \Magento\Shipping\Model\ShipmentNotifier $shipmentNotifier,
    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
    \Magento\Sales\Model\Convert\Order $orderConvertModel,
    \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
    \Magento\Sales\Api\Data\ShipmentTrackInterface $trackRepository,
    \Magento\Sales\Model\Order\Pdf\Invoice $invoicePdf,
    \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation,
    \PrintNode\Credentials $printNodeCredentials,
    \Psr\Log\LoggerInterface $logger
  ) {
    parent::__construct($context);

    $this->orderRepository = $orderRepository;
    $this->invoiceRepository = $invoiceRepository;
    $this->shipmentNotifier = $shipmentNotifier;
    $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->resultJsonFactory = $resultJsonFactory;
    $this->scopeConfig = $scopeConfig;
    $this->orderAddressRepo = $orderAddressRepo;
    $this->orderConvertModel = $orderConvertModel;
    $this->shipmentRepository = $shipmentRepository;
    $this->trackRepository = $trackRepository;
    $this->invoicePdf = $invoicePdf;
    $this->dpdAuthorisation = $dpdAuthorisation;
    $this->printNodeCredentials = $printNodeCredentials;
    $this->logger = $logger;


    $this->printNodeApiKey = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeapikey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    $this->printNodePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    $this->printNodeInvoicePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeinvoiceprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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

    $this->addressTrim = 1;
    $this->address2Trim = 1;
    $this->addressSplitLine = 1;
    $this->nameTrim = 1;
    $this->companyTrim = 1;
    $this->countyTrim = 1;
    $this->townTrim = 1;
    $this->teleTrim = 1;

  }

  /**
   * Index action
   *
   * @return \Magento\Framework\Controller\Result\Json
   * @throws \Exception
   */
  public function execute() {
    //$this->logger->info('TESTING');
    //die;
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


    // Loop Over Form Data


    if (!isset($params['datasend'])) {
      $response = array();
      $message = 'Error: All orders are marked excluded. Please uncheck one row and try again.';
      $response[0] = array(
        'no_data' => 1,
        'main_message' => $message
      );

      $this->logger->info('datasend object blank on ajax request - '.$message);
      $json_response = $this->resultJsonFactory->create();
      $json_response->setData($response);

      return $json_response;
    } else {
      $order_grid_data = $params['datasend'];
    }
    // Loop over Form Data.


    $order_grid_data_workable = array();

    $order_ids = array();
    foreach ($order_grid_data as $key => $value) {
      // Order id
      $order_id = $key;
      $number_of_packages = $value[1]['number_of_packages_'.$key];
      $network_code_id = $value[2]['shipping_networkcode_'.$key];

      $data_array = array(
        'order_id'           => $order_id,
        'number_of_packages' => intval($number_of_packages),
        'network_code_id'    => (string)$network_code_id
      );

      $order_ids[] = $order_id;
      $order_grid_data_workable[$order_id] = $data_array;
      unset($data_array);
    }

    // Get Actual Order Data (don't have all of it from the form on the grid)

    $searchCriteria = $this->searchCriteriaBuilder->addFilter(
      'increment_id', $order_ids, 'IN'
    )->create();

    $orders = $this->orderRepository->getList($searchCriteria);

    // Lets try to ship some orders!
    $response_array = array();
    /**
     * @var $order \Magento\Sales\Api\Data\OrderInterface
     */
    foreach ($orders as $order) {
      $increment_id = $order->getIncrementId();
      $info = $this->carrierLabelPrint($order, $order_grid_data_workable);
      $something = 1;
      $order->save();

      // Get/Print PDF
      //$invoice_id = $order->getInvoice

      // Should only be one invoice... but

      $invoice = $order->getInvoiceCollection();

      $pdf = $this->invoicePdf->getPdf($invoice);

      /*
      $filefactory = $this->_objectManager->create( \Magento\Framework\App\Response\Http\FileFactory::class);

      $date = $this->_objectManager->get(
          \Magento\Framework\Stdlib\DateTime\DateTime::class
      )->date('Y-m-d_H-i-s');
      $fileContent = ['type' => 'string', 'value' => $pdf->render(), 'rm' => false];

      $filefactory->create(
          'invoice' . $date . '.pdf',
          $fileContent,
          DirectoryList::VAR_DIR,
          'application/pdf'
      );

      */

      $invoice_response = $this->printInvoice($order, $invoice, $pdf);

      if (!empty($something)) {
        //where to do ship order??!
        $shipment = $this->shipOrder($order, $increment_id, $info);
        $new_info = array_merge($info, $shipment);
      } else {
        $new_info = $info;
      }

      $new_info = array_merge($new_info, array('invoice_response' => $invoice_response));


      $carrier_success = $new_info['carrier_response']['success'];
      $invoice_success = $new_info['invoice_response']['success'];
      $shipment_success = $new_info['magento_shipment']['success'];


      if (!empty($carrier_success) && !empty($invoice_success) && !empty($shipment_success)) {
        // All good
        $new_info['main_message'] = 'Created/Printed Carrier Label, Printed Invoice, and Shipped Order';
        $new_info['success'] = 1;
        $this->logger->info($increment_id.' '.$new_info['main_message']);
      } else {

        $order_message_output = 'Problem ';
        $order_message_output_array = array();
        if (!empty($carrier_success)) {
          // Carrier Issue
          $order_message_output_array[] = 'Creating/Printing Carrier Label';
        }

        if (!empty($invoice_success)) {
          /// Invoice Problem
          $order_message_output_array[] = 'Printing Invoice';

        }
        if (!empty($shipment_success)) {
          // Ship Order Problem
          $order_message_output_array[] = 'Shipping Order';
        }

        $output_message_temp = implode(' & ',$order_message_output_array);

        $order_message_output .= $output_message_temp;

        $new_info['main_message'] = $order_message_output;
        $new_info['success'] = 0;

        $this->logger->info($increment_id.' '.$order_message_output);
      }
      $new_info['order_id'] = $increment_id;
      $response_array[$increment_id] = $new_info;

    }

    $response = $response_array;
    $json_response = $this->resultJsonFactory->create();
    $json_response->setData($response);

    return $json_response;
  }

  /**
   *
   */
  public function carrierLabelPrint(
    $order,
    array $order_grid_data
  ) {
    try {

      $customer_validation_max = array(
        'shipping_customer_name' => 35,
        'shipping_telephone' => 15,
        'shipping_address_organisation' => 35,
        'shipping_address_countryCode' => 2,
        'shipping_address_postcode' => null,
        'shipping_address_street' => 35,
        'shipping_address_locality' => 35,
        'shipping_address_town' => 35,
        'shipping_address_county' => 35,
      );

      $customerdetails_validation_errors = array();
      $order_increment_id = $order->getIncrementId();
      $shipping_address = $order->getShippingAddress();

      $order_increment_id = $order->getIncrementId();

      $shipping_customer_email = $order->getShippingAddress()->getEmail();


      $shipping_firstname = $order->getShippingAddress()->getFirstname();
      $shipping_lastname = $order->getShippingAddress()->getLastname();

      // Max 35 Chars
      $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;

      if (strlen($shipping_customer_name) > $customer_validation_max['shipping_customer_name']) {
        // Too Long
        if (!empty($this->nameTrim)) {
          $shipping_firstname = $this->limitChars($shipping_firstname, 17);
          $shipping_lastname = $this->limitChars($shipping_firstname, 18);
          $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;
        } else {
          $customerdetails_validation_errors[] = "Shipping Name > ".$customer_validation_max['shipping_customer_name']." characters, please shorten First/Last Name";
        }
      }

      // Max 15 Chars
      $shipping_telephone = $order->getShippingAddress()->getTelephone();

      if (strlen($shipping_telephone) > $customer_validation_max['shipping_telephone']) {
        // Too Long
        if (!empty($this->telephoneTrim)) {
          $shipping_telephone = $this->limitChars($shipping_telephone, $customer_validation_max['shipping_telephone']);
        } else {
          $customerdetails_validation_errors[] = "Telephone Number > ".$customer_validation_max['shipping_telephone']." characters";
        }
      }

      // Max 35 Chars
      $shipping_address_organisation = $order->getShippingAddress()->getCompany();

      if (strlen($shipping_address_organisation) > $customer_validation_max['shipping_address_organisation']) {
        // Too Long
        if (!empty($this->companyTrim)) {
          $shipping_address_organisation = $this->limitChars($shipping_address_organisation, $customer_validation_max['shipping_address_organisation']);
        } else {
          $customerdetails_validation_errors[] = "Shipping Address Company > ".$customer_validation_max['shipping_address_organisation']." characters";
        }
      }


      // Max 2 Chars
      $shipping_address_countryCode = $order->getShippingAddress()->getCountryId(); //2 letter iso - e.g GB

      $shipping_address_postcode = $order->getShippingAddress()->getPostcode();

      // Max 35 Chars
      $shipping_address_street = $order->getShippingAddress()->getData('street');

      // Max 35 Chars
      $shipping_address_locality = ''; // Address Line 2 (essentially)


      if (strlen($shipping_address_street) > $customer_validation_max['shipping_address_street']) {
        // Too Long

        // is Split Line Set

        if (!empty($this->addressSplitLine)) {
          // yes

          if (!empty($this->address2Trim)) {
            $temp_address = $shipping_address_street;
            $shipping_address_street = substr($temp_address,0,35);
            $shipping_address_locality = substr($temp_address,35, 35);
          } else {
            $customerdetails_validation_errors[] = "Shipping Address Line 1 (Address), or 2 (Locality) > ".$customer_validation_max['shipping_address_locality']." characters";
          }
        } else {

          if (!empty($this->addressTrim)) {
            $shipping_address_street = $this->limitChars($shipping_address_street, $customer_validation_max['shipping_address_street']);
          } else {
            $customerdetails_validation_errors[] = "Shipping Address > ".$customer_validation_max['shipping_address_street']." characters";
          }
        }



      }


      // Max 35 Chars
      $shipping_address_town = $order->getShippingAddress()->getCity();

      if (strlen($shipping_address_town) > $customer_validation_max['shipping_address_town']) {
        // Too Long
        if (!empty($this->townTrim)) {
          $shipping_address_town = $this->limitChars($shipping_address_town, $customer_validation_max['shipping_address_town']);
        } else {
          $customerdetails_validation_errors[] = "Shipping Address Town/City > ".$customer_validation_max['shipping_address_town']." characters";
        }
      }

      // Max 35 Chars
      $shipping_address_county = $order->getShippingAddress()->getRegion();


      if (strlen($shipping_address_county) > $customer_validation_max['shipping_address_county']) {
        // Too Long
        if (!empty($this->countyTrim)) {
          $shipping_address_county = $this->limitChars($shipping_address_county, $customer_validation_max['shipping_address_county']);
        } else {
          $customerdetails_validation_errors[] = "Shipping Address County > ".$customer_validation_max['shipping_address_county']." characters";
        }
      }


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
        'jobId'                => NULL,
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
      $createdLabel = $this->dpdAuthorisation->insertShipping($shippingArray, 'application/json');
      $dpd_response_array = array();

      if (!empty($createdLabel['error'])) {

        foreach ($createdLabel['error'] AS $key => $val) {

          if (isset($val['obj'])){
            $obj = $val['obj'];
          } else {
            $obj = '';
          }

          if (isset($val['errorAction'])) {
            $err_action = $val['errorAction'];
          } else {
            $err_action = '';
          }

          $error[] = array(
            'error_code'    => $val['errorCode'],
            'error_type'    => $val['errorType'],
            'error_message' => $val['errorMessage'],
            'obj'           => $obj,
            'error_action'  => $err_action
          );

        }

        $reponse_array['error'] = 1;
        $reponse_array['error_details'] = $error;

        $dpd_response_array = array(
          'created_label_data' => NULL,
          'shipment_id'        => NULL,
          'success'            => 0,
          'error'              => 1,
          'error_details'      => $error
        );
        $this->logger->info('Carrier Error - Carrier-Response');
        $this->logger->info(print_r($dpd_response_array, true));
        $this->logger->info(print_r($error,true));

        if (is_array($error)) {
          $temp_error_string = '';
          foreach ($error as $individual_error) {
            foreach ($individual_error as $key => $value) {
              $temp_error_string .= $key.": ".$value." ";
            }
          }
          $error = $temp_error_string;
        } else {

        }

        $order->addCommentToStatusHistory('Error Registering Label with Carrier:'.$error);
      } else {

        // Created Ok
        $shipment_id = $createdLabel['data']['shipmentId'];
        $dpd_response_array = array(
          'created_label_data' => $createdLabel,
          'shipment_id'        => $shipment_id,
          'success'            => 1,
          'error'              => 0
        );
        $order->addCommentToStatusHistory('Label Registered with Carrier Succesfully');
      }

      $shipmentId = $createdLabel['data']['shipmentId'];
      $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];;
      $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];

      //YES THIS GETS THE LABEL in HTML
      //echo $shippingObject->getLabel('203081895', 'text/html');

      $dpdLabel = $this->dpdAuthorisation->getLabel($shipmentId, 'text/vnd.citizen-clp');

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

      // Trim this! (spaces at front!)
      $printJob->printer = $this->printNodePrinterId; // 271989
      $printJob->contentType = 'raw_base64';

      $printJob->content = base64_encode($dpdLabel);

      //echo "<br>";
      //echo base64_encode($dpdLabel);

      $printJob->source = 'My App/1.0';
      $printJob->title = 'DPD Label Job: ' . $order_increment_id . '/1.0';
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
      //$dpd_response_array['print_node_response'] = $print_node_response;
      if ($statusCode === 201) {
        $order->addCommentToStatusHistory('Print Job Created for Carrier Label in PrintNode');
      } else {
        $order->addCommentToStatusHistory('Print Node Problem');
      }

      $array_to_return = array(
        'carrier_response' => $dpd_response_array,
        'print_node_response' => $print_node_response
      );
      return $array_to_return;

    } catch(Exception $e) {
      echo $e->getMessage();
    }

  }

  /**
   * Limit the Number of chars of a passed String
   *
   *
   * @param $string
   * @param $length
   *
   * @return bool|string
   */
  public function limitChars($string,$length) {
    return substr($string,0,$length);
  }

  /**
   * Ship an Order
   *
   * @param $order
   * @param $order_id
   * @param $info
   *
   * @return array
   * @throws \Magento\Framework\Exception\LocalizedException
   */
  public function shipOrder(
    $order,
    $order_id,
    $info
  ) {
    $response = array();
    // Check if order can be shipped or has already shipped
    if (!$order->canShip()) {
      $response['magento_shipment'] = array(
        'error'         => 1,
        'success'       => 0,
        'error_message' => "Can't Ship Order",
        'order_id'      => $order_id
      );
    }

    // Initialize the order shipment object
    $shipment = $this->orderConvertModel->toShipment($order);


    // Loop through order items
    foreach ($order->getAllItems() AS $orderItem) {
      // Check if order item has qty to ship or is virtual
      if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
        continue;
      }

      $qtyShipped = $orderItem->getQtyToShip();

      // Create shipment item with qty
      $shipmentItem = $this->orderConvertModel->itemToShipmentItem($orderItem)->setQty($qtyShipped);

      // Add shipment item to shipment
      $shipment->addItem($shipmentItem);
    }

    // Register shipment
    $shipment->register();

    $shipment->getOrder()->setIsInProcess(true);

    try {
      // Where it is in DPD Local Anyway
      $trackingNumber = $info['carrier_response']['created_label_data']['data']['consignmentDetail'][0]['consignmentNumber'];



      // What this to be less carrier specific

      /** @var ShipmentTrackInterface $track */

      // Set this to definable Variable in Admin?
      $this->trackRepository->setNumber($trackingNumber)
        ->setTitle('DPD Local')
        ->setCarrierCode('Custom Value');

      $shipment->addTrack($this->trackRepository);
      $shipment->save();

      // Save created shipment and order
      $shipment->getOrder()->addCommentToStatusHistory('Added Carrier Tracking Number: '.$trackingNumber.' to Order Shipment');
      $shipment->getOrder()->save();

      // Send email

      // Admin Turn on/off? Option

      $this->shipmentNotifier->notify($shipment);

      $shipment->save();

      $response['magento_shipment'] = array(
        'error'         => 0,
        'success'       => 1,
        'error_message' => "Successfully Shipped Order",
        'order_id'      => $order_id
      );

    } catch(\Exception $e) {
      $response['magento_shipment'] = array(
        'error'         => 1,
        'success'       => 0,
        'error_message' => "Order Save Error?",
        'order_id'      => $order_id
      );
    }

    // Check if order can be shipped or has already shipped


    return $response;

  }


  public function printInvoice($order, $invoice, $pdf) {

    $order_increment_id = $order->getIncrementId();

    $credentials = $this->printNodeCredentials;
    $credentials->setApiKey($this->printNodeApiKey);

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
    $printJob->printer = $this->printNodeInvoicePrinterId;
    $printJob->contentType = 'pdf_base64';

    $pdf_file = $pdf->render();

    $printJob->content = base64_encode($pdf_file);



    $printJob->source = 'My App/1.0';
    $printJob->title = 'Invoice for Order -'.$order_increment_id.'/1.0';
    $print_node_response = $request->post($printJob);
    $statusCode = $print_node_response->getStatusCode();
    $statusMessage = $print_node_response->getStatusMessage();
    $headers = $print_node_response->getHeaders();
    $content = $print_node_response->getContent();

    if (intval($statusCode) === 201) {
      $response_success = 1;
      $response_message = 'Printed Invoice for Order: '.$order_increment_id;
      //$order->addCommentToStatusHistory('Print Job Created for Carrier Label in PrintNode');
    } else {
      $response_success = 0;
      $response_message = 'Print Node couldn\'t Print Invoice for Order: '.$order_increment_id;
      //$order->addCommentToStatusHistory('Print Node Problem');
    }
    $response_array = array(
      'message' => $response_message,
      'success' => $response_success
    );

    return $response_array;
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
    for ($i = 1; $i <= $numberOfParcels; $i++) {
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
   * @return string
   * @throws \Exception
   */
  public function getCollectionDate() {
    $collectionDate = '';

    $currentDateTime = new \DateTime('NOW');

    $collectionDate = new \DateTime('NOW');

    $collectionTime = strtotime($this->collectionTime);

    $collectionCutOffTime = strtotime($this->collectionCutOffTime);

    $collectionDays = $this->collectionDays;

    $collectionDaysArray = explode(',', $collectionDays);

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
        $allDays = array(
          0,
          1,
          2,
          3,
          4,
          5,
          6
        );

        $nonDeliveryDays = array_diff($allDays, $collectionDaysArray);
        $seperateholidaydates = []; //Need to Add For Bank holidays/etc
        $this->checkDateSuitability($collectionDate, $nonDeliveryDays, $seperateholidaydates);

      }

    }

    $collectDateOutput = $collectionDate->format('Y-m-d\TH:i:s');

    return $collectDateOutput;
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
