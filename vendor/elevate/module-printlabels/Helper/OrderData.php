<?php

namespace Elevate\PrintLabels\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class OrderData
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Helper
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class OrderData extends AbstractHelper
{

    /**
     * @var \Elevate\Framework\Helper\Data
     */
    protected $ev_helper;

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
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var \Magento\Sales\Model\Order\AddressRepository
     */
    protected $orderAddressRepository;

    /**
     * @var \Magento\Sales\Api\OrderAddressRepositoryInterface
     */
    protected $orderAddressRepo;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $orderModel;

    /**
     * @var \Magento\GiftMessage\Api\OrderRepositoryInterface
     */
    protected $giftMessageRepository;

    /**
     * @var \Elevate\PrintLabels\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $backendUrl;

    /**
     * @var
     */
    protected $validpostcodes;
    /**
     * Data constructor.
     *
     * @param Context                                                         $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
     * @param \Magento\Sales\Api\OrderRepositoryInterface                     $orderRepository
     * @param \Magento\Customer\Api\AccountManagementInterface                $accountManagement
     * @param \Magento\Sales\Api\OrderCustomerManagementInterface             $orderCustomerService
     * @param \Magento\Framework\Controller\Result\JsonFactory                $resultJsonFactory
     * @param \Magento\Customer\Api\AddressRepositoryInterface                $addressRepository
     * @param \Magento\Sales\Model\Order\AddressRepository                    $orderAddressRepository
     * @param \Magento\Sales\Api\OrderAddressRepositoryInterface              $orderAddressRepo
     * @param \Magento\Sales\Model\Order                                      $orderModel
     * @param \Magento\GiftMessage\Api\OrderRepositoryInterface               $giftMessageRepository
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
     * @param \Elevate\PrintLabels\Helper\Data                                $helper
     * @param \Elevate\Framework\Helper\Data                                  $ev_helper
     * @param \Magento\Backend\Model\UrlInterface                             $backendUrl
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Model\Order $orderModel,
        \Magento\GiftMessage\Api\OrderRepositoryInterface $giftMessageRepository,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation,
        \Elevate\Framework\Helper\Data $ev_helper,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->accountManagement = $accountManagement;
        $this->scopeConfig = $scopeConfig;
        $this->addressRepository = $addressRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->orderModel = $orderModel;
        $this->giftMessageRepository = $giftMessageRepository;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->dpdAuthorisation = $dpdAuthorisation;
        $this->ev_helper = $ev_helper;
        $this->helper = $helper;
        $this->backendUrl = $backendUrl;
    }

    /**
     * Get Orders (Collection paged)
     *
     * @param int $pagenumber
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrdersByPage(int $pagenumber)
    {
        // We need to limit the number of orders which are going to be sent to the DPD API to be checked for the delivery options -> 100 Seems to be about its limit.

        // Counts to Send...

        // Total Number of orders "Processing" - regardless of delivery date/etc
        // Total Number of orders For the next Delivery Date.
        // Total Number of orders For the next Weeks Delivery Dates?

        // Whenever the System was Started to be used - Going to Need something Better longer term
        $date = (new \DateTime("2020-12-1"));

        // Make Admin Selectable?
        $filters = [
            [
                'field' => 'status',
                'value' => 'processing'
            ],
            [
                'field' => 'created_at',
                'value' => $date->format('Y-m-d'),
                'condition_type' => 'gt'
            ]
        ];

        // What If Not Using This Field?
        $sortorder = [
            'field' => 'created_at',
            'direction' => 'DESC',
        ];

        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);

        /*
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            'status',
            'processing',
            'eq'
        )->addFilter(
            'created_at',

            'gt'
        )->create();
        */

        $order_counts = [];

        $orders_precount = $this->orderRepository->getList($searchCriteria);

        $order_counts['total_processing'] = $orders_precount->getTotalCount();

        $pageSize = 90;

        $searchCriteria->setPageSize($pageSize)->setCurrentPage($pagenumber);

        $orders = $this->orderRepository->getList($searchCriteria);

        // Ok Depends on How we are showing them though -
        // Yes, if we're showing all processing orders regardless of date, but if we are showing a specfic date then
        // well either way it would be on the toatl processing number as it would show the total processing for day
        // x if we passed that into the filter above

        $output = '';
        $problem_orders_html = '';
        $problem_orders_count = 0;
        $problem_orders = [];

        $alternative_orders_html = '';
        $alternative_orders_count = 0;
        $alternative_orders = [];

        $order_data = [];

        //echo $order_counts['total_processing'];
        //die();
        $alternative_shipping_postcodes = $this->helper->getAlternativedeloptionPostcodes();
        //
        foreach ($orders->getItems() as $order) {
            $order_id = $order->getIncrementId();

            $this->_logger->info($order_id . ' - ' . strtoupper($order->getShippingAddress()->getPostcode()));

            $shippingAddress = $order->getShippingAddress();

            $billingAddress = $order->getBillingAddress();

            // Gift Message

            $gift_message = $this->getGiftMessage($order, $shippingAddress);

            // Build Order Data For View Order Popup

            $temp_order_data = $this->BuildOrderPopupData($order, $shippingAddress, $billingAddress, $gift_message);

            $order_data[$order_id] = $temp_order_data['order_data'];
            $order_data_array = $temp_order_data['order_data_array'];

            $shipping_firstname = rtrim($shippingAddress->getFirstname());
            $shipping_lastname = rtrim($shippingAddress->getLastname());
            $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;
            $shipping_telephone_tmp = rtrim($shippingAddress->getTelephone());
            $shipping_customer_email = rtrim($shippingAddress->getEmail());
            $shipping_postcode_pre = rtrim($shippingAddress->getPostcode());

            $shipping_postcode = strtoupper($shipping_postcode_pre);
            // Need to check if this is an issue...

            $shipping_city = rtrim($shippingAddress->getCity());
            $shipping_region = rtrim($shippingAddress->getRegion());
            $shipping_address_tmp = rtrim($shippingAddress->getData('street'));
            $shipping_company = rtrim($shippingAddress->getCompany());

            // Fudge This For Now!
            $shipping_country_code = $shippingAddress->getCountryId(); //2 letter iso - e.g US

            //$shipping_country_code = "GB";
            $is_country_gb = false;

            if (strcmp($shipping_country_code, 'GB') === 0) {
                $is_country_gb = true;
            }

            //$shipping_country_code = "GB";
            // Rounding to 2 decimals because otherwise empty doesn't find 0.0000 as empty!
            $shipping_weight = round($order->getWeight(), 2);

            $packageWeightLimit = $this->helper->getPackageWeightLimit();
            // Setting this because it was causing major issues
            $sender_country_code = "GB";
            $numberOfParcels = 1;

            if ($shipping_weight < 1) {
                $shipping_weight = 1;
            }
            if ($numberOfParcels < 1) {
                $numberOfParcels = 1;
            }

            // What if Sender Street 2/County/etc is empty?!

            $dataArray = [
                'collectionDetails' => [
                    'address' => [
                        'locality'    => $this->helper->getSenderOrgStreet2(),
                        'county'      => $this->helper->getSenderOrgCounty(),
                        'postcode'    => $this->helper->getSenderOrgPostcode(),
                        'countryCode' => $sender_country_code
                    ],
                ],
                'deliveryDetails'   => [
                    'address' => [
                        'locality'    => '',
                        'postcode'    => $shipping_postcode,
                        'countryCode' => $shipping_country_code
                    ],
                ],
                'deliveryDirection' => 1,
                'numberOfParcels'   => $numberOfParcels,
                'totalWeight'       => $shipping_weight,
                'shipmentType'      => 0
            ];

            $problem_order_flag = false;

            //gets the available services

            $error_code = [];

            if (empty($shipping_weight)) {
                $error_code[] = 100;
            }

            if (empty($this->checkOrderPostcode($shipping_country_code, $shipping_postcode))) {
                $error_code[] = 200;
            }

            $error_message = $this->getErrorMessage($error_code);
            unset($error_code);

            if (!empty($this->checkOrderPostcode($shipping_country_code, $shipping_postcode)) && (!empty($shipping_weight))) {
                $delivery_date_on = 1;

                if ($delivery_date_on === 1) {
                    // if Using Detailed Delivery Date
                    // This will Work if it is a single date - but if it is a range we'll have to come up with a solution down the line

                    //$delivery_date_raw = $order->getDetailedDeliveryInfoDates();
                    $delivery_date_raw = $order->getDeliveryDateSelected();

                    if (empty(strtotime($delivery_date_raw))) {
                        // either blank or 1970-01-01 - which returns 0!
                        $error_code[] = 300;
                        $error_message .= $this->getErrorMessage($error_code);

                        $problem_order_flag = true;
                        unset($error_code);
                    }
                }

                // Postcode is Valid and a Shipping Weight is set on Package
                // TODO: What when the Weight is REALLY BIG (PAS Situation 150kg)

                // Do I Strip The Letters out of the PackageWeightLimit

                if ($shipping_weight >= $packageWeightLimit) {
                    // Divide by the package weight limit and round up to next int
                    $dataArray['numberOfParcels'] = ceil($shipping_weight / $packageWeightLimit);
                }

                // Check if Order Postcode is going to match any of the postcodes
                // specified in the alternative postcode thingy

                $is_postcode_in_altenativepostcodes = false;

                if (!empty($is_country_gb)) {
                    // Only Check The Postcode if GB as this is for the alternative shipping method check
                    $is_postcode_in_altenativepostcodes = $this->checkPostcodeAgaisntSet($shipping_postcode, $alternative_shipping_postcodes);
                }

                if (empty($is_postcode_in_altenativepostcodes)) {
                    $shipping_array = $this->dpdAuthorisation->getShipping($dataArray);
                    if (!is_array($shipping_array)) {
                        // Catching "Error Connecting to API Error" - Rate Limited

                        if ($shipping_array == 'error connecting to API - ') {
                            // Shouldn't happen now.
                            $this->_logger->info('Error Connecting to API');
                            $this->_logger->info(print_r($dataArray, true));

                            if (empty($error_message)) {
                                $error_message = 'Error connecting to API';
                            } else {
                                $error_message .= 'Error connecting to API';
                            }

                            $problem_order_flag = true;
                        }
                    }

                    if (empty($problem_order_flag)) {
                        $output .= $this->getTableRow("Valid Order", $order, $shipping_array, $order_data_array, $gift_message, $billingAddress, $shippingAddress);
                    } else {

                        // Add to Problem Order
                        $problem_orders[] = $order->getIncrementId();

                        $problem_orders_html .= $this->getTableRow("Problem Order", $order, $shipping_array, $order_data_array, $gift_message, $billingAddress, $shippingAddress, $error_message);
                        unset($error_message);
                        continue;
                    }
                } else {
                    // Postcode is an alternative postcode!
                    $alternative_orders[] = $order->getIncrementId();
                    $alternative_orders_html .= $this->getTableRow("Alternative Postcode Order", $order, '', $order_data_array, $gift_message, $billingAddress, $shippingAddress, '');
                }
            } else {
                $problem_orders[] = $order->getIncrementId();

                $problem_orders_html .= $this->getTableRow("Problem Order", $order, '', $order_data_array, $gift_message, $billingAddress, $shippingAddress, $error_message);
                // Postcode is empty or something is wrong

                unset($error_message);
            }
        }

        $alternative_orders_count = count($alternative_orders);
        $problem_orders_count = count($problem_orders);

        $total_other_orders = $alternative_orders_count + $problem_orders_count;
        $total_order_count = $orders->count();
        $valid_order_count =  $total_order_count - $total_other_orders;

        $response = [
            'html_output'         => $output,
            'problem_orders_count' => $problem_orders_count,
            'problem_orders'      => $problem_orders,
            'problem_orders_html' => $problem_orders_html,
            'alternative_orders_count' => $alternative_orders_count,
            'alternative_orders' => $alternative_orders,
            'alternative_orders_html' => $alternative_orders_html,
            'order_count'         => $total_order_count,
            'order_data'          => $order_data,
            'valid_order_count'   => $valid_order_count,
            'total_order_count'   => $order_counts['total_processing']
        ];

        return $response;
    }

    /**
     * @param $error_code
     *
     * @return string|null
     */
    public function getErrorMessage($error_code)
    {
        if (isset($error_code)) {
            $error_message = $this->checkErrorCode($error_code);

            $error_message = implode(', ', $error_message);
        } else {
            $error_message = null;
        }

        return $error_message;
    }

    /**
     * @return array
     */
    public function getOrdersByDeliveryDate(int $pagenumber, string $delivery_date)
    {
        // We need to limit the number of orders which are going to be sent to the DPD API to be checked for the delivery options -> 100 Seems to be about its limit.

        // Counts to Send...

        // Total Number of orders "Processing" - regardless of delivery date/etc
        // Total Number of orders For the next Delivery Date.
        // Total Number of orders For the next Weeks Delivery Dates?

        // Whenever the System was Started to be used - Going to Need something Better longer term
        $date = (new \DateTime("2020-12-1"));

        //Check Date Passed

        $delivery_date_strtotime = strtotime($delivery_date);

        $delivery_date_tocompare = date("Y-m-d", $delivery_date_strtotime);

        // Make Admin Selectable?
        $filters = [
            [
                'field' => 'status',
                'value' => 'processing'
            ],
            [
                'field' => 'created_at',
                'value' => $date->format('Y-m-d'),
                'condition_type' => 'gt'
            ],
            [
                'field' => 'delivery_date_selected',
                'value' => $delivery_date_tocompare,
                'condition_type' => 'eq'
            ]
        ];

        // What If Not Using This Field?
        $sortorder = [
            'field' => 'created_at',
            'direction' => 'DESC',
        ];

        return $this->getOrdersMain($pagenumber, $filters, $sortorder);
    }

    /**
     * @return array
     */
    public function getOrdersMain(int $pagenumber, array $filters, array $sortorder)
    {
        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);

        $order_counts = [];

        $orders_precount = $this->orderRepository->getList($searchCriteria);

        $order_counts['total_processing'] = $orders_precount->getTotalCount();

        $pageSize = 90;

        $searchCriteria->setPageSize($pageSize)->setCurrentPage($pagenumber);

        $orders = $this->orderRepository->getList($searchCriteria);

        // Ok Depends on How we are showing them though -
        // Yes, if we're showing all processing orders regardless of date, but if we are showing a specfic date then
        // well either way it would be on the toatl processing number as it would show the total processing for day
        // x if we passed that into the filter above

        $output = '';
        $problem_orders_html = '';
        $problem_orders_count = 0;
        $problem_orders = [];

        $alternative_orders_html = '';
        $alternative_orders_count = 0;
        $alternative_orders = [];

        $order_data = [];

        $alternative_shipping_postcodes = $this->helper->getAlternativedeloptionPostcodes();

        foreach ($orders->getItems() as $order) {
            $order_id = $order->getIncrementId();

            $this->_logger->info($order_id . ' - ' . strtoupper($order->getShippingAddress()->getPostcode()));

            $shippingAddress = $order->getShippingAddress();

            $billingAddress = $order->getBillingAddress();

            // Gift Message

            $gift_message = $this->getGiftMessage($order, $shippingAddress);

            // Build Order Data For View Order Popup

            $temp_order_data = $this->BuildOrderPopupData($order, $shippingAddress, $billingAddress, $gift_message);

            $order_data[$order_id] = $temp_order_data['order_data'];
            $order_data_array = $temp_order_data['order_data_array'];

            $shipping_firstname = rtrim($shippingAddress->getFirstname());
            $shipping_lastname = rtrim($shippingAddress->getLastname());
            $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;
            $shipping_telephone_tmp = rtrim($shippingAddress->getTelephone());
            $shipping_customer_email = rtrim($shippingAddress->getEmail());
            $shipping_postcode_pre = rtrim($shippingAddress->getPostcode());

            $shipping_postcode = strtoupper($shipping_postcode_pre);
            // Need to check if this is an issue...

            $shipping_city = rtrim($shippingAddress->getCity());
            $shipping_region = rtrim($shippingAddress->getRegion());
            $shipping_address_tmp = rtrim($shippingAddress->getData('street'));
            $shipping_company = rtrim($shippingAddress->getCompany());

            // Fudge This For Now!
            $shipping_country_code = $shippingAddress->getCountryId(); //2 letter iso - e.g US

            //$shipping_country_code = "GB";
            $is_country_gb = false;

            if (strcmp($shipping_country_code, 'GB') === 0) {
                $is_country_gb = true;
            }

            //$shipping_country_code = "GB";
            // Rounding to 2 decimals because otherwise empty doesn't find 0.0000 as empty!
            $shipping_weight = round($order->getWeight(), 2);

            $packageWeightLimit = $this->helper->getPackageWeightLimit();
            // Setting this because it was causing major issues
            $sender_country_code = "GB";
            $numberOfParcels = 1;

            if ($shipping_weight < 1) {
                $shipping_weight = 1;
            }
            if ($numberOfParcels < 1) {
                $numberOfParcels = 1;
            }

            // What if Sender Street 2/County/etc is empty?!

            $dataArray = [
                'collectionDetails' => [
                    'address' => [
                        'locality'    => $this->helper->getSenderOrgStreet2(),
                        'county'      => $this->helper->getSenderOrgCounty(),
                        'postcode'    => $this->helper->getSenderOrgPostcode(),
                        'countryCode' => $sender_country_code
                    ],
                ],
                'deliveryDetails'   => [
                    'address' => [
                        'locality'    => '',
                        'postcode'    => $shipping_postcode,
                        'countryCode' => $shipping_country_code
                    ],
                ],
                'deliveryDirection' => 1,
                'numberOfParcels'   => $numberOfParcels,
                'totalWeight'       => $shipping_weight,
                'shipmentType'      => 0
            ];

            $problem_order_flag = false;

            //gets the available services

            $error_code = [];

            if (empty($shipping_weight)) {
                $error_code[] = 100;
            }

            if (empty($this->checkOrderPostcode($shipping_country_code, $shipping_postcode))) {
                $error_code[] = 200;
            }

            $error_message = $this->getErrorMessage($error_code);
            unset($error_code);

            if (!empty($this->checkOrderPostcode($shipping_country_code, $shipping_postcode)) && (!empty($shipping_weight))) {
                $delivery_date_on = 1;

                if ($delivery_date_on === 1) {
                    // if Using Detailed Delivery Date
                    // This will Work if it is a single date - but if it is a range we'll have to come up with a solution down the line

                    //$delivery_date_raw = $order->getDetailedDeliveryInfoDates();
                    $delivery_date_raw = $order->getDeliveryDateSelected();

                    if (empty(strtotime($delivery_date_raw))) {
                        // either blank or 1970-01-01 - which returns 0!
                        $error_code[] = 300;
                        $error_message .= $this->getErrorMessage($error_code);

                        $problem_order_flag = true;
                        unset($error_code);
                    }
                }

                // Postcode is Valid and a Shipping Weight is set on Package
                // TODO: What when the Weight is REALLY BIG (PAS Situation 150kg)

                // Do I Strip The Letters out of the PackageWeightLimit

                if ($shipping_weight >= $packageWeightLimit) {
                    // Divide by the package weight limit and round up to next int
                    $dataArray['numberOfParcels'] = ceil($shipping_weight / $packageWeightLimit);
                }

                // Check if Order Postcode is going to match any of the postcodes
                // specified in the alternative postcode thingy

                $is_postcode_in_altenativepostcodes = false;

                if (!empty($is_country_gb)) {
                    // Only Check The Postcode if GB as this is for the alternative shipping method check
                    $is_postcode_in_altenativepostcodes = $this->checkPostcodeAgaisntSet($shipping_postcode, $alternative_shipping_postcodes);
                }

                if (empty($is_postcode_in_altenativepostcodes)) {
                    $shipping_array = $this->dpdAuthorisation->getShipping($dataArray);
                    if (!is_array($shipping_array)) {
                        // Catching "Error Connecting to API Error" - Rate Limited

                        if ($shipping_array == 'error connecting to API - ') {
                            // Shouldn't happen now.
                            $this->_logger->info('Error Connecting to API');
                            $this->_logger->info(print_r($dataArray, true));

                            if (empty($error_message)) {
                                $error_message = 'Error connecting to API';
                            } else {
                                $error_message .= 'Error connecting to API';
                            }

                            $problem_order_flag = true;
                        }
                    }

                    if (empty($problem_order_flag)) {
                        $output .= $this->getTableRow("Valid Order", $order, $shipping_array, $order_data_array, $gift_message, $billingAddress, $shippingAddress);
                    } else {

                        // Add to Problem Order
                        $problem_orders[] = $order->getIncrementId();

                        $problem_orders_html .= $this->getTableRow("Problem Order", $order, $shipping_array, $order_data_array, $gift_message, $billingAddress, $shippingAddress, $error_message);
                        unset($error_message);
                        continue;
                    }
                } else {
                    // Postcode is an alternative postcode!
                    $alternative_orders[] = $order->getIncrementId();
                    $alternative_orders_html .= $this->getTableRow("Alternative Postcode Order", $order, '', $order_data_array, $gift_message, $billingAddress, $shippingAddress, '');
                }
            } else {
                $problem_orders[] = $order->getIncrementId();

                $problem_orders_html .= $this->getTableRow("Problem Order", $order, '', $order_data_array, $gift_message, $billingAddress, $shippingAddress, $error_message);
                // Postcode is empty or something is wrong

                unset($error_message);
            }
        }

        $alternative_orders_count = count($alternative_orders);
        $problem_orders_count = count($problem_orders);

        $total_other_orders = $alternative_orders_count + $problem_orders_count;
        $total_order_count = $orders->count();
        $valid_order_count =  $total_order_count - $total_other_orders;

        $response = [
            'html_output'         => $output,
            'problem_orders_count' => $problem_orders_count,
            'problem_orders'      => $problem_orders,
            'problem_orders_html' => $problem_orders_html,
            'alternative_orders_count' => $alternative_orders_count,
            'alternative_orders' => $alternative_orders,
            'alternative_orders_html' => $alternative_orders_html,
            'order_count'         => $total_order_count,
            'order_data'          => $order_data,
            'valid_order_count'   => $valid_order_count,
            'total_order_count'   => $order_counts['total_processing']
        ];

        return $response;
    }

    /**
     * @param $error_code
     *
     * @return array
     */
    public function checkErrorCode($error_code)
    {
        $error_messages = [];

        foreach ($error_code as $error) {
            switch ($error) {
                case 100:
                    $error_messages[] = "Order Weight = 0 - Check Product Weight Values";
                    break;
                case 200:
                    $error_messages[] = "Postcode Error";
                    break;
                case 300:
                    $error_messages[] = "Invalid Delivery Date";
                    break;
            }
        }

        return $error_messages;
    }

    /**
     * @param string $order_type
     * @param object $order
     * @param string|array|null $shipping_array
     * @param array $order_data_array
     * @param $gift_message
     * @param $billingAddress
     * @param $billingAddress
     * @param string|array|null $error_message
     *
     *
     * @return string
     */
    public function getTableRow(string $order_type, object $order, $shipping_array, array $order_data_array, $gift_message, $billingAddress, $shippingAddress, $error_message = null)
    {
        $order_id = $order->getId();
        $output = '';
        //Your Code Here
        $output .= '<tr class="data-row" data-order-id="' . $order->getIncrementId() . '">';

        // Switcher for Include/Exclude?

        if (strcmp($order_type, "Valid Order") === 0) {
            $include = 1;
            $exclude = 0;

            if ($include === 1) {
                $output .= '<td><div class="" style="text-align:center;"><input data-row-id="' . $order->getIncrementId() . '" type="checkbox" class="include-row" id="include_' . $order->getIncrementId() . '" value="0" name="include_' . $order->getIncrementId() . '"></div></td>';
            } else {
                // Exclude

                $output .= '<td><div class="" style="text-align:center;"><input data-row-id="' . $order->getIncrementId() . '" type="checkbox" class="exclude-row" id="exclude_' . $order->getIncrementId() . '" value="0" name="exclude_' . $order->getIncrementId() . '"></div></td>';
            }
        }

        $output .= '<td><div class="">' . $order->getIncrementId() . '<input hidden name="order_id" value="' . $order->getIncrementId() . '" /></div></td>';
        $output .= '<td><div class="">' . $order->getCreatedAt() . '</div></td>';

        $delivery_date_on = 1;

        if ($delivery_date_on === 1) {
            // if Using Detailed Delivery Date
            // This will Work if it is a single date - but if it is a range we'll have to come up with a solution down the line

            //$delivery_date_raw = $order->getDetailedDeliveryInfoDates();
            $delivery_date_raw = $order->getDeliveryDateSelected();

            $delivery_date = date("Y-m-d", strtotime($delivery_date_raw));

            $output .= '<td><div class="">' . $delivery_date . '</div></td>';
        }

        $shippingName = '';
        $shippingName = $shippingAddress->getFirstname() . " " . $shippingAddress->getLastname();

        //$output .= '<td><div class="">'.$billingName.'</div></td>';
        $output .= '<td><div class="">' . $shippingName . '</div></td>';
        //$output .= '<td><div class="">'.round($order->getBaseGrandTotal(),2).'</div></td>';

        // Postcode

        $output .= '<td><div class="">' . $shippingAddress->getPostcode() . '</div></td>';

        // Items
        $output .= '<td><div class="">' . $this->getOrderItemsShort($order, $order_data_array) . '</div></td>';

        $output .= '<td><div class="">' . round($order->getBaseGrandTotal(), 2) . '</div></td>';

        $output .= '<td><div class="">' . $gift_message['gift_message_flag_output'] . '</div></td>';

        $packages_select = $this->getSelectForPackages($order->getIncrementId());
        $output .= '<td><div class="">' . $packages_select . '</div></td>';

        // Check Weight Value from Config Value instead of hardcoding KG in text.
        $output .= '<td><div class="">' . round($order->getWeight(), 2) . 'kg</div></td>';
        if ($order_type == "Problem Order") {
            if (!empty($error_message)) {
                $output .= '<td><div class="">' . $error_message . '</div></td>';
            } else {
                $output .= '<td><div class="">Can\'t Send!</div></td>';
            }
        } elseif ($order_type == "Alternative Postcode Order") {
            if (!empty($error_message)) {
                $output .= '<td><div class="">' . $error_message . '</div></td>';
            } else {
                $output .= '<td><div class=""></div></td>';
            }
        } else {
            $output .= '<td><div class=""><select class="shipping_methods" data-row-id="' . $order->getIncrementId() . '" data-order-id="' . $order->getIncrementId() . '" name="shipping_networkcode_' . $order->getIncrementId() . '">' . $this->helper->getShippingOptionsSelect($shipping_array, $order) . '</select></div></td>';
        }

        //$output .= '<td><div class="">'.$order->getStatus().'</div></td>';
        $output .= '<td><div class=""><button type="button" class="view-order-data action" data-trigger="trigger" data-order-id="' . $order->getIncrementId() . '">View Order
</button></div></td>';
        $output .= '</tr>';

        return $output;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param \Magento\Sales\Api\Data\OrderAddressInterface $shippingAddress
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getGiftMessage($order, $shippingAddress)
    {

        // See if Order has a gift message assigned
        $gift_message_flag = false;
        $gift_message = $order->getGiftMessageId();

        // If nothing is set, check our specific variable added

        if (empty($gift_message)) {
            // Shipping Address Check
            // Check Order Address
            // Not Sure This is Necessary anymore but for redundancy
            $gift_message = $shippingAddress->getEvGiftmessagemessage();
        } else {
            // Else get the Gift Message
            $gift_message = $this->giftMessageRepository->get($order->getId())->getMessage();
        }



        $gift_message_flag_output_icon = '<i class="fa fa-cross" style="color:darkred;"></i>';
        $gift_message_flag_output_text = '<span class="gift-msg-flag">No</span>';

        if (!empty($gift_message)) {
            $gift_message_flag = true;
            $gift_message_flag_output_icon = '<i class="fa fa-check" style="color:forestgreen;"></i>';
            $gift_message_flag_output_text = '<span class="gift-msg-flag">Yes</span>';
            $gift_message = $this->stripLineBreaks($gift_message);
        }

        return [
            'gift_message' =>    $gift_message_flag,
            'gift_message_flag_output_icon' => $gift_message_flag_output_icon,
            'gift_message_flag_output_text' => $gift_message_flag_output_text,
            'gift_message_flag_output' => $gift_message_flag_output_icon . ' ' . $gift_message_flag_output_text,
            'gift_message_actual' => $gift_message
        ];
    }

    /**
     * @param string $text
     *
     * @return string|string[]
     */
    public function stripLineBreaks(string $text)
    {
        return str_replace(["\r", "\n"], '', $text);
    }

    /**
     * @param $order_id
     *
     * @return string
     */
    public function getSelectForPackages($order_id)
    {
        $packages_select = '<select class="number_of_packages_select" name="number_of_packages_' . $order_id . '" data-row-id="' . $order_id . '" data-order-id="' . $order_id . '" >';
        $packages_select .= '<option value="1" selected="selected">1</option>';
        for ($i = 2; $i < 15; $i++) {
            $packages_select .= '<option value="' . $i . '">' . $i . '</option>';
        }

        $packages_select .= '</select>';

        return (string)$packages_select;
    }

    /**
     * @param $order
     * @param $shippingAddress
     * @param $billingAddress
     * @param $gift_message
     *
     * @return array
     */
    public function BuildOrderPopupData($order, $shippingAddress, $billingAddress, $gift_message)
    {
        $billing_address_output = $this->getAddressOutput($order, $billingAddress);
        $shipping_address_output = $this->getAddressOutput($order, $shippingAddress);

        $orderId = $order->getEntityId(); // ENTITY ID NOT INCREMENT!

        $order_data_array = [];

        $output = '<div>';
        $output .= '<div class="order-details-output">';
        $output .= '<div class="order-details-top">';

        $output .= '<div class="order-data-section"><div class="field-title">Order Id: </div> <div class="field-value">' . $order['increment_id'] . '</div></div>';
        $output .= '<div class="order-data-section"><div class="field-title">Order Created: </div> <div class="field-value">' . $order['created_at'] . '</div></div>';
        $output .= '<div class="order-data-section"><div class="field-title">Order Updated: </div> <div class="field-value">' . $order['updated_at'] . '</div></div>';
        $output .= '<div class="order-view-link"><a class="action-button" target="_blank" href="' . $this->backendUrl->getUrl('sales/order/view', ['order_id' => $orderId]) . '">View Detailed Order Information</a></div>';
        $output .= '</div>';
        $output .= '<div class="addresses-output">';
        $output .= $billing_address_output;
        $output .= $shipping_address_output;

        $output .= '</div>';

        $output .= '<div class="gift-message-popupblock">';

        $output .= '<div class="panel panel-default card">';
        $output .= '<div class="panel-heading card-header">Gift Message</div>';
        $output .= '<div class="panel-body card-body"><p>' . $gift_message['gift_message_actual'] . '</p></div>';
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="order-items">';

        $output .= '<div class="panel panel-default">';
        $output .= '<div class="panel-heading">Order Items</div>';
        $output .= '<table class="table table-responsive">';
        $output .= '
        <thead>
            <tr class="headings">
              <th class="col-product"><span>Product</span></th>
              <th class="col-status"><span>Item Status</span></th>
              <th class="col-price-original"><span>Original Price</span></th>
              <th class="col-price"><span>Price</span></th>
              <th class="col-ordered-qty"><span>Qty</span></th>
              <th class="col-subtotal"><span>Subtotal</span></th>
              <th class="col-tax-amount"><span>Tax Amount</span></th>
              <th class="col-tax-percent"><span>Tax Percent</span></th>
              <th class="col-discont"><span>Discount Amount</span></th>
              <th class="col-total last"><span>Row Total</span></th>
            </tr>
        </thead>';
        $output .= '<tbody>';
        $order_items = $order['items'];

        $order_items = $order->getAllVisibleItems();

        $order_data_array = $order_items;

        foreach ($order_data_array as $item) {
            //$item_data = $item['data'];
            //$item_object = $item['object'];
            $item_object = $item;
            $item_data = $item->getData();

            $output .= '<tr>';
            $output .= '<td><div class="order-item">' . $item_data['name'] . '</div></td>';
            $output .= '<td><div class="order-item">' . $item_object->getStatus() . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getOriginalPrice(), 2) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getPrice(), 2) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getQtyOrdered(), 0) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getSubtotal(), 2) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getTaxamount(), 2) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getTaxpercent(), 2) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getDiscountAmount(), 2) . '</div></td>';
            $output .= '<td><div class="order-item">' . round($item_object->getRowTotal(), 2) . '</div></td>';
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>'; // Panel
        $output .= '</div>';//order -items?
        $output .= '</div>';
        $output .= '<div class="order-details-comments">';
        $output .= '<div class="panel panel-default">';
        $output .= '<div class="panel-heading">Order Comments</div>';
        $output .= '<table class="table table-responsive">';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>Time</th>';
        $output .= '<th>Comment</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        $status_history = $order->getStatusHistories();

        foreach ($status_history as $history) {
            $history_date_stamp = $history['created_at'];
            $history_comment = $history['comment'];

            $output .= '<tr>';
            $output .= '<td>' . $history_date_stamp . '</td>';
            $output .= '<td>' . $history_comment . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '</div>'; // comments Panel

        $output .= '</div>'; // Comments
        $output .= '</div>';
        $order_data = $output;

        $response = [
            'order_data_array' => $order_data_array,
            'order_data'       => $order_data
        ];

        return (array)$response;
    }
    /**
     * Checks Order Postcode - Trying To determine if it's a Foreign Order?/etc?
     *
     * @param $postcode
     *
     * @return bool
     */
    public function checkOrderPostcode(
        $country_code,
        $postcode
    ) {
        if (strcmp($country_code, 'GB') === 0) {
            // Check if empty (i.e

            // TODO: modify for admin selecting which countries shipped to?

            $match = false;
            // Check if empty (i.e

            // check if postcode is valid (i.e. the characters match the standard range of known uk postcode regions)
            $re = '/.+?(?=\d)/';
            preg_match($re, $postcode, $matches, PREG_OFFSET_CAPTURE, 0);

            if (isset($matches[0])) {
                $postcode_to_test  = strtoupper($matches[0][0]);
                // Tests for Valid Postcodes
                if (in_array($postcode_to_test, $this->helper->getValidPostcodes())) {
                    // Yes it matches
                    $match = true;
                } else {
                    // Doesn't match
                    $this->_logger->info("checkOrderPostcode - no match");
                    return false;
                }
            } else {
                $this->_logger->info("checkOrderPostcode - no match");
                return false;
            }
            if (empty($postcode)) {
                $match = false;
            } else {
                // Is this a UK postcode?
                //$re = '/^([A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}|GIR ?0A{2})$/m';
                $re = '/^([A-Za-z][A-Ha-hJ-Yj-y]?[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}|[Gg][Ii][Rr] ?0[Aa]{2})$/m';
                $matches = preg_match_all($re, $postcode, $matches, PREG_SET_ORDER, 0);
                if (!empty($matches)) {
                    $match = true;
                } else {
                    $match = false;
                    $this->_logger->info("checkOrderPostcode - no match");
                    $this->_logger->info(print_r($matches, true));
                }
            }
            return $match;
        } elseif (strcmp($country_code, 'IE') === 0) {
            // Ireland
            $match = true;
        //True as ireland Postcode doesn't matter
        } else {
            // TODO:: Need to implement something here
            return false;
        }
    }

    /**
     * Checks Postcodes stored in a AB10-99 Fashion
     *
     * $postcodes_to_compare is a string delimited by a semi-colon (;)
     *  (example: AB10-99; PH19-99;)
     * It will create the range of codes between the two numbers for comparison purposes
     */
    public function checkPostcodeAgaisntSet(
        string $postcode_to_check,
        string $postcodes_to_compare
    ) {
        $postcode = strtoupper(trim($postcode_to_check));
        $postcodes = explode(";", $postcodes_to_compare);
        $postcodesCount = 0;

        // We are only comparing agaisnt the first part of the postcode (AB19 etc)
        $match_found = false;
        $split_array = [];

        if (preg_match('/ ?([0-9])[ABD-HJLNP-UW-Z]{2}$/i', $postcode, $match, PREG_OFFSET_CAPTURE)) {
            $postcode = substr($postcode, 0, $match[0][1]);
            $match_found = true;
            $split_array = preg_split('/(?=\d)/', $match[0][0], 2);
        }
        if ($match_found === false) {
            return false;
            // basically if it gets to this point the postcode is highly likely to be invalid in some way.
        }

        $split_postcode = $split_array[0];

        foreach ($postcodes as $postcode_from_list) {
            // Check if It's likely to be in there?

            if (strpos($postcode_from_list, $split_postcode) === false) {
                // skip
                continue;
            }

            if (strlen(trim($postcode_from_list)) > 0) {
                $postcodesCount++;
                $postcode_from_list = trim($postcode_from_list);
                if (strpos($postcode_from_list, "-") === false) {
                    if ($postcode_from_list == $postcode) {
                        return true;
                    }
                } else {
                    $postcodesbounds = explode("-", $postcode_from_list);
                    // Get Letters from Postcode
                    $postcodeletters = preg_replace("/[^a-zA-Z]/", "", $postcodesbounds[0]);

                    $postcodesbounds[0] = str_replace($postcodeletters, '', $postcodesbounds[0]);

                    if (count($postcodesbounds) > 0) {
                        // is a Range
                        $rangelower = $postcodesbounds[0];

                        $rangeupper = $postcodesbounds[1];

                        $postcodenumericrange = range($rangelower, $rangeupper);
                        foreach ($postcodenumericrange as &$value) {
                            $value = $postcodeletters . $value;
                        }
                        unset($value);
                        unset($postcodeletters);
                    }

                    if (in_array($postcode, $postcodenumericrange)) {
                        return true;
                    }
                }
            }
        }
        // If it makes it through the loop without hitting a code then no match.
        return false;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function RemoveFalseButNotZero($value)
    {
        return ($value || is_numeric($value));
    }

    /**
     *
    public function misc()
    {
        $item_type = '';
        if (strcmp($item_type, 'configurable') === 0) {
            $order_data_array[$item_id]['object'] = $item;
            $order_data_array[$item_id]['data'] = $item->getData();
        } elseif (strcmp($item_type, 'simple') === 0) {
            // Does it Have Parent_ID ?
            $parent_id = $item->getParentItemId();
            if (!empty($parent_id)) {
                // Child of Configurable
                if (!empty($order_data_array[$parent_id])) {
                    // Check if exists
                    $data = [
                        'object' => $item,
                        'data'   => $item->getData()
                    ];
                    $order_data_array[$parent_id]['children'][] = $data;
                }
            } else {
                // Standard Simple
                $order_data_array[$item_id]['object'] = $item;
                $order_data_array[$item_id]['data'] = $item->getData();
            }
        }
    }
     */

    /**
     *
     */

    public function getOrderItemsShort(
        object $order,
        array $order_data_array
    ) {
        $output = '';
        //$order_items = $order['items'];
        $order_items = $order_data_array;

        foreach ($order_items as $item) {
            $item_data = $item->getData();
            $item_object = $item;

            $output .= '<div class="order-item-shrt">';
            if (strcmp($item_data['product_type'], 'configurable') === 0) {
                //$output .= '<span class="config-name">'.$item_data['name'].'</span>';
                if (!empty($item->getHasChildren())) {
                    $children_data = $item->getProductOptions();
                    $child_name = $children_data['simple_name'];
                    $child_qty = $children_data['info_buyRequest']['qty'];

                    $output .= $child_name . ' x ' . round($child_qty, 0);
                }
            } else {
                $output .= $item_data['name'] . ' x ' . round($item_object->getQtyOrdered(), 0);
            }

            $output .= '</div>';
        }

        return (string)$output;
    }

    /***
    /**
     * @param object $order
     *
     * @return string

    public function getOrderOutput(object $order)
    {
        $output = '<div class="order-details-output-inner">';

        $output .= '<div class="panel panel-default">';
        $output .= '<div class="panel-heading">Order: ' . $order->getIncrementId();
        '</div>';

        $output .= '<table class="table table-responsive">';
        $output .= '<tr>';
        $output .= '<td><div class="field-title">Name</div></td>';
        $output .= '<td><div class="">' . $full_name . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Company</div></td>';
        $output .= '<td><div class="">' . $address->getCompany() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Street</div></td>';
        $output .= '<td>';

        foreach ($address->getStreet() as $street_line) {
            $output .= '<div class="">' . $street_line . '</div>';
        }

        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">City</div></td>';
        $output .= '<td><div class="">' . $address->getCity() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">County</div></td>';
        $output .= '<td><div class="">' . $address->getCounty() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Postcode</div></td>';
        $output .= '<td><div class="">' . $address->getPostcode() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Country</div></td>';
        $output .= '<td><div class="">' . $address->getCountryId() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Email</div></td>';
        $output .= '<td><div class="">' . $address->getEmail() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Telephone</div></td>';
        $output .= '<td><div class="">' . $address->getTelephone() . '</div></td>';
        $output .= '</tr>';

        $output .= '</table>';

        $output .= '</div>'; // Panel

        $output .= '</div>';

        return (string)$output;
    }
    */

    /**
     * @param object $order
     * @param object $address
     *
     * @return string
     */
    public function getAddressOutput(
        object $order,
        object $address
    ) {
        $type = $address->getAddressType();

        /*
        echo $type;
        echo '<pre>';
        print_r($address->getData());
        die();
        */

        if (strcmp($type, 'billing') === 0) {
            $type_name = 'billing';
            $billing = 1;
            $shipping = 0;
        } else {
            // Shipping
            $type_name = 'shipping';
            $billing = 0;
            $shipping = 1;
        }

        $output = '<div class="' . $type_name . '-address">';

        $output .= '<div class="panel panel-default">';
        $output .= '<div class="panel-heading">' . $type_name . ' address</div>';

        if (!empty($billing)) {
            $full_name = $address->getFirstname() . " " . $address->getLastname();
        } else {
            $full_name = $address->getFirstname() . " " . $address->getLastname();
        }

        $output .= '<table class="table table-responsive">';
        $output .= '<tr>';
        $output .= '<td><div class="field-title">Name</div></td>';
        $output .= '<td><div class="">' . $full_name . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Company</div></td>';
        $output .= '<td><div class="">' . $address->getCompany() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Street</div></td>';
        $output .= '<td>';

        foreach ($address->getStreet() as $street_line) {
            $output .= '<div class="">' . $street_line . '</div>';
        }

        $output .= '</td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">City</div></td>';
        $output .= '<td><div class="">' . $address->getCity() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">County</div></td>';
        $output .= '<td><div class="">' . $address->getCounty() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Postcode</div></td>';
        $output .= '<td><div class="">' . $address->getPostcode() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Country</div></td>';
        $output .= '<td><div class="">' . $address->getCountryId() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Email</div></td>';
        $output .= '<td><div class="">' . $address->getEmail() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Telephone</div></td>';
        $output .= '<td><div class="">' . $address->getTelephone() . '</div></td>';
        $output .= '</tr>';

        $output .= '</table>';

        $output .= '</div>'; // Panel

        $output .= '</div>';

        return (string)$output;
    }

    /**
     * @param $order
     * @param $billingAddress
     *
     * @return string
     */
    public function getBillingAddressOutput(
        $order,
        $billingAddress
    ) {
        $output = '<div class="building-address">';

        $billingAddress = $order->getBillingAddress();
        $billingName = '';

        if (empty($order->getCustomerFirstname())) {
            $billingAddress = $order->getBillingAddress();

            $billingName = $billingAddress->getFirstname() . " " . $billingAddress->getLastname();
        } else {
            $billingName = $order->getCustomerFirstname() . " " . $order->getCustomerLastname();
        }

        $output .= '<table>';
        $output .= '<tr>';
        $output .= '<td><div class="field-title">Billing Name</div></td>';
        $output .= '<td><div class="">' . $billingName . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">Street</div></td>';
        $output .= '<td><div class="">' . $billingAddress->getStreet() . '</div></td>';
        $output .= '</tr>';

        $output .= '<tr>';
        $output .= '<td><div class="field-title">City</div></td>';
        $output .= '<td><div class="">' . $billingAddress->getCity() . '</div></td>';
        $output .= '</tr>';

        $output .= '<td><div class="field-title">Billing Name</div></td>';

        $output .= '</div>';

        return $output;
    }

    /**
     * @param $order
     * @param $shippingAddress
     *
     * @return string
     */
    public function getShippingAddressOutput(
        $order,
        $shippingAddress
    ) {
        $output = '<div class="shipping-address">';

        $shippingName = '';
        $shippingAddressId = $order->getShippingAddressId();
        $shippingAddress = $this->orderAddressRepo->get($shippingAddressId);
        $shippingName = $shippingAddress->getFirstname() . " " . $shippingAddress->getLastname();

        $output .= '<div class="ship-name">' . $shippingName . '</div>';
        $output .= '<div class="ship-address">' . $shippingAddress . '</div>';

        $output .= '</div>';

        return $output;
    }
}
