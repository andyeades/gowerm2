<?php

namespace Elevate\Delivery\Controller\Adminhtml\Changedeliveryoptions;

use DateTime;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Api\Data\ShipmentTrackInterface;
use Magento\Setup\Exception;

/**
 * Class Test
 *
 * @category Elevate
 * @package  Elevate\Delivery\Controller\Adminhtml\Changedeliveryoptions\Modifydeliveryinfo
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Modifydeliveryinfo extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order
     */
    protected $orderResourceModel;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $orderModel;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;


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
     * @var \Magento\Sales\Api\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory
     */
    protected $shipmentCollectionFactory;

    /**
     * @var \Magento\Sales\Api\Data\ShipmentTrackInterface
     */
    protected $trackRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Elevate\Framework\Helper\Data
     */
    protected $ev_helper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * Index constructor.
     *
     * @param Context                                                             $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface                         $orderRepository
     * @param \Magento\Sales\Model\OrderFactory                                   $orderFactory
     * @param \Magento\Shipping\Model\ShipmentNotifier                            $shipmentNotifier
     * @param \Magento\Framework\Api\SearchCriteriaBuilder                        $searchCriteriaBuilder
     * @param \Magento\Framework\Controller\Result\JsonFactory                    $resultJsonFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                  $scopeConfig
     * @param \Magento\Sales\Api\ShipmentRepositoryInterface                      $shipmentRepository
     * @param \Magento\Sales\Api\Data\ShipmentTrackInterface                      $trackRepository
     * @param \Elevate\Framework\Helper\Data                                      $ev_helper
     * @param \Psr\Log\LoggerInterface                                            $logger
     * @param \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                         $dateTime
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Order $orderModel,
        \Magento\Sales\Model\ResourceModel\Order $orderResourceModel,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Shipping\Model\ShipmentNotifier $shipmentNotifier,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
        \Magento\Sales\Api\Data\ShipmentTrackInterface $trackRepository,
        \Elevate\Framework\Helper\Data $ev_helper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
    ) {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->orderModel = $orderModel;
        $this->orderResourceModel = $orderResourceModel;
        $this->shipmentNotifier = $shipmentNotifier;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->shipmentRepository = $shipmentRepository;
        $this->trackRepository = $trackRepository;
        $this->ev_helper = $ev_helper;
        $this->logger = $logger;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
        $this->dateTime = $dateTime;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $formdata = $this->getRequest()->getParam('formdata');

        $data_array = [];

        foreach ($formdata as $row => $val) {
            $data_array[$val['name']] = $val['value'];
        }

        $order_id = $data_array['order_id'];
        // Remove from array for foreach below
        unset($data_array['order_id']);

        $orderModel = $this->orderFactory->create();
        /**
         * @var $order \Magento\Sales\Model\Order
         */
        $order = $orderModel->load($order_id);
       // $order_data = $order->getData();

        $order->addData($data_array);
        $response = [];
        try {
            $order->save($order);
            $response['success'] = true;

        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            $response['success'] = false;
            $response['message'] = $error_message;
        }

        $json_response = $this->resultJsonFactory->create();
        $json_response->setData($response);

        return $json_response;

    }

    /**
     *
     */
    public function registerShipmentWithCarrier(
        \Magento\Sales\Api\Data\OrderInterface $order,
        array $order_grid_data
    ) {
        try {
            // Have these copied into the admin and choosable via a select? NOT An Input!
            $customer_validation_max = [
                'shipping_customer_name'        => 35,
                'shipping_telephone'            => 15,
                'shipping_address_organisation' => 35,
                'shipping_address_countryCode'  => 2,
                'shipping_address_postcode'     => null,
                'shipping_address_street'       => 35,
                'shipping_address_locality'     => 35,
                'shipping_address_town'         => 35,
                'shipping_address_county'       => 35,
            ];

            $customerdetails_validation_errors = [];

            $shipping_address = $order->getShippingAddress();

            $order_increment_id = $order->getIncrementId();

            $shipping_customer_email = $shipping_address->getEmail();

            $shipping_firstname = $shipping_address->getFirstname();
            $shipping_lastname = $shipping_address->getLastname();

            // Max 35 Chars
            $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;

            if (strlen($shipping_customer_name) > $customer_validation_max['shipping_customer_name']) {
                // Too Long
                if (!empty($this->nameTrim)) {
                    $shipping_firstname = $this->limitChars($shipping_firstname, 17);
                    //TODO HELLOOOOO! IS THIS RIGHT?!?
                    //TODO ALERT!!
                    $shipping_lastname = $this->limitChars($shipping_firstname, 18);
                    $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;
                } else {
                    $customerdetails_validation_errors[] = "Shipping Name > " . $customer_validation_max['shipping_customer_name'] . " characters, please shorten First/Last Name";
                }
            }

            // Max 15 Chars
            $shipping_telephone = $shipping_address->getTelephone();

            if (strlen($shipping_telephone) > $customer_validation_max['shipping_telephone']) {
                // Too Long
                if (!empty($this->telephoneTrim)) {
                    $shipping_telephone = $this->limitChars($shipping_telephone, $customer_validation_max['shipping_telephone']);
                } else {
                    $customerdetails_validation_errors[] = "Telephone Number > " . $customer_validation_max['shipping_telephone'] . " characters";
                }
            }

            // Validate no stupid characters
            $shipping_telephone = preg_replace('/\D+/', '', $shipping_telephone);

            // Max 35 Chars
            $shipping_address_organisation = $shipping_address->getCompany();

            if (strlen($shipping_address_organisation) > $customer_validation_max['shipping_address_organisation']) {
                // Too Long
                if (!empty($this->companyTrim)) {
                    $shipping_address_organisation = $this->limitChars($shipping_address_organisation, $customer_validation_max['shipping_address_organisation']);
                } else {
                    $customerdetails_validation_errors[] = "Shipping Address Company > " . $customer_validation_max['shipping_address_organisation'] . " characters";
                }
            }

            // Max 2 Chars
            $shipping_address_countryCode = $shipping_address->getCountryId(); //2 letter iso - e.g GB

            $shipping_address_postcode = $shipping_address->getPostcode();

            // Max 35 Chars
            $shipping_address_street = $shipping_address->getData('street');

            // Max 35 Chars
            $shipping_address_locality = ''; // Address Line 2 (essentially)

            if (strlen($shipping_address_street) > $customer_validation_max['shipping_address_street']) {
                // Too Long

                // is Split Line Set

                if (!empty($this->printlabels_data_helper->getAddressSplitLine())) {
                    // yes

                    if (!empty($this->printlabels_data_helper->getAddressTrim2())) {
                        $temp_address = $shipping_address_street;
                        $shipping_address_street = substr($temp_address, 0, 35);
                        $shipping_address_locality = substr($temp_address, 35, 35);
                    } else {
                        $customerdetails_validation_errors[] = "Shipping Address Line 1 (Address), or 2 (Locality) > " . $customer_validation_max['shipping_address_locality'] . " characters";
                    }
                } else {
                    if (!empty($this->printlabels_data_helper->getAddressTrim())) {
                        $shipping_address_street = $this->limitChars($shipping_address_street, $customer_validation_max['shipping_address_street']);
                    } else {
                        $customerdetails_validation_errors[] = "Shipping Address > " . $customer_validation_max['shipping_address_street'] . " characters";
                    }
                }
            }

            // Max 35 Chars
            $shipping_address_town = $shipping_address->getCity();

            if (strlen($shipping_address_town) > $customer_validation_max['shipping_address_town']) {
                // Too Long
                if (!empty($this->printlabels_data_helper->getTownTrim())) {
                    $shipping_address_town = $this->limitChars($shipping_address_town, $customer_validation_max['shipping_address_town']);
                } else {
                    $customerdetails_validation_errors[] = "Shipping Address Town/City > " . $customer_validation_max['shipping_address_town'] . " characters";
                }
            }

            // Max 35 Chars
            $shipping_address_county = $shipping_address->getRegion();

            if (strlen($shipping_address_county) > $customer_validation_max['shipping_address_county']) {
                // Too Long
                if (!empty($this->printlabels_data_helper->getCountyTrim())) {
                    $shipping_address_county = $this->limitChars($shipping_address_county, $customer_validation_max['shipping_address_county']);
                } else {
                    $customerdetails_validation_errors[] = "Shipping Address County > " . $customer_validation_max['shipping_address_county'] . " characters";
                }
            }

            // Validation Required
            $shipping_networkCode = $order_grid_data[$order_increment_id]['network_code_id'];
            $shipping_numberOfParcels = $order_grid_data[$order_increment_id]['number_of_packages'];

            $shipping_totalWeight = $order->getWeight();
            $shipping_deliveryInstructions = ''; // Again What to do here? if order comment we could use it here.

            $collectionDate = ''; //Date the courier picks up this order

            // 'collectionDate'       => '2019-02-20T16:00:00',

            // Calculates if the order being printed is going on todays manifest (or the next dispatch day manifest - factoring in holiday dates/etc)

            $collectionDate = $this->getCollectionDate();

            $contactName = '';
            $contactTelephone = '';
            $collectionOrganisation = ''; //should we get this from dpd, can we exclude it?

            //create shipment

            $shippingArray = [
                'jobId'                => null,
                'collectionOnDelivery' => false,
                'invoice'              => null,
                'collectionDate'       => $collectionDate,
                'consolidate'          => false,
                'consignment'          => [
                    [
                        'consignmentNumber'    => null,
                        'consignmentRef'       => null,
                        'parcel'               => [],
                        'collectionDetails'    => [
                            'contactDetails' => [
                                'contactName' => $this->printlabels_data_helper->getSenderContactName(),
                                'telephone'   => $this->printlabels_data_helper->getSenderTelephone()
                            ],
                            'address'        => [
                                'organisation' => $this->printlabels_data_helper->getSenderOrgName(),
                                'countryCode'  => $this->printlabels_data_helper->getSenderOrgCountryCode(),
                                'postcode'     => $this->printlabels_data_helper->getSenderOrgPostcode(),
                                'street'       => $this->printlabels_data_helper->getSenderOrgStreet(),
                                'locality'     => $this->printlabels_data_helper->getSenderOrgStreet2(),
                                'town'         => $this->printlabels_data_helper->getSenderOrgTownCity(),
                                'county'       => $this->printlabels_data_helper->getSenderOrgCounty()
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
                        'totalWeight'          => floatval($shipping_totalWeight),
                        'shippingRef1'         => $order_increment_id,
                        'shippingRef2'         => '',
                        'shippingRef3'         => '',
                        'customsValue'         => null,
                        'deliveryInstructions' => $shipping_deliveryInstructions,
                        'parcelDescription'    => null,
                        'liabilityValue'       => null,
                        'liability'            => false
                    ]
                ]
            ];

            //actually Works!! Needs some real checking though
            $createdLabel = $this->dpdAuthorisation->insertShipping($shippingArray, 'application/json');
            $dpd_response_array = [];

            // If Error Lets See What the Error is!
            if (!empty($createdLabel['error'])) {
                $error = [];
                foreach ($createdLabel['error'] as $key => $val) {
                    if (isset($val['obj'])) {
                        $obj = $val['obj'];
                    } else {
                        $obj = '';
                    }

                    if (isset($val['errorAction'])) {
                        $err_action = $val['errorAction'];
                    } else {
                        $err_action = '';
                    }

                    $error[] = [
                        'error_code'    => $val['errorCode'],
                        'error_type'    => $val['errorType'],
                        'error_message' => $val['errorMessage'],
                        'obj'           => $obj,
                        'error_action'  => $err_action
                    ];
                }

                $reponse_array['error'] = 1;
                $reponse_array['error_details'] = $error;

                $dpd_response_array = [
                    'created_label_data' => null,
                    'shipment_id'        => null,
                    'success'            => 0,
                    'error'              => 1,
                    'error_details'      => $error
                ];

                $this->logger->info('Carrier Error - Carrier-Response');
                $this->logger->info(print_r($dpd_response_array, true));
                $this->logger->info(print_r($error, true));

                if (is_array($error)) {
                    $temp_error_string = '';
                    foreach ($error as $individual_error) {
                        foreach ($individual_error as $key => $value) {
                            $temp_error_string .= $key . ": " . $value . " ";
                        }
                    }
                    $error = $temp_error_string;
                }

                $order->addCommentToStatusHistory('Error Registering Label with Carrier:' . $error);
            } else {
                // Created Ok
                $shipment_id = $createdLabel['data']['shipmentId'];
                $dpd_response_array = [
                    'created_label_data' => $createdLabel,
                    'shipment_id'        => $shipment_id,
                    'success'            => 1,
                    'error'              => 0
                ];
                $order->addCommentToStatusHistory('Label Registered with Carrier Successfully');
            }

            return $dpd_response_array;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @var $order \Magento\Sales\Api\Data\OrderInterface
     */
    public function printItems(
        $order,
        $info
    ) {
        try {
            $order_id = $order->getEntityId();
            $order_increment_id = $order->getIncrementId();

            $print_items_response = [];
            $items_to_print = [
                'label'        => '1',
                'gift_message' => $this->printlabels_data_helper->getPrintGiftMessage(),
                'invoice'      => $this->printlabels_data_helper->getPrintInvoice(),
                'packing_list' => $this->printlabels_data_helper->getPrintPackingList()
            ];
            $print_items_response['configuration'] = $items_to_print;

            $createdLabel = $info['created_label_data'];

            $shipmentId = $createdLabel['data']['shipmentId'];
            $reponse_array['shipment_id'] = $createdLabel['data']['shipmentId'];

            $credentials = $this->printNodeCredentials;
            $credentials->setApiKey($this->printlabels_data_helper->getPrintNodeApiKey());

            //$credentials = new \PrintNode\Credentials\ApiKey('C7ioWJT8LLtpE2C-pVXvnvgtcoFq35wrwDMLJoTHBYE');

            $request = new \PrintNode\Request($credentials);

            // TODO - Admin Area Selector Required for Data/Type?
            $dpdLabel = $this->dpdAuthorisation->getLabel($shipmentId, 'text/vnd.citizen-clp');

            // PrintNode currently only accepts PDF documents.
            // To print something, you need to create a new instance of PrintNode\PrintJob:

            $printJob = new \PrintNode\PrintJob();

            $print_node_responses = [];

            foreach ($items_to_print as $key => $value) {
                $printJob = new \PrintNode\PrintJob();
                // If this One is set to 0 Then don't bother, goto next
                if (!empty($value)) {
                    if ($key == 'label') {
                        $printJob->printer = $this->printlabels_data_helper->getPrintNodePrinterId(); // 271989
                        $printJob->contentType = 'raw_base64';
                        $printJob->content = base64_encode($dpdLabel);
                        $printJob->source = 'DPD Label Job: ' . $order_increment_id;
                        $printJob->title = 'DPD Label Job: ' . $order_increment_id;
                    } elseif ($key == 'gift_message') {
                        // Get Gift Message (as a PDF)

                        $giftmessage = $this->printlabels_order_data_helper->getGiftMessage($order, $order->getShippingAddress())['gift_message_actual'];

                        $gift_message_css_styles = $this->printlabels_data_helper->getGiftmessagepdfcssstyles();

                        $print_multiple = $this->printlabels_data_helper->getPrintgiftmessagelabelperitem();

                        $gift_message_css_styles = '<style>' . $gift_message_css_styles . '</style>';

                        if (!empty($print_multiple)) {
                            $orderitems_totalqty = $order->getTotalItemCount();
                            $orderitems = $order->getItems();

                            $number_of_pages_to_print = $orderitems_totalqty;
                        } else {
                            $number_of_pages_to_print = 1;
                        }

                        // Create PDF
                        $giftmessage_pdf = $this->createGiftMessagePdf($order_increment_id, $giftmessage, $gift_message_css_styles, $number_of_pages_to_print);

                        $giftmessage_output = $giftmessage_pdf;

                        $printJob->printer = $this->printlabels_data_helper->getPrintNodeGiftmessagePrinterId();
                        $printJob->contentType = 'pdf_base64';
                        $printJob->content = base64_encode($giftmessage_output);
                        $printJob->source = 'Gift Message For ' . $order_increment_id;
                        $printJob->title = 'Gift Message For ' . $order_increment_id;
                    } elseif ($key == 'packing_list') {
                        // Get Packing List as a PDF

                        $packinglist = '';
                        // Need the Model not hte interface... going to have to factory it I guess?

                        // You can get the factory from the rrepo?
                        //https://www.digitalsix.co.uk/in-your-face-interfaces/

                        // Need the model to pass the damn thing to getPDF (Annoying!!)

                        $shipmentsCollection = $this->shipmentCollectionFactory->create()->setOrderFilter($order->getEntityId());

                        if ($shipmentsCollection->getSize()) {
                            if ($shipmentsCollection->count() == 1) {
                                $order_shipment = $shipmentsCollection->getFirstItem();
                                /**
                                 * @var $packinglist \Zend_Pdf
                                 */
                                $packinglist = $this->salesModelOrderPdfShipment->getPdf([$order_shipment]);

                                // Modify With What we Want To Add

                                $packinglist_output = $packinglist->render();
                                $printJob->printer = $this->printlabels_data_helper->getPrintNodePackingListPrinterId();
                                $printJob->contentType = 'pdf_base64';
                                $printJob->content = base64_encode($packinglist_output);
                                $printJob->source = 'Packing List For: ' . $order_increment_id;
                                $printJob->title = 'Packing List For: ' . $order_increment_id;
                            } else {
                                // More than one? Need to figure out what to do here in future if client is going to use functionality
                                continue;
                            }
                        } else {
                            continue;
                        }
                    } elseif ($key == 'invoice') {
                        // Get Invoice as a PDF

                        $invoice = '';

                        //Would be same as above

                        $printJob->printer = $this->printlabels_data_helper->getPrintNodeInvoicePrinterId();
                        $printJob->contentType = 'pdf_base64';
                        $printJob->content = base64_encode($invoice);
                        $printJob->source = 'My App/1.0';
                        $printJob->title = 'Invoice For: ' . $order_increment_id . '/1.0';
                    }
                    // Submit and get Response
                    $print_node_response = $request->post($printJob);
                    // The response returned from the post method is an instance of PrintNode\Response.
                    // It contains methods for retrieving the response headers, body and HTTP status-code and message.
                    // Returns the HTTP status code.
                    $statusCode = $print_node_response->getStatusCode();
                    $statusMessage = $print_node_response->getStatusMessage();
                    // Returns an array of HTTP headers.
                    $headers = $print_node_response->getHeaders();
                    // Return the response body.
                    $content = $print_node_response->getContent();

                    $print_node_responses[$key]['print_node_response'] = $print_node_response;

                    if ($statusCode === 201) {
                        $print_node_responses[$key]['success'] = 1;
                        $order->addCommentToStatusHistory('Print Job Created for ' . $key . ' in Print Node');
                    } else {
                        $print_node_responses[$key]['success'] = 0;
                        $order->addCommentToStatusHistory('Print Node Error while attempting to print ' . $key . '');
                    }
                }
            }

            $print_items_response['print_node_responses'] = $print_node_responses;

            return $print_items_response;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *  NOT USED - Previous TEST
     *  Create Gift Message PDF For Printing
     */

    public function createGiftMessagePdfZend($gift_message): \Zend_Pdf
    {

        // Gift Message Size is 102.047 x 212.598 POINTS (1/72 of an inch) or
        // 36x75mm

        $pdf = new \Zend_Pdf();
        $pdf->pages[] = $pdf->newPage(102.047, 212.598);
        $page = $pdf->pages[0]; // this will get reference to the first page.
        $style = new \Zend_Pdf_Style();
        $style->setLineColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $style->setFont($font, 12);
        $page->setStyle($style);
        $width = $page->getWidth();
        $height = $page->getHeight();
        $x = 10;
        $this->y = 200;

        $charsperline = 50;

        $gift_message_split = str_split($gift_message, 40);

        $style->setFont($font, 14);
        $page->setStyle($style);

        foreach ($gift_message_split as $gift_message_line) {
            $page->drawText($gift_message_line, $x, $this->y - 30, 'UTF-8');
        }

        return $pdf;
    }

    /**
     * Create Gift Message PDF
     *
     * Number of Pages allows the number of the same gift message labels to be printed - if labels are attached to all items on the order for instance.
     *
     * @param int    $order_increment_id
     * @param string $gift_message
     * @param string $gift_message_css_styles
     * @param int    $number_of_pages
     *
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function createGiftMessagePdf(int $order_increment_id, string $gift_message, string $gift_message_css_styles, int $number_of_pages)
    {

        // Gift Message Size is 102.047 x 212.598 POINTS (1/72 of an inch) or
        // 36x75mm

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $gift_message_header_enabled = 1;
        $gift_message_footer_enabled = 0;
        $gift_message_css_styles_enabled = 1;

        $gift_message_header_content = '<header><div class="header-right">' . $order_increment_id . '</div></header>';
        $gift_message_footer_content = '';

        $mpdf = new \Mpdf\Mpdf(
            ['mode' => 'utf-8',
             'format' => [75,36],
             'margin_top' => 6,
             'margin_left' => 2,
             'margin_right' => 2,
             'margin_bottom' => 2,
             'margin_header' => 1.5,
             'margin_footer' => 0,
             'orientation' => 'P',
             'fontDir' => array_merge($fontDirs, [
                 dirname(__DIR__, 3) . '/view/adminhtml/web/fonts',
             ]),
             'fontdata' => $fontData + [
                     'bookmanjfpro' => [
                         'R' => 'BookmanJFPro-Roman.ttf',
                         'I' => 'BookmanJFPro-Roman.ttf',
                     ]
                 ],
             'default_font' => 'bookmanjfpro'
            ]
        );

        //gift_message = "<p>Font Size 12px Test.Testing the gift message label again, I'm filling this upto the maximum number of allowed characters... I think.</p>";

        //$gift_message = "<p>Font Size 12px Test.Testing the gift message label again, I'm filling this upto the maximum number of allowed characters... I think. This should be about 170 characters. The end of this fullstop is more like 230 chars or so.</p>";

        $internal_gift_message = '';

        if (!empty($gift_message_css_styles_enabled)) {
            $internal_gift_message = $gift_message_css_styles;
        }

        if (!empty($gift_message_header_enabled)) {
            $mpdf->SetHtmlHeader($gift_message_header_content);
        }

        $gift_message = $internal_gift_message . $gift_message;

        if (!empty($gift_message_footer_enabled)) {
            $gift_message .= "<footer>$gift_message_footer_content</footer>";
        }

        $mpdf->WriteHTML($gift_message);
        if ($number_of_pages >= 2) {
            for ($i = 1; $i < $number_of_pages; $i++) {
                $mpdf->AddPage();
                $mpdf->WriteHTML($gift_message);
            }
        }

        // Write some HTML code:

        // Output a PDF file directly to the browser
        return $mpdf->Output('headertest1.pdf', \Mpdf\Output\Destination::FILE);
        return $mpdf->Output('test.pdf', \Mpdf\Output\Destination::STRING_RETURN);
        //return $mpdf->Output('',\Mpdf\Output\Destination::STRING_RETURN);
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
    public function limitChars(
        $string,
        $length
    ) {
        return substr($string, 0, $length);
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
        $response = [];
        // Check if order can be shipped or has already shipped
        if (!$order->canShip()) {
            $response['magento_shipment'] = [
                'error'         => 1,
                'success'       => 0,
                'error_message' => "Can't Ship Order",
                'order_id'      => $order_id
            ];
        }

        // Initialize the order shipment object
        $shipment = $this->orderConvertModel->toShipment($order);

        // Loop through order items
        foreach ($order->getAllItems() as $orderItem) {
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
            $trackingNumber = $info['created_label_data']['data']['consignmentDetail'][0]['consignmentNumber'];

            // What this to be less carrier specific

            /** @var ShipmentTrackInterface $track */

            // Set this to definable Variable in Admin?
            $this->trackRepository->setNumber($trackingNumber)->setTitle('DPD Local')->setCarrierCode('Custom Value');

            $shipment->addTrack($this->trackRepository);
            $shipment->save();

            // Save created shipment and order
            $shipment->getOrder()->addCommentToStatusHistory('Added Carrier Tracking Number: ' . $trackingNumber . ' to Order Shipment');
            $shipment->getOrder()->save();

            // Send email

            // Admin Turn on/off? Option

            $this->shipmentNotifier->notify($shipment);

            $shipment->save();

            $response['magento_shipment'] = [
                'error'         => 0,
                'success'       => 1,
                'error_message' => "Successfully Shipped Order",
                'order_id'      => $order_id
            ];
        } catch (\Exception $e) {
            $response['magento_shipment'] = [
                'error'         => 1,
                'success'       => 0,
                'error_message' => "Order Save Error?",
                'order_id'      => $order_id
            ];
        }

        // Check if order can be shipped or has already shipped

        return $response;
    }

    public function printInvoice(
        $order,
        $invoice,
        $pdf
    ) {
        $order_increment_id = $order->getIncrementId();

        $credentials = new \PrintNode\Credentials\ApiKey($this->printNodeApiKey);

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
        $printJob->title = 'Invoice for Order -' . $order_increment_id . '/1.0';
        $print_node_response = $request->post($printJob);
        $statusCode = $print_node_response->getStatusCode();
        $statusMessage = $print_node_response->getStatusMessage();
        $headers = $print_node_response->getHeaders();
        $content = $print_node_response->getContent();

        if (intval($statusCode) === 201) {
            $response_success = 1;
            $response_message = 'Printed Invoice for Order: ' . $order_increment_id;
        //$order->addCommentToStatusHistory('Print Job Created for Carrier Label in PrintNode');
        } else {
            $response_success = 0;
            $response_message = 'Print Node couldn\'t Print Invoice for Order: ' . $order_increment_id;
            //$order->addCommentToStatusHistory('Print Node Problem');
        }
        $response_array = [
            'message' => $response_message,
            'success' => $response_success
        ];

        return $response_array;
    }

    /**
     * Generate data for Request based on number of parcels for shipment
     *
     * @param $numberOfParcels
     *
     * @return array
     */
    public function getParcels($numberOfParcels)
    {
        $output = [];
        for ($i = 1; $i <= $numberOfParcels; $i++) {
            $temp_array = [
                'auditInfo'     => null,
                'isVoided'      => false,
                'labelNumber'   => null,
                'packageNumber' => $i,
                'parcelNumber'  => null
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
    protected function getCollectionDate()
    {
        $collectionDate = '';

        $currentDateTime = new \DateTime('NOW');

        $collectionDate = new \DateTime('NOW');

        $collectionTime = strtotime($this->printlabels_data_helper->getCollectionTime());

        $collectionCutOffTime = strtotime($this->printlabels_data_helper->getCollectionCutOffTime());

        $collectionDays = $this->printlabels_data_helper->getCollectionDays();

        $collectionDaysArray = explode(',', $collectionDays);

        $currentTime = $currentDateTime->format('H:i');

        $currentTimeStr = strtotime($currentTime);

        if ($currentTimeStr > $collectionCutOffTime) {
            // Not Sending today, so it will need to find the next available working day to add the orders to that manifest

            // Next Available Day
            $collectionDate->modify("+1 day");
            $collectionDateCheck = $collectionDate->format('N');

            if (in_array($collectionDateCheck, $collectionDaysArray)) {
                // Ok this day is good
            } else {
                // All Days in date ('N') format - 0 Sunday
                $allDays = [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6
                ];

                $nonDeliveryDays = array_diff($allDays, $collectionDaysArray);

                // Array of Dates that won't be usable for the next collection date/etc
                // TODO
                $seperateholidaydates = $this->getHolidayDates();

                $this->printlabels_data_helper->checkDateSuitability($collectionDate, $nonDeliveryDays, $seperateholidaydates);
            }
        }

        $collectDateOutput = $collectionDate->format('Y-m-d\TH:i:s');

        return $collectDateOutput;
    }

    protected function getHolidayDates()
    {
        $date = new DateTime('NOW');
        //$date = new DateTime('2020-08-10 09:30');
        // Get Holiday Dates

        $date_string = $date->format('Y-m-d');

        $filters = [
            [
                'field'          => 'end_date',
                'value'          => $date_string,
                'condition_type' => 'gteq',
            ]
        ];

        $sortorder = [
            'field'     => 'start_date',
            'direction' => 'DESC'
        ];

        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);
        $holiday_dates = $this->holidayDates->getList($searchCriteria);
        $holiday_dates = $holiday_dates->getItems();

        $holiday_dates_array = [];
        $holiday_dates_individual = [];

        foreach ($holiday_dates as $holiday_date) {
            $holiday_dates_data = $holiday_date->getAllData();
            $holiday_date_start = $holiday_date->getStartDate();
            $holiday_date_end = $holiday_date->getEndDate();

            $holiday_dates_array[] = $holiday_dates_data;

            $holiday_dates = $this->printlabels_data_helper->createDateRange($holiday_date_start, $holiday_date_end);
            $holiday_dates_individual = array_merge($holiday_dates_individual, $holiday_dates);
        }

        $seperateholidaydates = array_unique($holiday_dates_individual);

        return $seperateholidaydates;
    }

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;

        return $this->_authorization->isAllowed('Elevate_PrintLabels::elevate_printlabels');
    }
}
