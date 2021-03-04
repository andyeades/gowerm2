<?php

namespace Elevate\PrintLabels\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class OrderData extends AbstractHelper {
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
     * @var \PrintNode\Credentials
     */
    protected $printNodeCredentials;

    /**
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;

    protected $addressRepository;

    protected $orderAddressRepository;

    protected $orderAddressRepo;

    protected $orderModel;

    protected $helper;

    protected $backendUrl;

    /**
     * Data constructor.
     *
     * @param Context                                                         $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
     * @param \Magento\Sales\Api\OrderRepositoryInterface                     $orderRepository
     * @param \Magento\Customer\Api\AccountManagementInterface                $accountManagement
     * @param \Magento\Sales\Api\OrderCustomerManagementInterface             $orderCustomerService
     * @param \Magento\Framework\Api\SearchCriteriaBuilder                    $searchCriteriaBuilder
     * @param \Magento\Framework\Controller\Result\JsonFactory                $resultJsonFactory
     * @param \Magento\Customer\Api\AddressRepositoryInterface                $addressRepository
     * @param \Magento\Sales\Model\Order\AddressRepository                    $orderAddressRepository
     * @param \Magento\Sales\Api\OrderAddressRepositoryInterface              $orderAddressRepo
     * @param \Magento\Sales\Model\Order                                      $orderModel
     * @param \PrintNode\Credentials                                          $printNodeCredentials
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation ,
     * @param \Elevate\PrintLabels\Helper\Data                                $helper
     * @param \Magento\Backend\Model\UrlInterface                             $backendUrl
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \PrintNode\Credentials $printNodeCredentials,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Model\Order $orderModel,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation,
        \Elevate\PrintLabels\Helper\Data $helper,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->accountManagement = $accountManagement;
        $this->scopeConfig = $scopeConfig;
        $this->printNodeCredentials = $printNodeCredentials;
        $this->dpdAuthorisation = $dpdAuthorisation;
        $this->addressRepository = $addressRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderModel = $orderModel;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->helper = $helper;
        $this->backendUrl = $backendUrl;
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

    public function getOrders() {

        //arbitrary date - do we let them select?
        $date = (new \DateTime())->modify('-3 months');

        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            'status', 'processing', 'eq'
        )->addFilter(
            'created_at', $date->format('Y-m-d'), 'gt'
        )->create();

        $orders = $this->orderRepository->getList($searchCriteria);
        $output = '';
        $problem_orders_html = '';
        $problem_orders_count = 0;
        $problem_orders = array();

        $order_data = array();

        //
        foreach ($orders->getItems() as $order) {
            $order_id = $order->getIncrementId();

            // Build Order Data For View Order Popup

            $temp_order_data = $this->BuildOrderPopupData($order);

            $order_data[$order_id] = $temp_order_data['order_data'];
            $order_data_array = $temp_order_data['order_data_array'];

            $shipping_address = $order->getShippingAddress();

            $shipping_firstname = $order->getShippingAddress()->getFirstname();
            $shipping_lastname = $order->getShippingAddress()->getLastname();
            $shipping_customer_name = $shipping_firstname . ' ' . $shipping_lastname;
            $shipping_telephone_tmp = $order->getShippingAddress()->getTelephone();
            $shipping_customer_email = $order->getShippingAddress()->getEmail();
            $shipping_postcode = $order->getShippingAddress()->getPostcode();

            // Need to check if this is an issue...

            $shipping_city = $order->getShippingAddress()->getCity();
            $shipping_region = $order->getShippingAddress()->getRegion();
            $shipping_address_tmp = $order->getShippingAddress()->getData('street');
            $shipping_company = $order->getShippingAddress()->getCompany();

            // Fudge This For Now!
            $shipping_country_code = $order->getShippingAddress()->getCountryId(); //2 letter iso - e.g US

            // Ship to IRELAND AS WELL!

            //$shipping_country_code = "GB";
            // Rounding to 2 decimals because otherwise empty doesn't find 0.0000 as empty!
            $shipping_weight = round($order->getWeight(),2);

            // Setting this because it was causing major issues
            $sender_country_code = "GB";
            $numberOfParcels = 1;

            // What if Sender Street 2/County/etc is empty?!

            $dataArray = array(
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
            );

            //gets the available services



            $error_code = array();

            if (empty($shipping_weight)) {
                $error_code[] = 100;
            }
            if (empty($this->checkOrderPostcode($order, $shipping_country_code, $shipping_postcode))) {
                $error_code[] = 200;
            }



            if (!empty($this->checkOrderPostcode($order, $shipping_country_code, $shipping_postcode) ) & (!empty($shipping_weight))) {

                $shipping_array = $this->dpdAuthorisation->getShipping($dataArray);

                $output .= $this->getTableRow($order, $shipping_array, $order_data_array);



            } else {
                $problem_orders[] = $order->getIncrementId();


                if (isset($error_code)) {
                    $error_message = $this->checkErrorCode($error_code);

                    $error_message = implode(', ',$error_message);
                } else {
                    $error_message = '';
                }


                // Postcode is empty or something is wrong

                $problem_orders_html .= '<tr class="data-row" data-order-id="' . $order->getIncrementId() . '">';

                //$problem_orders_html .= '<td><div class="data-grid-cell-content" style="text-align:center;"><input data-row-id="'.$order->getIncrementId().'" type="checkbox" class="exclude-row" id="exclude_'.$order->getIncrementId().'" value="0" name="exclude_'.$order->getIncrementId().'"></div></td>';
                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . $order->getIncrementId() . '<input hidden name="problem_order_id" value="' . $order->getIncrementId() . '" /></div></td>';
                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . $order->getCreatedAt() . '</div></td>';

                $billingName = '';
                if (empty($order->getCustomerFirstname())) {
                    $billingAddress = $order->getBillingAddress();

                    $billingName = $billingAddress->getFirstname() . " " . $billingAddress->getLastname();

                } else {
                    $billingName = $order->getCustomerFirstname() . " " . $order->getCustomerLastname();
                }

                $shippingName = '';
                $shippingAddressId = $order->getShippingAddressId();
                $shippingAddress = $this->orderAddressRepo->get($shippingAddressId);
                $shippingName = $shippingAddress->getFirstname() . " " . $shippingAddress->getLastname();

                //$problem_orders_html .= '<td><div class="data-grid-cell-content">'.$billingName.'</div></td>';
                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . $shippingName . '</div></td>';
                //$problem_orders_html .= '<td><div class="data-grid-cell-content">'.round($order->getBaseGrandTotal(),2).'</div></td>';

                // Items
                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . $this->getOrderItemsShort($order, $order_data_array) . '</div></td>';

                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . round($order->getBaseGrandTotal(), 2) . '</div></td>';

                $packages_select = $this->getSelectForPackages($order->getIncrementId());
                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . $packages_select . '</div></td>';

                // Check Weight Value from Config Value instead of hardcoding KG in text.
                $problem_orders_html .= '<td><div class="data-grid-cell-content">' . round($order->getWeight(), 2) . 'kg</div></td>';

                // Check if Problem is available to provide hint for End User?



                $problem_orders_html .= '<td><div class="data-grid-cell-content">'.$error_message.'</div></td>';


                $problem_orders_html .= '<td><div class="data-grid-cell-content"><button type="button" class="view-order-data action" data-trigger="trigger" data-order-id="' . $order->getIncrementId() . '">View Order
</button></div></td>';
                $problem_orders_html .= '</tr>';
                unset($error_message);
            }

        }

        $problem_orders_count = count($problem_orders);

        $response = array(
            'html_output'         => $output,
            'problem_order_count' => $problem_orders_count,
            'problem_orders'      => $problem_orders,
            'problem_orders_html' => $problem_orders_html,
            'order_count'         => $orders->count(),
            'order_data'          => $order_data
        );

        return $response;
    }

    function checkErrorCode($error_code) {

        $error_messages = array();

        foreach($error_code as $error) {
            switch($error) {
                case 100:
                    $error_messages[] = "Order Weight = 0 - Check Product Weight Values";
                    break;
                case 200:
                    $error_messages[] = "Postcode Error";
                    break;
            }
        }



        return $error_messages;
    }


    function getTableRow($order, $shipping_array, $order_data_array) {

        $output = '';
        //Your Code Here
        $output .= '<tr class="data-row" data-order-id="' . $order->getIncrementId() . '">';
        $output .= '<td><div class="data-grid-cell-content" style="text-align:center;"><input data-row-id="' . $order->getIncrementId() . '" type="checkbox" class="exclude-row" id="exclude_' . $order->getIncrementId() . '" value="0" name="exclude_' . $order->getIncrementId() . '"></div></td>';
        $output .= '<td><div class="data-grid-cell-content">' . $order->getIncrementId() . '<input hidden name="order_id" value="' . $order->getIncrementId() . '" /></div></td>';
        $output .= '<td><div class="data-grid-cell-content">' . $order->getCreatedAt() . '</div></td>';

        $billingName = '';
        if (empty($order->getCustomerFirstname())) {
            $billingAddress = $order->getBillingAddress();

            $billingName = $billingAddress->getFirstname() . " " . $billingAddress->getLastname();

        } else {
            $billingName = $order->getCustomerFirstname() . " " . $order->getCustomerLastname();
        }

        $shippingName = '';
        $shippingAddressId = $order->getShippingAddressId();
        $shippingAddress = $this->orderAddressRepo->get($shippingAddressId);
        $shippingName = $shippingAddress->getFirstname() . " " . $shippingAddress->getLastname();

        //$output .= '<td><div class="data-grid-cell-content">'.$billingName.'</div></td>';
        $output .= '<td><div class="data-grid-cell-content">' . $shippingName . '</div></td>';
        //$output .= '<td><div class="data-grid-cell-content">'.round($order->getBaseGrandTotal(),2).'</div></td>';

        // Items
        $output .= '<td><div class="data-grid-cell-content">' . $this->getOrderItemsShort($order, $order_data_array) . '</div></td>';

        $output .= '<td><div class="data-grid-cell-content">' . round($order->getBaseGrandTotal(), 2) . '</div></td>';

        $packages_select = $this->getSelectForPackages($order->getIncrementId());
        $output .= '<td><div class="data-grid-cell-content">' . $packages_select . '</div></td>';

        // Check Weight Value from Config Value instead of hardcoding KG in text.
        $output .= '<td><div class="data-grid-cell-content">' . round($order->getWeight(), 2) . 'kg</div></td>';
        $output .= '<td><div class="data-grid-cell-content"><select class="shipping_methods" data-row-id="' . $order->getIncrementId() . '" data-order-id="' . $order->getIncrementId() . '" name="shipping_networkcode_' . $order->getIncrementId() . '">' . $this->helper->getShippingOptionsSelect($shipping_array, $order) . '</select></div></td>';
        //$output .= '<td><div class="data-grid-cell-content">'.$order->getStatus().'</div></td>';
        $output .= '<td><div class="data-grid-cell-content"><button type="button" class="view-order-data action" data-trigger="trigger" data-order-id="' . $order->getIncrementId() . '">View Order
</button></div></td>';
        $output .= '</tr>';

        return $output;
    }

    function getSelectForPackages($order_id) {

        $packages_select = '<select class="number_of_packages_select" name="number_of_packages_' . $order_id . '" data-row-id="' . $order_id . '" data-order-id="' . $order_id . '" >';
        $packages_select .= '<option value="1" selected="selected">1</option>';
        for ($i = 2; $i < 15; $i++) {
            $packages_select .= '<option value="' . $i . '">' . $i . '</option>';
        }

        $packages_select .= '</select>';

        return (string)$packages_select;
    }

    function BuildOrderPopupData($order) {

        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();

        $billing_address_output = $this->getAddressOutput($order, $billingAddress);
        $shipping_address_output = $this->getAddressOutput($order, $shippingAddress);

        $orderId = $order->getEntityId(); // ENTITY ID NOT INCREMENT!

        $order_data_array = array();

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

        $response = array(
            'order_data_array' => $order_data_array,
            'order_data'       => $order_data
        );

        return (array)$response;
    }

    /**
     * Checks Order Postcode - Trying To determine if it's a Foreign Order?/etc?
     *
     * @param $order
     * @param $postcode
     *
     * @return bool
     */
    function checkOrderPostcode(
        $order,
        $country_code,
        $postcode
    ) {

        // Check if empty (i.e

        // TODO: modify for admin selecting which countries shipped to?

        if (strcmp($country_code, 'IE') === 0) {
            // Ireland
            return true;
        }

        if (empty($postcode)) {

            return false;
        } else {
            // Is this a UK postcode?
            //$re = '/^([A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}|GIR ?0A{2})$/m';
            $re = '/^([A-Za-z][A-Ha-hJ-Yj-y]?[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}|[Gg][Ii][Rr] ?0[Aa]{2})$/m';
            $matches = preg_match_all($re, $postcode, $matches, PREG_SET_ORDER, 0);

            if (!empty($matches)) {
                return true;
            } else {
                return false;
            }

        }
    }

    function misc() {
        if (strcmp($item_type, 'configurable') === 0) {


            $order_data_array[$item_id]['object'] = $item;
            $order_data_array[$item_id]['data'] = $item->getData();

        } else if (strcmp($item_type, 'simple') === 0) {
            // Does it Have Parent_ID ?
            $parent_id = $item->getParentItemId();
            if (!empty($parent_id)) {
                // Child of Configurable
                if (!empty($order_data_array[$parent_id])) {
                    // Check if exists
                    $data = array(
                        'object' => $item,
                        'data'   => $item->getData()
                    );
                    $order_data_array[$parent_id]['children'][] = $data;
                }
            } else {
                // Standard Simple
                $order_data_array[$item_id]['object'] = $item;
                $order_data_array[$item_id]['data'] = $item->getData();
            }
        }

    }

    /**
     *
     */

    function getOrderItemsShort(
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

    /**
     * @param object $order
     *
     * @return string
     */
    function getOrderOutput(object $order) {

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
        };

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
     * @param object $order
     * @param object $address
     *
     * @return string
     */
    function getAddressOutput(
        object $order,
        object $address
    ) {

        $type = $address->getAddressType();

        if (strcmp($type, 'billing')) {
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
            if (empty($order->getCustomerFirstname())) {
                $address = $order->getBillingAddress();
                $full_name = $address->getFirstname() . " " . $address->getLastname();

            } else {
                $full_name = $order->getCustomerFirstname() . " " . $order->getCustomerLastname();
            }
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
        };

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

    function getBillingAddressOutput(
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

    function getShippingAddressOutput(
        $order,
        $shippingAddress
    ) {
        $output = '<div class="shipping-address">';

        $shippingName = '';
        $shippingAddressId = $order->getShippingAddressId();
        $shippingAddress = $this->orderAddressRepo->get($shippingAddressId);
        $shippingName = $shippingAddress->getFirstname() . " " . $shippingAddress->getLastname();

        $output .= '<div class="ship-name">' . $shippingName . '</div>';
        $output .= '<div class="ship-address">' . $shipping . '</div>';

        $output .= '</div>';

        return $output;
    }
}