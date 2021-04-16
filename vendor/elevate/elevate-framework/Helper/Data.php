<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Elevate\Framework\Helper;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_coreSession;
    protected $_assetRepo;
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    protected $sortOrderBuilder;
    /**
     * @var \Magento\Framework\Api\Filter
     */
    protected $filter;
    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;
    /**
     * @var \Magento\Framework\Api\Search\FilterGroup
     */
    protected $filterGroup;
    /**
     * @var \Magento\Framework\Api\Search\FilterGroupBuilder
     */
    protected $filterGroupBuilder;

    public function __construct(

        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        DateTime $date,
        TimezoneInterface $timezone
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filter = $filter;
        $this->filterGroup = $filterGroup;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->_coreSession = $coreSession;
        $this->_assetRepo = $assetRepo;
        $this->date = $date;
        $this->timezone = $timezone;

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
    public function buildSearchCriteria(
        array $filters,
        array $sortorder
    ) {

        $filtergroups = $this->buildFilters($filters, $sortorder);

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
    public function buildFilters(
        array $filters,
        array $sortorder
    ): array {

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
     * @param $_product
     */
    public function getPricing($_product) {
        $output_array = array();


        $percent = 0;
        $percent2 = 0;
        $difference2 = 0;
        $difference1 = 0;
        $product_price = '0.00';
        $product_special_price = '0.00';

                $product_price = (float)number_format($_product->getPriceInfo()->getPrice('regular_price')->getValue(), '2', '.', ',');
        //get $_product special price
                $product_special_price = (float)number_format($_product->getSpecialPrice(), '2', '.', ',');

        if ($_product->getMsrp() > 0) {
                    $orignumber = (float)number_format($_product->getMsrp(), '2', '.', '');

            // $difference = ($orignumber - $product_price);

            $difference = ($orignumber - $product_special_price);
            $percent = $difference / $orignumber;

            $orignumber2 = $product_price;

            if ($product_price != 0.00) {
                $difference2 = ($product_price - $product_special_price);
                $percent2 = $difference2 / $product_price;
            } else {
                $percent2 = -1;
            }

        } else {

            $difference = $product_price - $product_special_price;

            if ($difference > 0) {
                $percent = $difference / $product_price;
            } else {
                $percent = 0;
            }

        }

        $percent_friendly = number_format($percent * 100, 0) . '%';
        $percent_friendly2 = number_format($percent2 * 100, 0) . '%';

        $now = date("Y-m-d");

        return array(
            'product_price' => $product_price,
            'product_special_price' => $product_special_price,
            'percent' => $percent,
            'percent2' => $percent2,
            'difference' => $difference,
            'difference2' => $difference2
        );
    }

    function getLeadTimeInfo($_product) {

            $message = '';
            $extra_class = '';
      $sku_whitelist = [];      
        $output = '';

        if ($_product->getTypeId() == 'configurable') {

            $output = '<div id="stock_message" class="stock_message" style="display:none;">&nbsp;</div>';

        }

        if ($_product->getTypeId() == 'simple') {


            if($_product->isSaleable()){
                try {                       
                    $date_next_available = $_product->getDateNextAvailable();
                    if ($date_next_available == '' && array_key_exists($_product->getSku(), $sku_whitelist)) {
                    
                    
                    $extra_class = 'xmas_message2';  
                        $message = 'In Stock - Arrives by Christmas';
                         $message = '<div class="xmas_message '.$extra_class.'">'.$message.'</div>';
                        $promo_inject = 1;
                        
                        
                        
                    }    
                    else if ($date_next_available == '') {
                    
                    
                    $extra_class = '';  
                        $message = 'In Stock - Quick Delivery Available';
                         $message = '<div class="xmas_message '.$extra_class.'">'.$message.'</div>';
                        $promo_inject = 1;
                        
                        
                        
                        
                    } else {
                        $date = str_replace('/', '-', $date_next_available);
                        $date_next_available = date('Y-m-d', strtotime($date));
                        $OldDate = new \DateTime($date_next_available);
                        $now = new \DateTime(Date('Y-m-d'));
                        $date_arr = $OldDate->diff($now);
                        $days_diff = $date_arr->days;

                        if ($days_diff < 7) {
                            $message = 'Usually dispatched within 7 Days';
                        }
                        if ($days_diff >= 7 && $days_diff < 14) {
                            $message = 'Usually dispatched within 1 - 2 weeks';
                        }
                        if ($days_diff >= 14 && $days_diff < 21) {
                            $message = 'Usually dispatched within  2 - 3 weeks';
                        }
                        if ($days_diff >= 21 && $days_diff < 35) {
                            $message = 'Usually dispatched within 3 - 5 weeks';
                        }
                        if ($days_diff >= 35 && $days_diff < 56) {
                            $message = 'Usually dispatched within 5 - 8 weeks';
                        }
                        if ($days_diff >= 56 && $days_diff < 84) {
                            $message = 'Usually dispatched within 2 - 3 months';
                        }
                        if ($days_diff >= 84 && $days_diff < 112) {
                            $message = 'Usually dispatched within 3 - 4 months';
                        }
                        if ($days_diff >= 112) {
                            $message = 'Usually dispatched within 4 - 6 months';
                        }
                                                         
                            $message = '<div class="xmas_message '.$extra_class.'">'.$message.'</div>';
                        $message .= '<div style="background-color: rgb(230, 242, 255);padding: 18px;"><i class="fa fa-info-circle" style="margin-right: 5px;"></i>This is when this item will leave our warehouse. More accurate delivery dates will be available within checkout, your delivery postcode and any other products you wish to purchase will be taken into account.</div>';

                    }                               
                    
                    
                }         
                catch(Exception $e){
                    //$message = $e->getMessage();
                }

            }
            else{
                $message = 'This size is out of stock, please choose another size.';
            }
             $output = $message;
        }

        return $output;

        return false;
        $handling_time = $product->getHandlingTime();

        $handling_text = '';
        if ($handling_time < 15) {

            $handling_text = "1 - 2 Weeks";
        } else {

            $handling_text = floor($handling_time / 5) . " Weeks";
        }

        if ($handling_time) {
            // ToDO - Make Admin Message

            return '<div id="stock_handlingtime" class="stock_message"><strong class="stock_handlingtimehdr">COVID-19 Notice</strong> - There is currently a lead time of <strong>' . $handling_text . '</strong>
            on this item due to delays in our supply chain. As a small business,
            we hope you can support us by waiting a little longer for your order and apologise for the inconvenience</div>

          ';
        }

        return false;
        // for get current time according to time zone
        //$time = $this->timezone->scopeTimeStamp();

        // you can use \Zend_Date to work with date like this :
        // $date = new \Zend_Date($time, \Zend_Date::TIMESTAMP);
        // $date->subDay(1); // -1 day
        // $date->addDay(1); // +1 day

        ;
        // you can use time with DateTime
        //    $date = (new \DateTime())->setTimestamp($time);
        //    $date->sub(new \DateInterval('P1D')); // -1 day
        //    $date->add(new \DateInterval('P1D')); // +1 day

    }

    function getPercentageOverlay($product) {
        $save_off_output = "";

        $product_price = $product->getPriceInfo()->getPrice('regular_price')->getValue();
        $product_special_price = $product->getPriceInfo()->getPrice('special_price')->getValue();

        if (is_numeric($product_special_price) && ($product_special_price < $product_price)) {
            if ($product->getTypeId() == 'configurable') {
                $basePrice = $product->getPriceInfo()->getPrice('regular_price');

                $product_price = $basePrice->getMinRegularAmount()->getValue();
                $product_special_price = $product->getFinalPrice();
            } else if ($product->getTypeId() == 'bundle') {
                $product_price = $product->getPriceInfo()->getPrice('regular_price')->getMinimalPrice()->getValue();
                $product_special_price = $product->getPriceInfo()->getPrice('final_price')->getMinimalPrice()->getValue();
            } else if ($product->getTypeId() == 'grouped') {
                $usedProds = $product->getTypeInstance(true)->getAssociatedProducts($product);
                foreach ($usedProds as $child) {
                    if ($child->getId() != $product->getId()) {
                        $product_price += $child->getPrice();
                        $product_special_price += $child->getFinalPrice();
                    }
                }
            }

            $percent2 = '';

            $difference = ($product_price - $product_special_price);

            if ($difference > 0) {
                $percent = $difference / $product_price;
            } else {
                $percent = 0;
            }

            $percent_friendly = number_format($percent * 100, 0) . '%';
            if (is_numeric($percent2)) {
                $percent_friendly2 = number_format($percent2 * 100, 0) . '%';
            }

            if ((number_format($percent * 100, 0) > 0)) {
                $save_off_output = "<div class=\"p-img-icon save-icon\">$percent_friendly OFF</div>";

            }
        }

        return $save_off_output;
    }

}
