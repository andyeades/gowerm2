<?php
namespace Elevate\Delivery\Controller\Delivery;

use DateTime;
use DateInterval;
use DatePeriod;

class Getprice extends \Magento\Framework\App\Action\Action
{
    protected $cutofftime_fornextworkingday;
    protected $cutofftime_fornextworkingday_2digit;
    protected $days_to_show_after_startdate;
    protected $working_days;
    protected $non_working_days;


    protected $resultPageFactory;
    protected $jsonHelper;

    protected $cartData;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $deliveryArea;

    protected $deliveryMethod;

    protected $deliveryProducts;

    protected $deliveryFee;

    protected $deliveryRulesProducts;

    protected $deliveryRules;

    protected $deliveryRulesType;

    protected $deliveryRulesFunctions;

    protected $searchCriteriaBuilder;
    protected $sortOrderBuilder;
    protected $filter;
    protected $filterBuilder;
    protected $filterGroup;
    protected $filterGroupBuilder;

    protected $logger;

    protected $checkoutSession;

    /**
     * @var mixed
     */
    protected $debugmode;

    /**
     * @var
     */
    protected $loggingdebugmode;

    /**
     * @var \Elevate\Delivery\Api\HolidaydatesRepositoryInterface
     */
    protected $holidayDatesRepository;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Elevate\Delivery\Api\DeliveryFeeRepositoryInterface $deliveryFee,
        \Elevate\Delivery\Api\DeliveryMethodRepositoryInterface $deliveryMethod,
        \Elevate\Delivery\Api\DeliveryProductsRepositoryInterface $deliveryProducts,
        \Elevate\Delivery\Api\DeliveryAreaRepositoryInterface $deliveryArea,
        \Elevate\Delivery\Api\DeliveryRulesRepositoryInterface $deliveryRules,
        \Elevate\Delivery\Api\DeliveryRulesCombinationsRepositoryInterface $deliveryRulesCombinations,
        \Elevate\Delivery\Api\DeliveryRulesProductsRepositoryInterface $deliveryRulesProducts,
        \Elevate\Delivery\Api\DeliveryRulesTypeRepositoryInterface $deliveryRulesType,
        \Elevate\Delivery\Api\DeliveryRulesFunctionsRepositoryInterface $deliveryRulesFunctions,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Elevate\Delivery\Api\HolidaydatesRepositoryInterface $holidayDatesRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->cartData = $cartRepository;
        $this->deliveryArea = $deliveryArea;
        $this->deliveryFee = $deliveryFee;
        $this->deliveryMethod = $deliveryMethod;
        $this->deliveryProducts = $deliveryProducts;
        $this->deliveryRules = $deliveryRules;
        $this->deliveryRulesCombinations = $deliveryRulesCombinations;
        $this->deliveryRulesProducts = $deliveryRulesProducts;
        $this->deliveryRulesType = $deliveryRulesType;
        $this->deliveryRulesFunctions = $deliveryRulesFunctions;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filter = $filter;
        $this->filterGroup = $filterGroup;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->checkoutSession = $checkoutSession;
        $this->holidayDatesRepository = $holidayDatesRepository;
        $this->debugmode = $this->scopeConfig->getValue('delivery_options/general/debugmode');
        $this->loggingdebugmode = $this->scopeConfig->getValue('delivery_options/general/loggingdebugmode');

        // TODO: Change these to Admin Config Vars
        $this->cutofftime_fornextworkingday = 1100;
        $this->cutofftime_fornextworkingday_2digit = 11;
        $this->days_to_show_after_startdate = 90;
        $this->working_days = array(1,2,3,4,5);
        $this->non_working_days = array(6,7);

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $set_method_id = $this->getRequest()->getParam('method');
            $postcode = $this->getRequest()->getParam('postcode');
            $country = $this->getRequest()->getParam('country');
            $date = $this->getRequest()->getParam('date');
            // parse Date



            $date_array = explode('_',$date);

            $actual_date = $date[0];




            $price = $this->getRequest()->getParam('price');

            $input_id = $this->getRequest()->getParam('inputid');
            $delivery_area = $this->getRequest()->getParam('deliveryarea');

            $method_range_dates = $this->getRequest()->getParam('rangedates');
            $delivery_summary_text = $this->getRequest()->getParam('summarytext');
            $delivery_team_number = $this->getRequest()->getParam('teamnumber');


            // Check Fee is correct (stop js hijack)


            $selected_date = strtotime($actual_date);
            $selected_date = date("N", $selected_date);

            // Log some stuff
            $this->logger->info("Validate - MethodSelected: $set_method_id - $postcode - $country - $date - Price: $price  - TeamNumber: $delivery_team_number - SummaryTxt: $delivery_summary_text - ");


            if (!empty($date_array)) {

                $cart_items = $this->getCartItems();

                $cart_items_data = array();
                $cart_productids = array();
                $cart_qty_data = array();
                $cart_attribute_sets_counts[] = array();

                $cart_items_handling_times = array();
                $cart_items_date_next_available = array();

                foreach ($cart_items as $item) {

                    if (strcmp($item->getProductType(), 'configurable') == 0) {
                        // Configurable - get Simple Id
                        $children = $item->getChildren();
                        // We can't presume it's the only child, can we...?
                        $product_id = $children[0]->getProductId();
                        $product_sku = $children[0]->getSku();
                        $qty = $children[0]->getQty();

                        $full_data = $children[0];

                        $attribute_set_id = $full_data->getProduct()->getAttributeSetId();
                        $date_next_available = $full_data->getDateNextAvailable();
                        $handling_time = $full_data->getHandlingTime();

                    } else {
                        $product_id = $item['product_id'];
                        $product_sku = $item['sku'];
                        $qty = $item['qty'];
                        $full_data = $item;
                        $attribute_set_id = $full_data->getProduct()->getAttributeSetId();
                        $date_next_available = $full_data->getDateNextAvailable();
                        $handling_time = $full_data->getHandlingTime();
                    }

                    if (isset($date_next_available)) {
                        // replace slashes with dashes
                        $date = str_replace('/', '-', $date_next_available);
                        $date_val = strtotime($date);
                        $cart_items_date_next_available[] = $date_val;
                    }

                    $cart_items_handling_times[] = intval($handling_time);

                    $cart_items_data[$product_id] = array(
                        'full_data'           => $full_data,
                        'product_id'          => $product_id,
                        'sku'                 => $product_sku,
                        'attribute_set_id'    => $attribute_set_id,
                        'qty'                 => $qty,
                        'handling_time'       => $handling_time,
                        'date_next_available' => $date_next_available,
                    );

                    if (isset($cart_attribute_sets_counts[$attribute_set_id])) {
                        $cart_attribute_sets_counts[$attribute_set_id] += 1;
                    } else {
                        $cart_attribute_sets_counts[$attribute_set_id] = 1;
                    }

                    $cart_productids[] = $product_id;
                    $cart_skus[] = $product_sku;
                    $cart_qty_data[$product_id] = $qty;
                }
                rsort($cart_items_date_next_available);
                rsort($cart_items_handling_times);

                if (!empty($cart_items_handling_times)) {
                    $longest_handling_time = $cart_items_handling_times[0];
                } else {
                    $longest_handling_time = 0;
                }

                if (!empty($cart_items_date_next_available)) {
                    $furthest_date = $cart_items_date_next_available[0];
                } else {
                    $furthest_date = NULL;
                }

                $cart_items_data_more = array(
                    'handling_time'       => $longest_handling_time,
                    'date_next_available' => $furthest_date
                );

                $cart_productids = implode(",", $cart_productids);

                $cart_skus = implode(",", $cart_skus);

                $delivery_products = $this->getDeliveryProducts($cart_productids)->getItems();

                // Single Products?
                //$delivery_products = $this->getDeliveryProductsBySku($cart_skus)->getItems();

                $delivery_methods_available = array();

                foreach ($delivery_products as $product) {
                    $delivery_product_data = $product->getAllData();
                    $delivery_method_id = $delivery_product_data['deliverymethod_id'];
                    $delivery_product_id = $delivery_product_data['product_id'];

                    $delivery_methods_available[] = intval($delivery_method_id);
                }

                $delivery_methods_available = array_unique($delivery_methods_available);

                $delivery_areas = $this->getDeliveryAreas()->getItems();

                $match = false;
                $delivery_area_match = NULL;

                foreach ($delivery_areas as $delivery_area) {

                    // TODO: Do we want to implement the store_id check? Probably will down the line

                    // Check if Enabled
                    if ($delivery_area->getEnabled() != 1) {
                        // Area Disabled - Skip?
                        continue;
                    }

                    if ($country == 'IE') {
                        // TODO: Need better solution.
                        if ($delivery_area->getDeliveryAreaId() == 3) {
                            $delivery_area_match = $delivery_area;

                        } else {
                            continue;
                        }

                    } else {
                        // Check if Postcode is in Delivery Area
                        $match = self::checkPostcode($postcode, $delivery_area);

                        if ($match === true) {
                            // Found Match! - No need to check anymore... (if there's another match then module isn't setup correctly so lets not waste processing time)
                            $delivery_area_match = $delivery_area;
                            break;
                        }
                    }

                }

                if (empty($delivery_area_match)) {
                    // No matched area, return with message/etc?
                    return false;
                }

                // Now we have our matched area, we need to get the Calendar Data/Fees/Etc

                // Get the Data on the Area
                $delivery_area_match = $delivery_area_match->getAllData();

                // Find all Methods Applicable to Area
                $filters = array(
                    array(
                        'field' => 'deliveryarea_id',
                        'value' => $delivery_area_match['deliveryarea_id']
                    ),
                    // We only want enabled methods
                    array(
                        'field' => 'enabled',
                        'value' => 1
                    )
                );

                $sortorder = array(
                    'field'     => 'deliverymethod_id',
                    'direction' => 'DESC'
                );

                $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                $delivery_methods = $this->deliveryMethod->getList($searchCriteria);
                $delivery_methods = $delivery_methods->getItems();

                $delivery_methods_forarea_full = array();
                $delivery_area_fallbackmethods = array();
                $delivery_methods_matched = array();

                foreach ($delivery_methods as $delivery_method) {
                    $delivery_method_data = $delivery_method->getAllData();
                    $delivery_method_id = $delivery_method_data['deliverymethod_id'];
                    $delivery_area_id = $delivery_method_data['deliveryarea_id'];

                    $filters = array(
                        array(
                            'field' => 'deliverymethod_id',
                            'value' => $delivery_method_id
                        ),
                        array(
                            'field' => 'deliveryarea_id',
                            'value' => $delivery_area_id
                        )
                    );

                    $sortorder = array(
                        'field'     => 'day',
                        'direction' => 'ASC'
                    );

                    // Get the Fees for the Days
                    $delivery_fees_data = $this->getFeeForMethod($filters, $sortorder);

                    $delivery_method_data['delivery_fees'] = $delivery_fees_data;

                    if ($delivery_method_data['is_fallback_method'] == 1) {
                        $delivery_area_fallbackmethods[$delivery_area_id][$delivery_method_id] = $delivery_method_data;
                    }

                    $delivery_methods_forarea_full[$delivery_method_id] = $delivery_method_data;
                    $delivery_methods_matched[$delivery_method_id] = $delivery_method_data;
                }

                $delivery_area_matched_id = $delivery_area_match['deliveryarea_id'];

                $removed_methods = array();

                $delivery_methods_matched_ids = array();

                // Remove Methods Not Allowed in Area/etc
                if (!empty($delivery_products)) {
                    foreach ($delivery_methods_matched as $key => $value) {

                        $delivery_methods_matched_ids[] = $key;

                        if (!in_array($key, $delivery_methods_available)) {
                            // Method_id (Key) isn't allowed for current products - remove it!
                            $removed_methods[$key] = $value;
                            unset($delivery_methods_matched[$key]);
                        }

                    }
                }

                // Left with Methods for the products;

                // Delivery Rules - There are Multiple ways of settings Rules
                // Product Specific
                // Area Specific (global area rules - applying to all products in area)
                // etc

                // First check the Rules_Products Table

                // Delivery Rules Products
                // Get Specific Product Rules which apply to products in this cart of products

                $filters = array(
                    array(
                        'field'          => 'product_id',
                        'value'          => $cart_productids,
                        'condition_type' => 'in'
                    )
                );

                $sortorder = array(
                    'field'     => 'deliveryrulesproducts_id',
                    'direction' => 'DESC'
                );

                $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                $deliveryRulesProducts = $this->deliveryRulesProducts->getList($searchCriteria);

                $deliveryRulesProductsCount = $deliveryRulesProducts->getTotalCount();

                // Capture Global ALL product rules
                // Get Global Rules for Products

                $filters = array(
                    array(
                        'field' => 'product_sku',
                        'value' => '*',
                    )
                );

                $sortorder = array(
                    'field'     => 'deliveryrulesproducts_id',
                    'direction' => 'DESC'
                );

                $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                $deliveryRulesAllProducts = $this->deliveryRulesProducts->getList($searchCriteria);

                $deliveryRulesAllProductsCount = $deliveryRulesAllProducts->getTotalCount();

                $deliveryRulesAllProductsItems = array();
                $deliveryRulesProductsItems = array();

                if (!empty($deliveryRulesProductsCount) || (!empty($deliveryRulesAllProductsCount))) {
                    // Rules are set on Product(s) in basket

                    // Delivery Rules

                    $delivery_rules_matched_ids = array();

                    // Global Rules

                    $deliveryRulesCombined = array();

                    $deliveryRulesAllProductsItems = $deliveryRulesAllProducts->getItems();
                    // Product Specific Rules
                    $deliveryRulesProductsItems = $deliveryRulesProducts->getItems();

                    $deliveryRulesCombined = array_merge($deliveryRulesAllProductsItems, $deliveryRulesProductsItems);

                    foreach ($deliveryRulesCombined as $deliveryRule) {
                        $delivery_rules_matched_ids[] = $deliveryRule->getDeliveryrulesId();
                    }

                    $delivery_rules_matched_ids_csv = implode(",", $delivery_rules_matched_ids);

                    // Get Delivery Rules
                    $deliveryRules = $this->getDeliveryRules($delivery_rules_matched_ids_csv);

                    $deliveryRulesCount = $deliveryRules->getTotalCount();

                    $combinationDeliveryRulesSingles = array();

                    // Store Removed Methods in Array
                    $delivery_methods_removed = array();

                    if (!empty($deliveryRulesCount)) {
                        // Matched one or more delivery Rules
                        $deliveryRulesItems = $deliveryRules->getItems();
                        foreach ($deliveryRulesItems as $deliveryRule) {
                            $deliveryRuleType = $this->deliveryRulesType->getById(intval($deliveryRule->getDeliveryrulesTypeId()))->getRuleType();
                            $deliveryRuleFunction = $this->deliveryRulesFunctions->getById(intval($deliveryRule->getDeliveryrulesfunctionId()))->getDeliveryrulesFunction();
                            // 4 is Combination/Meta Rule

                            if (strcmp("Meta Rule - Do Nothing", $deliveryRuleFunction) == 0) {
                                // This is a catch for rules inadvertantly set on this setting - as these are processed later we will skip these
                                continue;
                            }

                            $deliveryRuleFunctionPerform = $deliveryRule->getRuleFunction();
                            $deliveryRuleQuantity = $deliveryRule->getRuleQuantity();
                            $deliveryRuleOperator = $deliveryRule->getRuleOperator();
                            $deliveryRuleMode = $deliveryRule->getRuleMode();

                            // Add // Remove // Remove All But ?

                            if (strcmp("Total Basket Qty", $deliveryRuleType) == 0) {
                                // Total Basket Quantity Based Rule

                                $cart_total_quantity = array_sum($cart_qty_data);

                                if (strcmp($deliveryRuleOperator, ">=") == 0) {
                                    // Greater Than or Equal to

                                    if (intval($cart_total_quantity) >= $deliveryRuleQuantity) {
                                        // RULE SATISFIED!
                                        // Do Something
                                        $something = 1;
                                        if (strcmp($deliveryRuleFunction, 'Add') == 0) {
                                            // Add another Method to the Available Methods

                                            $newMethod = $this->deliveryMethod->getById(intval($deliveryRuleFunctionPerform))->getAllData();
                                            // Add to Array of Matched Methods
                                            $newMethod_fees = $this->getDeliveryFees($newMethod['deliverymethod_id'], $newMethod['deliveryarea_id'], 'day', 'ASC');
                                            $newMethod['delivery_fees'] = $newMethod_fees;
                                            $newMethod_id = $newMethod['deliverymethod_id'];
                                            $delivery_methods_matched[$newMethod_id] = $newMethod;

                                        } else if (strcmp($deliveryRuleFunction, 'Remove') == 0) {
                                            // Remove Method X
                                            foreach ($delivery_methods_matched as $key => $delivery_method) {
                                                if (intval($deliveryRuleFunctionPerform) == intval($delivery_method['deliverymethod_id'])) {
                                                    // found the one to remove
                                                    $delivery_methods_removed[$key] = $delivery_method;
                                                    unset($delivery_methods_matched[$key]);
                                                }
                                            }
                                        } else if (strcmp($deliveryRuleFunction, 'Remove All But') == 0) {
                                            // remove all but method X

                                            foreach ($delivery_methods_matched as $key => $delivery_method) {
                                                if (intval($deliveryRuleFunctionPerform) != intval($delivery_method['deliverymethod_id'])) {
                                                    // Remove this one
                                                    $delivery_methods_removed[$key] = $delivery_method;
                                                    unset($delivery_methods_matched[$key]);
                                                }
                                            }
                                        }
                                    }
                                }

                            } else if (strcmp("Product Qty", $deliveryRuleType) == 0) {
                                // TODO: CODE THIS!
                                // Based on the Quantity of a particular Product]
                                // Does the Basket Satisfy the Rule? if not then:
                                continue;
                            } else if (strcmp("Product Attribute Set Qty", $deliveryRuleType) == 0) {
                                // Based on the Quantity of a particular attribute set in the cart

                                // TODO: CODE THIS! - In Theory We could have rules based on if a product attribute set is X or Y Qty on it's own

                                // Does the Basket Satisfy the Rule? if not then:
                                continue;
                            }

                        }

                    }

                    // Product Specific/Global Product Rules Processed

                }

                //delivery_methods_matched == Results so far

                // Combination Rules

                // check the combination rules for this area

                $filters = array(
                    array(
                        'field' => 'combo_area_id',
                        'value' => $delivery_area_matched_id
                    ),
                    // We only want enabled combo rules
                    array(
                        'field' => 'combo_rule_enable',
                        'value' => 1
                    )
                );

                $sortorder = array(
                    'field'     => 'combo_rule_sort_value',
                    'direction' => 'DESC'
                );

                $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                $deliveryRulesCombinations = $this->deliveryRulesCombinations->getList($searchCriteria);

                $deliveryRulesCombinationsCount = $deliveryRulesCombinations->getTotalCount();

                if (!empty($deliveryRulesCombinationsCount)) {
                    // Matched one or more delivery Rules
                    $deliveryRulesCombinationsItems = $deliveryRulesCombinations->getItems();

                    foreach ($deliveryRulesCombinationsItems as $deliveryRuleCombination) {

                        $deliveryRulesCombo_current = array();
                        // Rules which need to be valid in combination
                        $combo_rule_id = $deliveryRuleCombination->getDeliveryrulescombinationsId();
                        $deliveryrules_ids = $deliveryRuleCombination->getDeliveryrulesIds();

                        $combo_rule_sort_value = $deliveryRuleCombination->getComboRuleSortValue();
                        $combo_rule_stop_continue = $deliveryRuleCombination->getComboRuleStopContinue();

                        $deliveryrules_ids_array = explode(',', $deliveryrules_ids);
                        $deliveryrules_ids_count = count($deliveryrules_ids_array);

                        $deliveryRules = $this->getDeliveryRules($deliveryrules_ids);
                        $deliveryRulesCount = $deliveryRules->getTotalCount();

                        if (!empty($deliveryRulesCount)) {
                            // At least one of them exists
                            if ($deliveryRulesCount != $deliveryrules_ids_count) {
                                // Well this is a problem - surely?
                            }
                            // Loop Through Each Now to Check

                            $deliveryRulesCombo_matched = array();
                            $deliveryRulesItems = $deliveryRules->getItems();

                            foreach ($deliveryRulesItems as $deliveryRule) {
                                $delivery_rule_id = $deliveryRule->getDeliveryrulesId();
                                $deliveryRuleType = $this->deliveryRulesType->getById(intval($deliveryRule->getDeliveryrulesTypeId()))->getRuleType();

                                $deliveryRuleFunction = $this->deliveryRulesFunctions->getById(intval($deliveryRule->getDeliveryrulesfunctionId()))->getDeliveryrulesFunction();

                                // Only Interested in Meta Rules Obviously

                                // 4 is Combination/Meta Rule

                                if (strcmp("Meta Rule - Do Nothing", $deliveryRuleFunction) == 0) {

                                    // Qty of something to Match Agaisnt
                                    $deliveryRuleQuantity = $deliveryRule->getRuleQuantity();

                                    // For Matching Agaisnt
                                    $deliveryRuleValue = $deliveryRule->getRuleValue();

                                    $deliveryRuleOperator = $deliveryRule->getRuleOperator();

                                    if (strcmp($deliveryRuleOperator, ">=") == 0) {

                                    } else if (strcmp($deliveryRuleOperator, "==") == 0) {

                                        // RulesTypeID 3 is Product Attribute Set -- But StrCMP to be safe

                                        if (strcmp($deliveryRuleType, "Product Attribute Set Qty") == 0) {
                                            // Yes This Rule is Attribute Set Based
                                            if (isset($cart_attribute_sets_counts[$deliveryRuleValue])) {
                                                if ($cart_attribute_sets_counts[$deliveryRuleValue] == $deliveryRuleQuantity) {
                                                    // Yes this rule is matched
                                                    $deliveryRulesCombo_current[$delivery_rule_id] = true;
                                                    $deliveryRulesCombo_matched[$combo_rule_id]['individualrules'][$delivery_rule_id] = true;
                                                    continue;
                                                } else {
                                                    // Uh-oh?
                                                }
                                            }

                                        }

                                    } else if (strcmp($deliveryRuleOperator, "<=") == 0) {

                                    }

                                } else {
                                    continue;
                                }

                            }

                        }

                        // This Combo Rule has been checked
                        $deliveryComboRuleCheck_true = count(array_filter($deliveryRulesCombo_current));

                        // Function Type (Remove/Add) to perform if rules are all true
                        $deliveryrulesfunction = $this->deliveryRulesFunctions->getById(intval($deliveryRuleCombination->getDeliveryrulesfunctionId()))->getDeliveryrulesFunction();

                        if (isset($deliveryComboRuleCheck_true)) {
                            if ($deliveryComboRuleCheck_true == $deliveryRulesCount) {
                                // All Are Valid
                                // Apply Rule!

                                // What Methods to perform said function on
                                $combo_rule_function = $deliveryRuleCombination->getComboRuleFunction();

                                $combo_rule_function_ids = explode(',', $combo_rule_function);

                                if (count($combo_rule_function_ids) > 1) {

                                    if (strcmp($deliveryrulesfunction, 'Remove All Except') == 0) {

                                        // This one ADDS any missing as well as removing

                                        foreach ($delivery_methods_matched as $key => $value) {
                                            if (!in_array($key, $combo_rule_function_ids)) {
                                                $delivery_methods_removed[$key] = $value;
                                                unset($delivery_methods_matched[$key]);
                                            }
                                        }
                                        // Add Any Missing Ones

                                        foreach ($combo_rule_function_ids as $delivery_method) {
                                            if (!in_array($delivery_method, $delivery_methods_matched)) {
                                                // TODO - if this is retrieved and stored already? use that store? // not stored by key at present
                                                $newMethod = $this->deliveryMethod->getById(intval($delivery_method))->getAllData();
                                                // Add to Array of Matched Methods
                                                $newMethod_fees = $this->getDeliveryFees($newMethod['deliverymethod_id'], $newMethod['deliveryarea_id'], 'day', 'ASC');
                                                $newMethod['delivery_fees'] = $newMethod_fees;
                                                $newMethod_id = $newMethod['deliverymethod_id'];
                                                $delivery_methods_matched[$newMethod_id] = $newMethod;
                                            }

                                        }
                                    }
                                    if (strcmp($deliveryrulesfunction, 'Remove All Except (And Do Not Add Missing Options)') == 0) {
                                        foreach ($delivery_methods_matched as $key => $value) {
                                            if (!in_array($key, $combo_rule_function_ids)) {
                                                $delivery_methods_removed[$key] = $value;
                                                unset($delivery_methods_matched[$key]);
                                            }
                                        }
                                    }

                                } else {
                                    foreach ($combo_rule_function_ids as $delivery_method) {
                                        if (strcmp($deliveryrulesfunction, 'Add') == 0) {
                                            //TODO: IMplement THis Function
                                        }
                                        if (strcmp($deliveryrulesfunction, 'Remove') == 0) {
                                            //TODO: IMplement THis Function
                                        }

                                        if (strcmp($deliveryrulesfunction, 'Meta Rule - Do Nothing') == 0) {
                                            //TODO: IMplement THis Function
                                        }

                                    }
                                }

                            } else {
                                if (strcmp($deliveryrulesfunction, 'Add') == 0) {
                                    //TODO: IMplement THis Function
                                }
                                if (strcmp($deliveryrulesfunction, 'Remove') == 0) {
                                    //TODO: IMplement THis Function
                                }
                                if (strcmp($deliveryrulesfunction, 'Remove All Except') == 0) {
                                    //TODO: IMplement THis Function

                                }

                                if (strcmp($deliveryrulesfunction, 'Meta Rule - Do Nothing') == 0) {
                                    //TODO: IMplement THis Function
                                }
                            }

                        }
                    }

                }

                // All Combo Rules Checked

                $test = 0;
                $output = '';


                if (count($delivery_methods_matched) > 0) {
                    if (isset($delivery_methods_matched[$set_method_id])) {
                        // Ok We have a method match (good start)
                        if (isset($delivery_methods_matched[$set_method_id]['delivery_fees'][$actual_date])) {
                            // Price set for day - also good!
                            $fee_for_day = $delivery_methods_matched[$set_method_id]['delivery_fees'][$actual_date]['fee'];
                            $output = array(
                                'valid' => true,
                                'fee_for_day' => $fee_for_day,
                            );

                        }
                    } else {
                        // No method match (in actual matches)
                        // Return Error
                        $output = array('error_message' => 'Please select a different day/delivery option and try again.');
                    }
                } else {
                    // Fall Back Methods Check
                    $fallback_methods_count = count($delivery_area_fallbackmethods[$delivery_area_id]);

                    if ($fallback_methods_count > 0) {
                        // Fall back method exists - we need to add the methods missing
                        // TODO: What if the method is one previously removed? Hmm

                        foreach ($delivery_area_fallbackmethods[$delivery_area_id] as $deliverymethod_toadd) {
                            $deliverymethod_toadd_id = $deliverymethod_toadd['deliverymethod_id'];

                            // add Method to array
                            $delivery_methods_matched[$deliverymethod_toadd_id] = $deliverymethod_toadd;
                        }

                        if (isset($delivery_methods_matched[$set_method_id])) {

                            if (isset($delivery_methods_matched[$set_method_id]['delivery_fees'][$actual_date])) {
                                // Price set for day - also good!
                                $fee_for_day = $delivery_methods_matched[$set_method_id]['delivery_fees'][$actual_date]['fee'];
                                $output = array(
                                    'valid' => true,
                                    'fee_for_day' => $fee_for_day,
                                );

                            } else {

                                $delivery_methods_matched[$set_method_id]['delivery_fees'] = $this->getDeliveryFees($set_method_id, $delivery_methods_matched[$set_method_id]['deliveryarea_id'], 'day', 'ASC');

                                if (isset($delivery_methods_matched[$set_method_id]['delivery_fees'][$actual_date])) {
                                    // Price set for day - also good!
                                    $fee_for_day = $delivery_methods_matched[$set_method_id]['delivery_fees'][$actual_date]['fee'];
                                    $output = array(
                                        'valid'       => true,
                                        'fee_for_day' => $fee_for_day,
                                    );
                                } else {

                                    $this->logger('Error - Number 5');
                                    $output = array('error_message' => 'Error please try again');
                                }

                            }

                        } else {
                            $this->logger('Error - Number 6');
                            $output = array('error_message' => 'Error please try again');
                        }

                    } else {
                        // On The off Chance no Method is set as a fall back
                        $output = array('error_message' => 'Please select a delivery method and try again.');
                    }

                }


                return $this->jsonResponse($output);

            }



        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }


    /**
     * @param string $delivery_rules_matched_ids_csv
     *
     * @return object
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDeliveryRules(string $delivery_rules_matched_ids_csv) {

        $filters = array(
            array(
                'field'          => 'deliveryrules_id',
                'value'          => $delivery_rules_matched_ids_csv,
                'condition_type' => 'in'
            ),
            // We only want enabled methods
            array(
                'field' => 'rule_enable',
                'value' => 1
            )
        );

        $sortorder = array(
            'field'     => 'rule_sort_value',
            'direction' => 'DESC'
        );

        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
        $deliveryRules = $this->deliveryRules->getList($searchCriteria);

        return (object)$deliveryRules;
    }

    public function processDeliveryRules(
        $deliveryMethods,
        $deliveryRules,
        $deliveryRulesProducts
    ) {


        return $something;
    }

    public function getDeliveryFees($delivery_method_id, $delivery_area_id, $sort_order_field, $sort_order_direction) {

        $filters = array(
            array(
                'field' => 'deliverymethod_id',
                'value' => $delivery_method_id
            ),
            array(
                'field' => 'deliveryarea_id',
                'value' => $delivery_area_id
            )
        );

        $sortorder = array(
            'field' => "$sort_order_field",
            'direction' => "$sort_order_direction"
        );

        $delivery_fees_data = $this->getFeeForMethod($filters, $sortorder);
        // Get the Feeds for the Days

        return $delivery_fees_data;
    }
    /**
     * @return object
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDeliveryAreas(): object {
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $areas = $this->deliveryArea->getList($searchCriteria);

        return $areas;
    }

    /**
     *
     * Build Search Criteria
     *
     *
     * @param array $filters
     * @param array $sortorder
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function buildSearchCriteria(array $filters, array $sortorder) {

        $filtergroups = $this->buildFilters($filters,$sortorder);

        $sortorder = $this->buildSortOrder($sortorder);

        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups($filtergroups)->setSortOrders([$sortorder])->create();

        return $searchCriteria;
    }

    /**
     *
     * Builds Filters & Filter Groups (For AND based Queries) based on an array of filters and array of sortorders
     *
     * Example:
     *  $filters = array( array(
     * 'field' => 'deliverymethod_id',
     * 'value' => $delivery_method_id,
     * 'condition_type' => 'in'),
     *   array(
     * 'field' => 'deliveryarea_id',
     * 'value' => $delivery_area_id,
     * 'condition_type' => 'like'),
     * );
     *
     * condition_type is not needed, will be defaulted to eq if not present
     *
     * $sortOrder = array(
     * 'field' => 'day',
     * 'direction' => 'DESC'
     * );
     *
     *
     * @param $filters
     * @param $sortorder
     *
     * @return array
     */
    public function buildFilters(array $filters, array $sortorder) : array {

        foreach ($filters as $filter) {
            if (array_key_exists('condition_type', $filter)) {
                $filters_built[] = $this->filterBuilder->create()->setField($filter['field'])->setValue($filter['value'])->setConditionType($filter['condition_type']);
            } else {
                // Will be default Condition Type (i.e. eq)
                $filters_built[] = $this->filterBuilder->create()->setField($filter['field'])->setValue($filter['value']);
            }

        }

        foreach ($filters_built as $filter) {
            $filter_groups[] = $this->filterGroupBuilder->create()->setFilters([$filter]);
        }

        return $filter_groups;
    }

    /**
     *
     * Builds a SortOrder Query for SearchCriteria
     *
     *
     * $sortOrder = array(
     * 'field' => 'day',
     * 'direction' => 'DESC'
     * );
     *
     * @param array $sortorder
     *
     * @return \Magento\Framework\Api\SortOrder
     */
    public function buildSortOrder(array $sortorder) {

        $field = $sortorder['field'];
        $direction = $sortorder['direction'];

        return $this->sortOrderBuilder->setField($field)->setDirection($direction)->create();
    }

    /**
     * @param $filters
     * @param $sortorder
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFeeForMethod($filters, $sortorder) {

        $searchCriteria = $this->buildSearchCriteria($filters,$sortorder);
        $deliveryfees = $this->deliveryFee->getList($searchCriteria);

        $deliveryfees = $deliveryfees->getItems();

        $output_deliveryfees = array();

        // get the Data Easier
        $count = 0;
        foreach ($deliveryfees as $deliveryfee) {
            $count++;
            $day = intval($deliveryfee->getDay());
            $output_deliveryfees[$day] = $deliveryfee->getAllData();
        }

        if ($count > 7) {
            $this->logger->info('Should only be 7 fees (days) for a delivery method - remove extra');
            $this->logger->info(print_r($deliveryfees,true));
        }

        return $output_deliveryfees;
    }

    public function checkPostcode($postcode, $delivery_area) {

        $postcode = strtoupper(trim($postcode));
        $postcodes = explode(";", $delivery_area->getPostcodes());
        $postcodesCount = 0;

        //get the short postcode (as we are only matching by the first part of the code for a uk code)

        if (preg_match('/ ?([0-9])[ABD-HJLNP-UW-Z]{2}$/i', $postcode, $match, PREG_OFFSET_CAPTURE)) {
            $postcode = substr($postcode, 0, $match[0][1]);
        }

        foreach ($postcodes as $postcode_from_list) {

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

                    $postcodevaluerange = array_values(array_filter(explode($postcodeletters, $postcode_from_list), "Self::RemoveFalseButNotZero"));
                    if (strpos($postcodevaluerange[0], '-')) {
                        //remove - from array value
                        $postcodevaluerange[0] = substr($postcodevaluerange[0], 0, strpos($postcodevaluerange[0], '-'));
                    }
                    $rangelower = $postcodevaluerange[0];
                    $rangeupper = $postcodevaluerange[1];

                    $postcodenumericrange = range($rangelower, $rangeupper);

                    foreach ($postcodenumericrange as &$value) {
                        $value = $postcodeletters . $value;
                    }
                    unset($value);
                    unset($postcodeletters);

                    if (in_array($postcode, $postcodenumericrange)) {
                        return true;
                    }
                }
            }

        }

        return false;
    }

    public function RemoveFalseButNotZero($value) {
        return ($value || is_numeric($value));
    }

    /**
     * Count the number of days where the method is "Free Delivery"
     *
     *
     *
     * @param array $delivery_fees
     * @param array $delivery_days
     *
     * @return int
     */
    public function getFreeDeliveryDaysCount(array $delivery_fees, array $delivery_days) : int {

        $count = 0;

        // TODO:: ADD a Check if Fee isn't set to stop error
        foreach ($delivery_days as $key => $day) {
            $delivery_fee_for_day = $delivery_fees[$key]['fee'];
            if ($delivery_fee_for_day == '0.0000') {
                // Free
                $count++;
            }
        }

        return $count;
    }

    public function getDeliveryOutput($deliverymethods, $cart_item_data) {

        $itemcount = 1;
        $debug_mode = 0;
        $isCheckout = 1;


        $custom_type_heading = 0;
        $date = new DateTime('NOW');

        // TODO: Setup Database Table and Interface for adding

        $holidays_temp = '2019-08-26;2019-12-25;2019-12-26;';

        //Get Holidays Set in Admin
        $holidays = explode(";", $holidays_temp);
        $seperateholidaydates = $holidays;
        // Check Bank Holidays / Excluded Days
        // TODO:: Create Database Class/etc


        $deliverymethodcount = 1;
        $actualoptionsvisible = 0;
        $start_day_for_deliverymethod_options = array();
        $deliverymethod_first_dispatch_dates = array();
        $deliverymethod_first_delivery_dates = array();
        $deliverymethod_first_pickable_option_day = array();
        $deliverymethod_last_date_to_show = array();
        $deliverymethod_daterange_interval = array();
        $deliverymethod_delivery_desc = array();
        $deliverymethod_delivery_desc_checkout = array();
        $deliverymethod_available_from_date = array();
        $deliverymethod_range_end_date_foroutput = array();
        $deliverymethod_fee_list = array();
        $deliverymethod_free_days_count = array();


        foreach ($deliverymethods as $key => &$deliverymethod) {

            foreach ($deliverymethod as $key => $property) {
                $deliverymethod_sorted[$key] = $property;
            }



            $deliverymethod_id = $deliverymethod['deliverymethod_id'];
            $deliveryarea_id = $deliverymethod['deliveryarea_id'];
            $deliverymethod_name = $deliverymethod['name'];
            $deliverymethod_internalname = $deliverymethod['internal_name'];
            $deliverymethod_letter_code = $deliverymethod['internal_name'];
            $deliverymethod_delivery_days = array();
            $deliverymethod_non_deliverydays = array();
            for ($i = 1; $i < 8; $i++) {
                if (empty(intval($deliverymethod['day'.$i]))) {
                    // Not delivery on this day
                    $deliverymethod_non_deliverydays[$i] = $deliverymethod['day'.$i];
                } else {
                    // Delivery on this day
                    $deliverymethod_delivery_days[$i] = $deliverymethod['day'.$i];
                }
            }
            // Get Free Count For Ordering Methods Displayed to User
            $deliverymethod_free_count = $this->getFreeDeliveryDaysCount($deliverymethod['delivery_fees'],$deliverymethod_delivery_days);


            $deliverymethod['delivery_freedelivery_count'] = $deliverymethod_free_count;
            $deliverymethod_enabled = $deliverymethod['enabled'];

            // Because the dates are stored in the database as a full date + time (and we only want the time...) Lets get them time alone


            $deliverymethodstarttime = $this->formatTimeElevate($deliverymethod['start_time']);
            $deliverymethodendtime = $this->formatTimeElevate($deliverymethod['end_time']);


            $deliverymethodbeforetime = $this->formatTimeElevate($deliverymethod['before_time']);
            $deliveryteamnumber = $deliverymethod['delivery_team_number'];
            $deliveryteamability = $deliverymethod['delivery_team_ability'];
            $deliverymethod_delivery_desc[$deliverymethod_id] = $deliverymethod['delivery_desc'];
            $deliverymethod_delivery_desc_checkout[$deliverymethod_id] = $deliverymethod['delivery_desc_checkout'];

            $deliverymethod_workingdays_min = $deliverymethod['workingdays_min'];

            // Add in in case dispatch_days is set!
            // TEMP TODO: FIX

            $workingDaysToAdd = 0;

            $deliverymethod_workingdays_min_adjusted = $deliverymethod_workingdays_min + $workingDaysToAdd;


            $deliverymethod['delivery_days'] = $deliverymethod_delivery_days;
            $deliverymethod['non_delivery_days'] = $deliverymethod_non_deliverydays;
            $deliverymethod['workingdays_min_adjusted'] = $deliverymethod_workingdays_min_adjusted;

            $deliverymethod_workingdays_max = $deliverymethod['workingdays_max'];

            $call_day_before = $deliverymethod['call_day_before'];
            $date_range_selection = $deliverymethod['date_range_selection'];

            $deliveryteamnumbertext = '';

            if ($deliveryteamnumber == 1) {
                $deliveryteamnumbertext = 'One Man';
            } else if ($deliveryteamnumber == 2) {
                $deliveryteamnumbertext = 'Two Man';
            }

            $deliverymethod['delivery_team_ability_text'] = $deliveryteamnumbertext;

            $currentdate = strtotime($date->format('Y-m-d'));


            // Finds first Dispatch and Delivery Date available for this Delivery Method
            $deliverymethod_first_dates_available = $this->findFirstDateAvailable($date, $seperateholidaydates, $deliverymethod_non_deliverydays, $deliverymethod_workingdays_min_adjusted);

            $deliverymethod['first_dates_available'] = $deliverymethod_first_dates_available;
            $deliverymethod['dispatch_day'] = $deliverymethod_first_dates_available[0];
            $deliverymethod['delivery_day'] = $deliverymethod_first_dates_available[1];

            $deliverymethod_dispatch_day = $deliverymethod_first_dates_available[0]; // Date-Time Object
            $deliverymethod_delivery_day = $deliverymethod_first_dates_available[1]; // Date-Time Object

            $deliverymethod_first_pickable_day = $deliverymethod_delivery_day->format('Y-m-d');
            $deliverymethod_first_pickable_day_date = new DateTime($deliverymethod_first_pickable_day);
            $deliverymethod_last_day_to_show = new DateTime($deliverymethod_first_pickable_day);

            $add_to_start_date_forend = $this->days_to_show_after_startdate;
            $deliverymethod_last_day_to_show->modify('+' . $add_to_start_date_forend . ' days');


            $deliverymethod['first_pickable_day'] = $deliverymethod_delivery_day->format('Y-m-d');
            $deliverymethod['first_pickable_day_date'] = new DateTime($deliverymethod_first_pickable_day);
            $deliverymethod['last_day_to_show'] = new DateTime($deliverymethod_first_pickable_day);


            // Check that day isn't one we don't deliver on and or is a holiday/etc

            $this->checkDateSuitability($deliverymethod_last_day_to_show, $deliverymethod_non_deliverydays, $seperateholidaydates);

            $deliverymethod_first_pickable_option_day[$deliverymethod_id] = $deliverymethod_first_pickable_day_date->format("Y-m-d");
            $deliverymethod_first_dispatch_dates[$deliverymethod_id] = strtotime($deliverymethod_dispatch_day->format("Y-m-d"));
            $deliverymethod_first_delivery_dates[$deliverymethod_id] = strtotime($deliverymethod_delivery_day->format("Y-m-d"));
            $deliverymethod_last_date_to_show[$deliverymethod_id] = strtotime($deliverymethod_last_day_to_show->format("Y-m-d"));

            if (!empty($date_range_selection)) {


                $deliverymethod_range_end_date = new DateTime($deliverymethod_first_pickable_day);
                $range_working_days_to_add = ($deliverymethod_workingdays_max - $deliverymethod_workingdays_min_adjusted);

                $range_interval = $this->dateRangeCheck($deliverymethod_range_end_date, $deliverymethod_delivery_day, $deliverymethod_non_deliverydays, $range_working_days_to_add, $seperateholidaydates);
                $deliverymethod_range_end_date_touse = new DateTime($deliverymethod_range_end_date->format("Y-m-d"));
                $deliverymethod_range_end_date_touse->modify('-1 days');

                $deliverymethod_range_end_date_foroutput[$deliverymethod_id] = $deliverymethod_range_end_date_touse->format("Y-m-d");
                $deliverymethod_daterange_interval[$deliverymethod_id] = $range_interval->d;
            }

            unset($deliverymethod);
            $deliverymethodcount++;
        }

        unset($deliverymethodcount);

        $_deliverymethod_first_dispatch_dates = $deliverymethod_first_dispatch_dates;
        $_deliverymethod_first_delivery_dates = $deliverymethod_first_delivery_dates;
        $_deliverymethod_last_date_to_show = $deliverymethod_last_date_to_show;
        $deliverymethod_sort_array = array();

        // Sort Temp Arrays - One Sort (for First Date) and RSORT for last date in range;
        //sort($_deliverymethod_first_dispatch_dates);
        sort($_deliverymethod_first_delivery_dates);
        rsort($_deliverymethod_last_date_to_show);


        $deliveryitemclass = '';

        // Change Check
        // Find First Delivery Date in Array Above and use that for first, and then the last delivery date and use as reservationend
        $deliverymethod_delivery_date_first = $deliverymethod_first_delivery_dates[$deliverymethod_id];
        $delivery_slider_start_date = $_deliverymethod_first_delivery_dates[0];
        $delivery_slider_last_date = $_deliverymethod_last_date_to_show[0];

        // Range of Days to show in Our Slider
        $dayslist = $this->createDateRange(date('Y-m-d', $delivery_slider_start_date), date('Y-m-d', $delivery_slider_last_date), 'Y-m-d');

        $mobile_day_options = array();

        foreach ($dayslist AS $day_key => $day) {

            $holidaycheck = $this->isDayHoliday($day, $holidays);

            $loop_day = strtotime($day);
            $loop_day = date("Y-m-d", $loop_day);
            $thisday = strtotime($day);
            $thisday = date("N", $thisday);


            $deliverymethodcount = 1;
            $deliverymethodTotalCount = count($deliverymethods);

            foreach ($deliverymethods as $deliverymethod) {

                $deliverymethod_id = $deliverymethod['deliverymethod_id'];
                $deliveryarea_id = $deliverymethod['deliveryarea_id'];
                $deliverymethod_name = $deliverymethod['name'];
                $deliverymethod_internalname = $deliverymethod['internal_name'];
                $deliverymethod_letter_code = $deliverymethod['internal_name'];

                $deliverymethod_non_deliverydays = $deliverymethod['non_delivery_days'];
                $deliverymethod_workingdays_min_adjusted = $deliverymethod['workingdays_min_adjusted'];
                $deliverymethod_teamnumber = $deliverymethod['delivery_team_number'];
                $deliverymethod_teamability = $deliverymethod['delivery_team_ability'];


                $deliverymethod_delivery_date_first = $deliverymethod_first_delivery_dates[$deliverymethod_id];

                $deliverymethod_enabled = $deliverymethod['enabled'];
                $deliverymethodstarttime = $this->formatTimeElevate($deliverymethod['start_time']);
                $deliverymethodendtime = $this->formatTimeElevate($deliverymethod['end_time']);
                $deliverymethodbeforetime = $this->formatTimeElevate($deliverymethod['before_time']);

                $deliverymethod_delivery_desc[$deliverymethod_id] = $deliverymethod['delivery_desc'];
                $deliverymethod_delivery_desc_checkout[$deliverymethod_id] = $deliverymethod['delivery_desc_checkout'];


                $deliverymethod_delivery_days = $deliverymethod['delivery_days'];
                $deliverymethod_non_deliverydays = $deliverymethod['non_delivery_days'];


                $deliverymethod_workingdays_max = $deliverymethod['workingdays_max'];

                $call_day_before = $deliverymethod['call_day_before'];
                $date_range_selection = $deliverymethod['date_range_selection'];

                if (!empty($date_range_selection)) {
                    $deliverymethod_thedaterange_interval = $deliverymethod_daterange_interval[$deliverymethod_id];
                    $deliverymethod_workingdays_min = $deliverymethod['workingdays_min_adjusted'];
                    $deliverymethod_workingdays_max = $deliverymethod['workingdays_max'];
                }

                $deliverymethod_workingdays_min = $deliverymethod['workingdays_min_adjusted'];
                $deliverymethod_workingdays_max = $deliverymethod['workingdays_max'];

                // TODO: Check it isn't doubling this up on the adjusted min days (like readding them later!!)


                if (strtotime($day) >= $deliverymethod_delivery_date_first) {
                    // Method is good to show on this day

                    // Get Price

                    $day_of_week_value = date("N",strtotime($day));

                    if (isset($deliverymethod['delivery_fees'][$day_of_week_value])) {
                        $price = $deliverymethod['delivery_fees'][$day_of_week_value]['fee'];
                    } else {
                        $price = null;
                    }

                    $val_price = '';
                    // Empty won't work as it's 0.0000 so check directly
                    if ($price == 0.0000) {
                        $price = "Free";
                        $val_price = 0.00;
                    } else {
                        $price = round($price, 0);
                        $val_price = round($price,0);
                    }
                    $elementid = $day . "_" . $deliverymethod_id . "_" . $deliveryarea_id;
                    // Get Days we don't want to show method for
                    if (!empty($date_range_selection)) {

                        $deliverymethod_thedaterange_interval = ($deliverymethod_daterange_interval[$deliverymethod_id]);
                        //$deliverymethod_thedaterange_interval = $deliverymethod_thedaterange_interval;

                        $date_range_class = "delivery-range delivery-selection-range-$deliverymethod_thedaterange_interval";
                    }

                    $deliverymethod_days_tonotshow = $deliverymethod['non_delivery_days'];


                    if (array_key_exists(intval($thisday), $deliverymethod_days_tonotshow) || (in_array($loop_day, $seperateholidaydates))) {


                        if ($deliverymethodcount == $deliverymethodTotalCount) {

                            $temp_mobile_filler = "<div class=\"delivery-radio-selector\">";
                            $temp_mobile_filler .= "Sorry no delivery options available on this day";
                            $temp_mobile_filler .= "</div>";

                            $mobile_day_options[$day]['options'][] = $temp_mobile_filler;
                            $mobile_day_options[$day]['no_options'] = 1;
                        }

                        $deliverymethodcount++;
                        continue;
                    }

                    $deliverymethod_start_day = $deliverymethod_first_pickable_option_day[$deliverymethod_id];
                    $deliverymethod_daterange_currentday = date('Y-m-d', strtotime($day));


                    $deliverymethod_before_time_pre = $deliverymethod['before_time'];

                    $deliverymethod_before_time = explode(' ',$deliverymethod_before_time_pre);


                    $mobile_before_block = '';
                    $summary_block_text = '';
                    $general_text = '';

                    $summary_block_text .= "$deliverymethod_teamnumber Man $deliverymethod_teamability ";

                    if(!empty($date_range_selection)) {
                        // Doing these here as it's all used further down.
                        $range_working_days_to_add = ($deliverymethod_workingdays_max - $deliverymethod_workingdays_min);
                        $deliverymethod_daterange_currentday_enddate = new DateTime($day);
                        $deliverymethod_daterange_deliverydate_determiner = new DateTime($day);

                        $this->findRangeEndDateForCurrentDate($deliverymethod_daterange_currentday_enddate, $seperateholidaydates, $deliverymethod_non_deliverydays, $deliverymethod_workingdays_min, $deliverymethod_workingdays_max);
                        $thisdate = $this->findRangeDeliveryDays($deliverymethod_daterange_deliverydate_determiner, $seperateholidaydates, $deliverymethod_non_deliverydays, $deliverymethod_workingdays_min, $deliverymethod_workingdays_max);
                        $output_delivery_days = implode(",", $thisdate);
                        $deliverymethod_daterange_output_enddate = $deliverymethod_daterange_currentday_enddate->format('Y-m-d');

                    }

                    // If methodbefore time is set
                    if (strcmp($deliverymethod_before_time[1], "00:00:00") != 0) {

                        $general_text .= "Morning (Before $deliverymethodbeforetime)";


                        $mobile_before_block .= '<span class="method-time-time">'.$general_text.'</span>';
                    } else {
                        if (!empty($date_range_selection)) {
                            $window_count = count($thisdate);

                            $g_tmp_array = array("$window_count Working Day Delivery Window"," - ", "Between ($deliverymethodstarttime - $deliverymethodendtime)");

                            $general_text .= $g_tmp_array[0] . $g_tmp_array[1]  . $g_tmp_array[2];


                            $mobile_before_block .= '<span class="method-time-delivery-window">';
                            $mobile_before_block .= $g_tmp_array[0];
                            $mobile_before_block .= '</span><br>';
                            $mobile_before_block .= '<span class="method-time-time">';
                            $mobile_before_block .= $g_tmp_array[2].'</span>';


                        } else {
                            $general_text .= "All Day (" . $deliverymethodstarttime . "-" . $deliverymethodendtime . ")";
                            $mobile_before_block .= '<span class="method-time-time">'.$general_text.'</span>';
                        }


                    }

                    $summary_block_text .= $general_text;

                    $general_text = '';
                    if (!empty($date_range_selection)) {

                        $simpler_output_delivery_days = array();

                        // $thisdate is the output delivery days before turnedinto a string.

                        foreach ($thisdate as $output_delivery_day) {

                            $newdate = date('M jS', strtotime($output_delivery_day));
                            $simpler_output_delivery_days[] = $newdate;


                        }

                        $new_output_delivery_days = implode(", ", $simpler_output_delivery_days);

                        $general_text .=  'On one of: '.$new_output_delivery_days;
                        $summary_block_text .= ' '.$general_text;
                        $mobile_before_block .= ' '.$general_text;

                        //$mobile_delivery_element .= $output_delivery_days;

                        //$mobile_delivery_element .= '<span class="method-date-range">' . date('M jS', strtotime($deliverymethod_daterange_currentday)) . ' to ' . $deliverymethod_daterange_currentday_enddate->format('M jS') . '</span>';

                    } else {
                        $general_text .= ' on '. date('M jS', strtotime($day));
                        $summary_block_text .= $general_text;
                    }



                    $mobile_delivery_element = "<div class=\"delivery-radio-selector\">";
                    $mobile_delivery_element .= "<div class=\"radio\">";
                    $mobile_delivery_element .= "<input id=\"mobile_delivery$elementid\"";
                    $mobile_delivery_element .= " name=\"deliveryMobile\" method=\"$deliverymethod_id\" type=\"radio\" ";

                    $delivery_element = "methodstartdate=\"$deliverymethod_start_day\"";


                    if (!empty($date_range_selection)) {

                        $delivery_element .= "methodrangestartdate=\"$deliverymethod_daterange_currentday\"";

                        $delivery_element .= "methodenddate=\"$deliverymethod_daterange_output_enddate\"";
                        $delivery_element .= "methodrangedates=\"$output_delivery_days\"";
                    }

                    $delivery_element .= " value=\"$elementid\" data-price=\"$val_price\" ";

                    $mobile_delivery_element .= $delivery_element;

                    $mobile_delivery_element .= " data-methodsummarytext=\"$summary_block_text\" ";

                    $delivery_element_1 = " /><label for=\"delivery" . $elementid. "\" class=\"method-price\">";

                    $mobile_delivery_element .= $delivery_element_1;

                    $mobile_delivery_element .= "<span class='method-pri'>";

                    if (strcmp($price, "Free") != 0) {
                        $mobile_delivery_element .= "£" . $price . "</span> ";
                    } else {
                        $mobile_delivery_element .= "" . $price . "</span> ";
                    }


                    $mobile_delivery_element .= '<span class="method-del-teamnumber">' . $deliverymethod_teamnumber . ' Man </span>';

                    $mobile_delivery_element .= '<span class="method-del-type">' . $deliverymethod_teamability . '</span><br>';


                    $mobile_delivery_element .= $mobile_before_block; // time block;



                    $mobile_delivery_element .= "</label></div></div>";


                    $mobile_day_options[$day]['options'][] = $mobile_delivery_element;

                    $deliverymethodcount++;

                }

            }

            unset($deliveryitemclass);
            unset($holidaycheck);
            unset($date_range_class);
            unset($date_range_selection);
            unset($deliverymethod_daterange_currentday_enddate);

        }
        $free_available = '';
        $free_date = '';


        foreach ($mobile_day_options as $key => $value) {
            if (count($value['options']) === 0) {
                $temp_mobile_filler = "<div class=\"delivery-radio-selector\">";
                $temp_mobile_filler .= "Sorry no delivery options available on this day";
                $temp_mobile_filler .= "</div>";

                $mobile_day_options[$key]['options'][] = $temp_mobile_filler;
                $mobile_day_options[$key]['no_options'] = 1;
            }
        }

        $mobile_day_options_1 = $this->buildMobileCalendar($mobile_day_options);
        $mobile_day_options_2 = $this->buildMobileCalendarDates($mobile_day_options);

        $mobile_delivery_desc_checkout = $this->getDeliveryCheckoutDescriptionOutput($deliverymethod_delivery_desc_checkout);

        $output_delivery_methods = array();

        foreach ($deliverymethods as $deliverymethod) {
            $deliverymethod_id = $deliverymethod['deliverymethod_id'];

            $output_delivery_methods[$deliverymethod_id] = $deliverymethod;
        }

        // For Month to Show First

        $first_month_to_display = date('F', $_deliverymethod_first_delivery_dates[0]);

        $something = array(
            'mobile_day_options' => $mobile_day_options,
            'mobile_day_options_1' => $mobile_day_options_1,
            'mobile_day_options_2' => $mobile_day_options_2,
            'mobile_delivery_element' => $mobile_delivery_element,
            'delivery_descriptions_checkout' => $mobile_delivery_desc_checkout,
            'methods_available' => $output_delivery_methods,
            'first_month_to_display' => $first_month_to_display
        );
        return $something;

    }

    public function getDeliveryCheckoutDescriptionOutput($descriptions) {

        $output = '';

        foreach ($descriptions as $key => $value) {
          $output .= '<div id="delivery-desc-checkout-'. $key .'" class="delivery-desc">';
          $output .= $value;
          $output .= '</div>';
        }

        return $output;
    }

    public function getDeliveryProducts($product_ids) {

        $filters = array(
            array(
                'field' => 'product_id',
                'value' => $product_ids,
                'condition_type' => 'in'
            )
        );

        $sortorder = array(
            'field' => 'product_id',
            'direction' => 'ASC'
        );

        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
        $delivery_methods_for_products = $this->deliveryProducts->getList($searchCriteria);

        return $delivery_methods_for_products;
    }

    public function getCartActiveData() {
        return $this->checkoutSession->getQuote();
    }

    public function getCartItems() {
        return $this->checkoutSession->getQuote()->getItems();
    }


    public function isDayDeliverable($day, $deliverymethod) {
        // TODO Fix this!
        return true;
    }

    public function formatTimeElevate($time) {
        return date('ga', strtotime($time));
    }

    public function buildMobileCalendar($mobile_day_options) {

        $mobile_calendar_output = '';

        $count = 0;

        foreach ($mobile_day_options as $key => $value) {

            $date_number = date('j', strtotime($key));
            $date_month = date('F', strtotime($key));
            $date_day = date('D', strtotime($key));

            if ($count === 0) {
                $mobile_calendar_output .= '<div id="mobile-dates-container" class="owl-carousel">';
            }

            $class = '';
            if (isset($value['no_options'])) {
                if ($value['no_options'] === 1) {
                    $class = 'no-del-options';
                }
            }


            $mobile_calendar_output .= '<div class="mobile-date '.$class.'" data-date="' . $key . '" data-number="' . $date_number . '" data-month="' . $date_month . '">';

            $mobile_calendar_output .= '<div class="date-day">' . $date_day . '</div>';

            $mobile_calendar_output .= '<div class="date-number">' . $date_number . '</div>';

            $mobile_calendar_output .= '</div>';

            $count++;
        }
        $mobile_calendar_output .= '</div>';

        return $mobile_calendar_output;
    }



    //Change day and method of a delivery


    public function buildMobileCalendarDates($mobile_day_options) {

        $mobile_calendar_output = '';

        foreach ($mobile_day_options as $key => $value) {

            $date_number = date('jS', strtotime($key));
            $date_month = date('F', strtotime($key));
            $date_day = date('D', strtotime($key));
            $mobile_calendar_output .= '<ul class="day-options-' . $key . '">';
            foreach ($mobile_day_options[$key]['options'] as $option) {
                $mobile_calendar_output .= '<li class="date-">';
                $mobile_calendar_output .= $option;
                $mobile_calendar_output .= "</li>";
            }
            $mobile_calendar_output .= "</ul>";
        }

        return $mobile_calendar_output;
    }

    public function isProcessStartPastCutOff($date) {

        $process_start_date = $date->format('Y-m-d H:i:s');
        $process_start_time = $date->format('H');

        if ($process_start_time >= $this->cutofftime_fornextworkingday_2digit) {
            // After Cutoff Time so First Available Dispatch Day will be the next working day
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param      $date
     * @param      $seperateholidaydates
     * @param      $method_non_deliverydays
     * @param null $days_to_add
     *
     * @return arrayf
     */
    public function findFirstDateAvailable($date, $seperateholidaydates, $deliverymethod_non_deliverydays, $days_to_add = NULL) : array{
        // Figure out First Dispatch Day
        // Figure out First Available Delivery Day

        //Monday - Friday
        $workingdays = $this->working_days;
        $dispatch_day = new DateTime($date->format('Y-m-d H:i:s'));

        $process_start_check = $this->isProcessStartPastCutOff($date);

        if ($process_start_check === true) {
            // Order Couldn't be dispatched on this day, so move to next working day.
            $days_to_add = $days_to_add + 1;
        } else {
            // Fixes issue with befor 10am not factoring in if the start date is working day

            // is this a working day or not?
            $this->isCurrentDateWorkingDay($dispatch_day);
        }

        // Check if we need to add any working days to the dates

        if (!empty($days_to_add)) {

            $this->addWorkingDays($dispatch_day, $days_to_add, $workingdays, $seperateholidaydates);

        }

        $delivery_date = new DateTime($dispatch_day->format('Y-m-d'));

        // Delivery Date can't be the same as the dispatch date, so add an extra day
        $delivery_date->modify("+1 days");

        // Check that dates suitability
        $this->checkDateSuitability($delivery_date, $deliverymethod_non_deliverydays, $seperateholidaydates);

        // We should now have a suitable first delivery date for this method...

        $dates = array(
            $dispatch_day,
            $delivery_date
        );

        return $dates;
    }

    public function findRangeEndDateForCurrentDate($date, $seperateholidaydates, $method_non_deliverydays, $range_min, $range_max) {

        //Determine End Date of Range For Current Date
        //Monday - Friday
        $workingdays = $this->working_days;
        $range_between = $range_max - $range_min;

        $this->addWorkingDays($date, $range_between, $workingdays, $seperateholidaydates);

    }

    /**
     * @param object $date
     * @param array  $seperateholidaydates
     * @param array  $deliverymethod_non_deliverydays
     * @param int    $range_min
     * @param int    $range_max
     * @param string $date_output_format
     *
     * @return array
     */
    public function findRangeDeliveryDays($date, $seperateholidaydates, $deliverymethod_non_deliverydays, $range_min, $range_max, $date_output_format = NULL) {

        //Determine End Date of Range For Current Date
        //Monday - Friday
        $workingdays = $this->working_days;
        $range_between = $range_max - $range_min;
        $delivery_days = [];

        if ($date_output_format === NULL) {
            $date_output_format = "l jS F Y";
        }

        $delivery_days[] = $date->format($date_output_format);

        $i = 1;
        while($i <= $range_between) {
            $date->modify("+1 day");
            // Is this a working day and is this a holiday
            if ((in_array($date->format('N'), $workingdays)) && (!in_array($date->format('Y-m-d'), $seperateholidaydates))) {
                //is a working day and isn't a holiday,
                $i++;
                //
                $delivery_days[] = $date->format($date_output_format);
            }
        }

        return $delivery_days;
    }

    /**
     * @param object $startdate
     * @param array  $seperateholidaydates
     * @param array  $method_non_deliverydays
     * @param int    $range_min
     * @param int    $range_max
     * @param string $date_output_format
     *
     * @return array
     */
    public function findRangeDeliveryDaysBetween($startdate, $enddate, $seperateholidaydates, $method_non_deliverydays, $range_min, $range_max, $date_output_format = NULL) {

        //Determine End Date of Range For Current Date
        //Monday - Friday
        $workingdays = $this->working_days;
        $delivery_days = [];

        if ($date_output_format === NULL) {
            $date_output_format = "l jS F Y";
        }

        $delivery_days[] = $startdate->format($date_output_format);

        $diff = intval($enddate->diff($startdate)->format("%a"));

        $enddate_strtime = strtotime($enddate->format('Y-m-d'));

        $i = 1;
        while($i <= $diff) {
            $startdate->modify("+1 day");
            // Is this a working day and is this a holiday
            if ((in_array($startdate->format('N'), $workingdays)) && (!in_array($startdate->format('Y-m-d'), $seperateholidaydates)) && (strtotime($startdate->format('Y-m-d')) != $enddate_strtime)) {
                //is a working day and isn't a holiday,

                $delivery_days[] = $startdate->format($date_output_format);
            }
            $i++;
        }
        $delivery_days[] = $enddate->format($date_output_format);

        return $delivery_days;
    }

    public function checkDateSuitability($date, $deliverymethod_non_deliverydays, $seperateholidaydates) {
        $date_suitable = 0;

        // Check to See if we date is suitable if we don't then lets find a day

        while($date_suitable != 1) {
            if (!in_array($date->format('N'), $deliverymethod_non_deliverydays)) {
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
     * @param $thisdate
     * @param $dispatch_days
     *
     * @return int
     *
     * Get the number of working days in a value of days (from the current date)
     * Does not factor in holiday calculations
     *
     */
    public function calculateWorkingDays($internal_base_date, $dispatch_days) {

        // dispatch days is working and non working days
        $workingdays = $this->working_days;
        // Working Days in the 1,2,3,4,5 format
        $i = 0;
        $workingdays_to_add = 0;

        while($i < $dispatch_days) {
            if (in_array($internal_base_date->format('N'), $workingdays)) {
                $workingdays_to_add++;
            }
            $i++;
            $internal_base_date->modify("+1 day");
        }

        return $workingdays_to_add;

    }

    public function isCurrentDateWorkingDay($workingday_date) {

        $workingdays = $this->working_days;

        $isworkingday = 0;

        while($isworkingday != 1) {
            if (!in_array($workingday_date->format('N'), $workingdays)) {
                // This day isn't a working day, so even if it's before 10am it can't be dispatched here, find the next working day!
                $workingday_date->modify("+1 day");
            } else {
                // Working Day == Yes
                $isworkingday = 1;
            }
        }
    }

    /**
     * @param object DateTime $method_range_end_date
     * @param object DateTime $method_first_working_date
     * @param int $method_working_days_max
     * @param array $method_non_delivery_days
     * @param array $seperateholidaydates
     */
    public function dateRangeCheck($method_range_end_date, $method_first_delivery_date, $method_non_delivery_days, $method_working_day_range, $seperateholidaydates) {
        // Find The Day our Range will end on
        $workingdays = $this->working_days;
        // Add one to Range End Date Because we want it to add the working days difference of the range
        $method_range_end_date->modify('+1 days');
        $this->addWorkingDays($method_range_end_date, $method_working_day_range, $workingdays, $seperateholidaydates);

        $datecheck = $method_first_delivery_date->diff($method_range_end_date);

        return $datecheck;
    }

    public function showmethodCheck($delivery_options_to_show, $area_sqlid, $standarddeliveryoptionsforproduct, $nonstandarddeliveryoptionsforproduct, $islands_deliveryoptionsforproduct) {
        if ($area_sqlid == self::AREA_HIGHLANDS) {
            if (empty(array_intersect($delivery_options_to_show, $nonstandarddeliveryoptionsforproduct))) {
                // Highlands Options
                // if Multi-select ID Values is not in the array, then skip output and check next.

                return false;

            }
        } elseif ($area_sqlid == self::AREA_IRELAND) {
            if (empty(array_intersect($delivery_options_to_show, $islands_deliveryoptionsforproduct))) {
                // Islands Options

                // if Multi-select ID Values is not in the array, then skip output and check next.

                return false;

            }
        } else {
            // Standard Options
            if (empty(array_intersect($delivery_options_to_show, $standarddeliveryoptionsforproduct))) {
                // if Multi-select ID Values is not show, then skip output and check next.
                return false;
            }
        }

        return true;
    }

    public function addWorkingDays($internaldate, $days_to_add, $workingdays, $holidays) {
        $i = 1;
        while($i <= $days_to_add) {
            $internaldate->modify("+1 day");
            // Is this a working day and is this a holiday
            if ((in_array($internaldate->format('N'), $workingdays)) && (!in_array($internaldate->format('Y-m-d'), $holidays))) {
                //is a working day and isn't a holiday,
                $i++;
                //
            }
        }
    }

    public function findDeliveryMethodNonDeliveryDays(array $method_days) : array {
        for ($i = 1; $i <= 7; $i++) {
            $day = $method_days[$i];

            if ($day == 0) {
                // i.e. don't show method for this date
                $days[] = $i;
            }
        }
        if (empty($days)) {
            $days = array('0');
        }

        return $days;
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
    public function createDateRange($startDate, $endDate, $format = "Y-m-d") {
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

    //Return true if day is in holiday
    //-----------------------------------------------------------------------------------------------------------------
    /**
     * @param $date
     * @param $holidays
     *
     * @return bool
     */
    public function isDayHoliday($date, $holidays) {
        foreach ($holidays AS $holiday) {
            if (strlen(trim($holiday)) > 0) {
                $holidaysBounds = explode(":", $holiday);
                if (sizeof($holidaysBounds) == 2) {

                    if (strtotime($date) >= strtotime($holidaysBounds[0]) AND strtotime($date) <= strtotime($holidaysBounds[1])) {
                        return true;

                    }
                }
            }
        }

        return false;
    }

    // Function for excluding certain dates from certain options
    //-----------------------------------------------------------------------------------------------------------------
    /**
     * @param $day
     * @param $method
     *
     * @return
     */
    public function ismethodDayAvailable($day, $method) {

        $methods_to_apply_exclusions = $this->methods_to_apply_exclusions;
        $days_to_exclude = $this->days_to_exclude;
        $method_id = $method->sqlId;
        if (in_array($method_id, $methods_to_apply_exclusions)) {
            // Time slot is one we are excluding

            // Check if day is one of the days in question

            if (in_array($day, $days_to_exclude)) {
                // Yes Exclude this day

                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    /*==============================================================================
Application:   Utility Function
Author:        John Gardner

Version:       V1.0
Date:          25th December 2004
Description:   Used to check the validity of a UK postcode

Version:       V2.0
Date:          8th March 2005
Description:   BFPO postcodes implemented.
               The rules concerning which alphabetic characters are alllowed in
               which part of the postcode were more stringently implementd.

Version:       V3.0
Date:          8th August 2005
Description:   Support for Overseas Territories added

Version:       V3.1
Date:          23rd March 2008
Description:   Problem corrected whereby valid postcode not returned, and
							 'BD23 DX' was invalidly treated as 'BD2 3DX' (thanks Peter
               Graves)

Version:       V4.0
Date:          7th October 2009
Description:   Character 3 extended to allow 'pmnrvxy' (thanks to Jaco de Groot)

Version:       V4.1
Date:          8th Septemeber 2011
Description:   ereg and ereg_replace replaced with preg_match and preg_replace
               BFPO support improved
               Add check for Anquilla

Version:       V5.0
Date:          8th November 2012
               Specific support added for new BFPO postcodes

Parameters:    $postcode - postcode to be checked. This is returned reformatted
               if valid.

This function checks the value of the parameter for a valid postcode format. The
space between the inward part and the outward part is optional, although is
inserted if not there as it is part of the official postcode.

The functions returns a value of false if the postcode is in an invalid format,
and a value of true if it is in a valid format. If the postcode is valid, the
parameter is loaded up with the postcode in capitals, and a space between the
outward and the inward code to conform to the correct format.

Example call:

    if (!checkPostcode ($postcode) ) {
      echo 'Invalid postcode <br>';
    }

------------------------------------------------------------------------------*/
    function checkPostcodeMain(&$toCheck) {

        // Permitted letters depend upon their position in the postcode.
        $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
        $alpha3 = "[abcdefghjkpmnrstuvwxy]";                            // Character 3
        $alpha4 = "[abehmnprvwxy]";                                     // Character 4
        $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
        $BFPOa5 = "[abdefghjlnpqrst]{1}";                               // BFPO character 5
        $BFPOa6 = "[abdefghjlnpqrstuwzyz]{1}";                          // BFPO character 6

        // Expression for BF1 type postcodes
        $pcexp[0] = '/^(bf1)([[:space:]]{0,})([0-9]{1}' . $BFPOa5 . $BFPOa6 . ')$/';

        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[1] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Expression for postcodes: ANA NAA
        $pcexp[2] = '/^(' . $alpha1 . '{1}[0-9]{1}' . $alpha3 . '{1})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Expression for postcodes: AANA NAA
        $pcexp[3] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{1}[0-9]{1}' . $alpha4 . ')([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Exception for the special postcode GIR 0AA
        $pcexp[4] = '/^(gir)([[:space:]]{0,})(0aa)$/';

        // Standard BFPO numbers
        $pcexp[5] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';

        // c/o BFPO numbers
        $pcexp[6] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';

        // Overseas Territories
        $pcexp[7] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';

        // Anquilla
        $pcexp[8] = '/^ai-2640$/';

        // Load up the string to check, converting into lowercase
        $postcode = strtolower($toCheck);

        // Assume we are not going to find a valid postcode
        $valid = false;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp) {

            if (preg_match($regexp, $postcode, $matches)) {

                // Load new postcode back into the form element
                $postcode = strtoupper($matches[1] . ' ' . $matches [3]);

                // Take account of the special BFPO c/o format
                $postcode = preg_replace('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

                // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
                if (preg_match($pcexp[7], strtolower($toCheck), $matches))
                    $postcode = 'AI-2640';

                // Remember that we have found that the code is valid and break from loop
                $valid = true;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was
        // valid
        if ($valid) {
            $toCheck = $postcode;

            return true;
        } else return false;
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
