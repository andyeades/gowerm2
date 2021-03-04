<?php

namespace Elevate\Delivery\Block\Adminhtml\Test;

use Magento\Framework\Message\ManagerInterface;


class Test extends \Magento\Backend\Block\Widget\Container
{

    protected $deliveryArea;

    protected $deliveryMethod;

    protected $deliveryProducts;

    protected $deliveryFee;

    protected $deliveryRulesProducts;

    protected $deliveryRules;

    protected $deliveryRulesType;

    protected $deliveryRulesFunctions;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    protected $_backendUrl;

    protected $sortOrderBuilder;
    protected $filter;
    protected $filterBuilder;
    protected $filterGroup;
    protected $filterGroupBuilder;

    protected $logger;

    protected $productrepository;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context                      $context
     * @param \Magento\Backend\Model\UrlInterface                        $backendUrl
     * @param ManagerInterface                                           $messageManager
     * @param \Magento\Framework\Api\SearchCriteriaBuilder               $searchCriteriaBuilder

     * @param array                                                      $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        ManagerInterface $messageManager,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Elevate\Delivery\Api\DeliveryFeeRepositoryInterface $deliveryFee,
        \Elevate\Delivery\Api\DeliveryMethodRepositoryInterface $deliveryMethod,
        \Elevate\Delivery\Api\DeliveryProductsRepositoryInterface $deliveryProducts,
        \Elevate\Delivery\Api\DeliveryAreaRepositoryInterface $deliveryArea,
        \Elevate\Delivery\Api\DeliveryRulesRepositoryInterface $deliveryRules,
        \Elevate\Delivery\Api\DeliveryRulesProductsRepositoryInterface $deliveryRulesProducts,
        \Elevate\Delivery\Api\DeliveryRulesTypeRepositoryInterface $deliveryRulesType,
        \Elevate\Delivery\Api\DeliveryRulesFunctionsRepositoryInterface $deliveryRulesFunctions,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,

        array $data = [])
    {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
        $this->_backendUrl = $backendUrl;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->deliveryArea = $deliveryArea;
        $this->deliveryFee = $deliveryFee;
        $this->deliveryMethod = $deliveryMethod;
        $this->deliveryProducts = $deliveryProducts;
        $this->deliveryRules = $deliveryRules;
        $this->deliveryRulesProducts = $deliveryRulesProducts;
        $this->deliveryRulesType = $deliveryRulesType;
        $this->deliveryRulesFunctions = $deliveryRulesFunctions;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filter = $filter;
        $this->filterGroup = $filterGroup;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->logger = $logger;
        $this->productrepository = $productrepository;
    }


    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute() {

        $params = $this->getRequest()->getParams();

        return $this->resultJsonFactory->create()->setData(['a' => 'b']);




    }


    public function getPostUrl()
    {
        $params = $this->getRequest()->getParams();

        $params = array('name' => '');
        //$params = array('some'=>$params);

        $url = $this->_backendUrl->getUrl("*/*/shiporder");

        return $url;
    }

    public function doSomething() {


        // Column C = Area 1
        // Column D = Area 2
        // Column E = Area 3

        // Load Area Model and get all, count number of areas (for max colums).





        $testrow = array(
            'product_sku' => 'JB-OAK-ASMTR-HF-FR-6F.1',
            '1' => 'G,H',
            '2' => 'D',
            '3' => ''
            );
        // Test Sku from "Row"
        $product_sku = $testrow['product_sku'];

        $output = '';

        // Test "Columns"
        //$columns = array("G,H","D","");
        $destinationfilePath = '/Users/richardjones/www/gowercottage/vendor/elevate/module-delivery/Block/Adminhtml/Test/samplehbfile-deliveryimport.csv';
        //$destinationfilePath = '/microcloud/domains/happyb/domains/happybeds.co.uk/www/vendor/elevate/module-delivery/Block/Adminhtml/Test/samplehbfile-deliveryimport.csv';

        $fdata = fopen($destinationfilePath, "r");

        $firstline = true;
        $count = 0;

        if ($fdata) {
            while (($column = fgetcsv($fdata)) !== FALSE) {
                $count++;
                if ($count == 1) { continue;}
                $product_sku = $column[0];
                //$product_id = $column[0];

                if($column[1] == 'sku')// unique Name like Primary key
                {
                    continue;
                }
                // TODO :: NEEDS TO BE BETTER!
                $columns = array($column[1], $column[2], $column[3]);

                $delivery_area_count = 1;

                foreach ($columns as $delivery_area_methods) {


                    echo "Delivery Method: ". $delivery_area_methods."<br>";

                    // check if empty, then

                    if (!empty($delivery_area_methods)) {
                        //echo "Not Empty! <br>";
                        // Something in Column - assume it's a comma seperated value list - lets try splitting it



                        // This is the column of data
                        $delivery_methods_internalnamed = explode(',',$delivery_area_methods);


                        // Get All Methods That Exist Which Apply To THis Area


                        $filters = array(
                            array(
                                'field'          => 'deliveryarea_id',
                                'value'          => $delivery_area_count,
                            )
                        );

                        $sortorder = array(
                            'field'     => 'deliverymethod_id',
                            'direction' => 'DESC'
                        );

                        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                        $available_delivery_methods_for_area = $this->deliveryMethod->getList($searchCriteria);
                        $available_delivery_methods_for_area_count = $available_delivery_methods_for_area->getTotalCount();



                        $available_delivery_methods_for_area = $available_delivery_methods_for_area->getItems();
                        $avail_del_meth_for_area_array = [];
                        $del_method_ids_for_area_arr = [];
                        foreach($available_delivery_methods_for_area as $area) {
                            // Create an Array we can search easily
                            $avail_del_meth_for_area_array[] = $area->getAllData();
                            $del_method_ids_for_area_arr[] = $area->getDeliverymethodId();
                        }


                        // Get Current Delivery/Product Associations for applicable Method Codes

                        $deliverymethod_ids = implode(',',$del_method_ids_for_area_arr);

                        echo "$deliverymethod_ids <br>";
                        echo "$product_sku <br>";

                        $current_deliveryoptions_for_product_thisarea = $this->getCurrentProductDeliveryOptionsByMethods($deliverymethod_ids, $product_sku);



                        $cdfpt_count = $current_deliveryoptions_for_product_thisarea->getTotalCount();

                        echo "Current Options Count ". $cdfpt_count. " <br>";

                        $cdfpt_items = $current_deliveryoptions_for_product_thisarea->getItems();


                        //$current_deliveryoptions_for_product_thisarea->getItems();

                        $cdfpt_items_array = array();

                        foreach ($cdfpt_items as $item) {

                            $deliverymethod_id = $item->getDeliverymethodId();

                            $cdfpt_items_array[$deliverymethod_id] = $item->getAllData();
                        }

                        //$cdfpt_items_array are the current product/delivery associations



                        // What if Count is 0? Don't add?

                        if (empty($available_delivery_methods_for_area_count)) {
                            $this->logger->info('No Methods Allowed For Area - Fix!');
                            $this->logger->info('Delivery Area = '.$delivery_area_count);
                            // Break Out of Loop?
                            continue;
                        }




                        $delProds_actual = array();

                        foreach ($delivery_methods_internalnamed as $internal_name) {
                            // Add Each of these to be checked/updated? - like what via an API call?
                            if (strcmp($internal_name,'D') == 0) {
                                $bob = 'job';
                            }
                            //echo "Array Search - ".$internal_name."<br>";

                            $searched_data = $this->searchForInternalName($internal_name, $avail_del_meth_for_area_array);
                            //$searched_data = array_search($internal_name, array_column($avail_del_meth_for_area_array, 'internal_name'));

                            //echo "<pre>";
                            //print_r($searched_data);
                            //echo "</pre>";



                            $methods_allowed_for_product_and_area = [];

                            $methods_to_delete_for_product_and_area = [];

                            // Not Found
                            if (empty($searched_data) && $searched_data != 0) {
                                echo "Method ".$internal_name." is not currently available for Area ".$delivery_area_count." You will need to create the method first <br>";
                                // Method currently doesn't exist for Area - Note to add it
                                $this->logger->info("Method ".$internal_name." is not currently available for Area ".$delivery_area_count." You will need to create the method first");
                                continue;
                            } else {
                                // Ok Method is acceptable for area Lets see if it already exists
                                $delivery_method_id = $avail_del_meth_for_area_array[$searched_data]['deliverymethod_id'];


                                if ($this->checkProductDeliveryAssociation($delivery_method_id, $product_sku)) {    // Already exists
                                    echo "Method ".$internal_name." already exists for product: ".$product_sku."<br>";
                                    // Method currently doesn't exist for Area - Note to add it
                                    $this->logger->info("Method ".$internal_name." already exists for ".$product_sku);

                                } else {
                                    // Doesn't Exist - Create it!
                                    echo "Method ".$internal_name." Doesn't Exist - Let's Create for product: ".$product_sku."<br>";
                                    // Method currently doesn't exist for Area - Note to add it
                                    $this->logger->info("Method ".$internal_name." Doesn't Exist - Let's Create for product: ".$product_sku);

                                    try {
                                        $product = $this->productrepository->get($product_sku);

                                    } catch (\Exception $e) {
                                        $this->logger->info("Error with :".$product_sku);
                                        echo 'error.with: '.$product_sku;
                                        continue;
                                    }


                                    $product_id = $product->getId();

                                    //echo "product_id $product_id <br>";

                                    $newDelProduct = $this->deliveryProducts->create();

                                    $newDelProduct->setProductSku($product_sku);
                                    $newDelProduct->setDeliverymethodId($delivery_method_id);
                                    $newDelProduct->setProductId($product_id);

                                    // Save New Association
                                    $newDelProduct->save();
                                }

                                $delProds_actual[$delivery_method_id] = 'exists';

                                // Remove Other Ones



                            }

                        }

                        if (isset($delProds_actual)) {
                            //echo "<pre>";
                            //print_r($delProds_actual);
                            //echo "</pre>";

                            //echo "other one";


                            $delProds_toKeep = array_intersect_key($cdfpt_items_array, $delProds_actual);


                            foreach ($cdfpt_items_array as $key => $value) {

                                //echo 'foreach cdfpt_items <br>';
                                if (array_key_exists($key, $delProds_toKeep)) {
                                    continue;
                                    //echo 'Keep this one! <br>';
                                } else {
                                   // echo "Deleting <br>";
                                    // Delete this one as it's not listed on the uploaded sheet!
                                    $this->logger->info('Deleting - Delivery Product Association');
                                    $this->logger->info(print_r($value, true));

                                    $id_to_delete = $value['deliveryproducts_id'];
                                    echo $id_to_delete."<br>";
                                    $this->deliveryProducts->deleteById($id_to_delete);

                                }

                            }
                        }

                        // Ok So Lets check now

                    } else {
                        // Check if are any records associated with a Delivery Method for this Area and remove

                        $filters = array(
                            array(
                                'field' => 'product_sku',
                                'value' => $product_sku
                            )
                        );

                        $sortorder = array(
                            'field'     => 'deliverymethod_id',
                            'direction' => 'DESC'
                        );

                        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                        $delivery_products = $this->deliveryProducts->getList($searchCriteria);
                        $count = $delivery_products->getTotalCount();

                        if (empty($count)) {
                            // None, Next!
                            continue;
                        }
                        $delivery_products = $delivery_products->getItems();

                        //$output .= "Total Options For: ".$product_sku." in Delivery Method: ". $delivery_area_count. " is ".$count."<br>";
                        echo $output;

                        // Now check if any of those Method_ids are in the area this column represents
                        $delivery_method_id_list = array();



                        foreach ($delivery_products as $del_produ) {
                            $delivery_method_id_list[] = $del_produ->getDeliverymethodId();
                        }

                        $deliverymethod_ids = implode(',', $delivery_method_id_list);

                        $filters = array(
                            array(
                                'field'          => 'deliverymethod_id',
                                'value'          => $deliverymethod_ids,
                                'condition_type' => 'in'
                            ),
                            array(
                                'field'          => 'deliveryarea_id',
                                'value'          => $delivery_area_count
                            ),
                        );

                        $sortorder = array(
                            'field'     => 'deliverymethod_id',
                            'direction' => 'DESC'
                        );

                        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
                        $deliverymethods = $this->deliveryMethod->getList($searchCriteria);
                        $count1 = $deliverymethods->getTotalCount();
                        $deliverymethods = $deliverymethods->getItems();




                        $output .= "Del Methods Count for ".$product_sku." in Delivery Method: ".$count1."<br>";
                        echo $output;

                        // Any Items Found Are ones that we need to REMOVE as if the column was blank they don't want to send it via that anymore.

                        foreach ($deliverymethods as $to_be_deleted) {
                            $delete_this_id = $to_be_deleted->getDeliverymethodId();

                            foreach ($delivery_products as $del_produ) {
                                $delivery_method_id = $del_produ->getDeliverymethodId();
                                $delivery_products_id = $del_produ->getDeliveryproductsId();

                                if ($delivery_method_id == $delete_this_id) {
                                    // Delete this Product/method Association
                                    //$this->deliveryProducts->deleteById($delivery_products_id);
                                    $this->logger->info('Deleting - Delivery Product Association');
                                    $this->logger->info(print_r($del_produ->getAllData(), true));
                                    echo 'Deleting Product Association!';
                                    echo '<pre>';
                                    print_r($del_produ->getAllData());
                                    echo '</pre>';
                                }
                            }
                        }



                    }


                    $delivery_area_count++;
                }
            }
        }


        die();


        return $output;
    }

    public function searchForInternalName($internal_name, $array) {
        foreach ($array as $key => $val) {
            if (strcmp($val['internal_name'],$internal_name) == 0) {
                return $key;
            }
        }
        return null;
    }

    public function checkProductDeliveryAssociation($deliverymethod_id, $product_sku) {

        $filters = array(
            array(
                'field'          => 'deliverymethod_id',
                'value'          => $deliverymethod_id
            ),
            array(
                'field'          => 'product_sku',
                'value'          => $product_sku
            ),
        );

        $sortorder = array(
            'field'     => 'deliverymethod_id',
            'direction' => 'DESC'
        );

        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
        $delivery_products = $this->deliveryProducts->getList($searchCriteria);
        $delivery_products_count = $delivery_products->getTotalCount();

        if ($delivery_products_count == 1) {
            return true;
        } else if ($delivery_products_count > 1) {
            // Why is it greater than 1?
            $this->logger("Product shouldn't have more than one association for method");
            echo 'Greater than one!<br>';
            return true;
        } else {
            return false;
        }
    }

    public function getCurrentProductDeliveryOptionsByMethods($deliverymethod_ids, $product_sku) {

        $filters = array(
            array(
                'field'          => 'deliverymethod_id',
                'value'          => $deliverymethod_ids,
                'condition_type'     => 'in'
            ),
            array(
                'field'          => 'product_sku',
                'value'          => $product_sku
            ),
        );

        $sortorder = array(
            'field'     => 'deliverymethod_id',
            'direction' => 'DESC'
        );

        $searchCriteria = $this->buildSearchCriteria($filters, $sortorder);
        $delivery_products = $this->deliveryProducts->getList($searchCriteria);

        return $delivery_products;
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
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_Delivery::delivery');
    }
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}

