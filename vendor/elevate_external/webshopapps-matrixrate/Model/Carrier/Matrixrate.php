<?php
/**
 * WebShopApps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * WebShopApps MatrixRate
 *
 * @category WebShopApps
 * @package WebShopApps_MatrixRate
 * @copyright Copyright (c) 2014 Zowta LLC (http://www.WebShopApps.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @author WebShopApps Team sales@webshopapps.com
 *
 */
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace WebShopApps\MatrixRate\Model\Carrier;

use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Matrixrate extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'matrixrate';

    /**
     * @var bool
     */
    protected $_isFixed = false;

    /**
     * @var string
     */
    protected $defaultConditionName = 'package_weight';

    /**
     * @var array
     */
    protected $conditionNames = [];

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $resultMethodFactory;

    /**
     * @var \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\MatrixrateFactory
     */
    protected $matrixrateFactory;
    
    
    protected $prod_addon_lookup;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $resultMethodFactory
     * @param \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\MatrixrateFactory $matrixrateFactory
     * @param array $data
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $resultMethodFactory,
        \WebShopApps\MatrixRate\Model\ResourceModel\Carrier\MatrixrateFactory $matrixrateFactory,
        array $data = []
    ) {
        $this->rateResultFactory = $rateResultFactory;
        $this->resultMethodFactory = $resultMethodFactory;
        $this->matrixrateFactory = $matrixrateFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        foreach ($this->getCode('condition_name') as $k => $v) {
            $this->conditionNames[] = $k;
        }
    }

    /**
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function collectRates(RateRequest $request)
    {


        if (!$this->getConfigFlag('active')) {

            //return false;
        }

        // exclude Virtual products price from Package value if pre-configured
        if (!$this->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual()) {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->isVirtual()) {
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }

        // Free shipping by qty
        //we can also implement here the shipping bypass for a product like downloads

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $freeQty = 0;
        if ($request->getAllItems()) {
        
        
        
        
        
            $freePackageValue = 0;
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }


            $this->prod_addon_lookup[$item->getProduct()->getId()][] = $item->getId();

   




                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeShipping = is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0;
                            $freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeShipping = is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0;
                    $freeQty += $item->getQty() - $freeShipping;
                    $freePackageValue += $item->getBaseRowTotal();
                }
            }
            $oldValue = $request->getPackageValue();
            $request->setPackageValue($oldValue - $freePackageValue);
        }

        if (!$request->getConditionMRName()) {
            $conditionName = $this->getConfigData('condition_name');
            $request->setConditionMRName($conditionName ? $conditionName : $this->defaultConditionName);
        }

        // Package weight and qty free shipping
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();

        $request->setPackageWeight($request->getFreeMethodWeight());
        $request->setPackageQty($oldQty - $freeQty);

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();
        $zipRange = $this->getConfigData('zip_range');
        $rateArray = $this->getRate($request, $zipRange);
  
        $request->setPackageWeight($oldWeight);
        $request->setPackageQty($oldQty);
        $pk = '';
        $foundRates = false;
        $price_array = [];
       // echo "<pre>";

      $foundShippingRate = false;
        $price_array['each_price'] = 0;
        $price_array['all_price'] = [];
                
       $has_free_ship_item = false;
                if ($request->getAllItems()) {
                    $freePackageValue = 0;
                    foreach ($request->getAllItems() as $item) {


                        if ($item->getParentItem()) {
                            continue;
                        }

                        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getProduct()->getId());
                        $pgg = strtolower($product->getProductGroupCode());
                       $item_price = $item->getPrice();
                        $item_qty = $item->getQty();
                          
                             
              // check if an addon
             
                                  
             $id_check = $item->getId();
     /*   if ($product->getTypeId() == 'simple') {
            // $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($_product->getId());

            $product_check = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($product->getId());
            if (isset($product_check[0])) {
                //this is parent product id..

             
               $id_check = $product[0]; 
            

        }
        
        }
        
                  */
               

             
             
             //end addon check                
                             
                $assignmentQuoteItemCollection = $objectManager->create('Elevate\CartAssignments\Model\QuoteItemAssignments')->loadAssignedChildren($id_check);
          $force_free = false;
if(count($assignmentQuoteItemCollection) > 0){
  foreach($assignmentQuoteItemCollection AS $assignmentQuoteItem){
      
//exit;                          $assignmentQuoteItem 

 
                         $addon_item =  $objectManager->create('Elevate\CartAssignments\Model\CartAssignmentsRepository')->get($assignmentQuoteItem->getData('addon_id'));


//andy               $free_shipping_count 
$free_shipping_count = 0;
                   
                    if($addon_item->getForceFreeShipping() == '1'){


                       
                   $force_free = true;
              break;
                      
                         }
         }            
         
        
          }  
          
            if($item->getSku() == '999400'){
                  $force_free = true;
                 } 
             if($item->getSku() == '999401'){
                  $force_free = true;
                 } 
             if($item->getSku() == '999402'){
                  $force_free = true;
                 } 
             if($item->getSku() == '999403'){
                  $force_free = true;
                 }             
                  
                      //get cart assignment and if free delivery option then skip
          if($force_free){
              $has_free_ship_item = true;
          continue;
          }
             
     
                        //if no group code and is a bundle product
                        //lets loop children and grab one - not ideal

                        if($item->getProductType() == 'bundle' && $item->getHasChildren()){

                            //assiging option productgroup to main bundleproduct
                            $optionIds = array();

                                foreach ($item->getChildren() as $child) {
                                    $optionIds[] = $child->getProduct()->getId();
                                    $item_prod = $objectManager->create('Magento\Catalog\Model\Product')->load($child->getProduct()->getId());

                                         
                                    $pgg = $item_prod->getProductGroupCode();
                                    if(!empty($pgg)){
                                        break;
                                    }


                                }

                        }

                           

                            if($item_qty < 1){ $item_qty = 1;}


                        if(isset($_GET['debug'])){
                            if($_GET['debug'] == 'true'){
                               echo "<pre>";
                               print_r($rateArray);
                               echo "</pre>";
                            }
                        }


                        $foundShippingRate = false;
                        $found_product_rate = false;
                        foreach ($rateArray as $rateLoop) {


                            $found_product_rate_data = [];
                            foreach ($rateLoop as $rate) {
                                if (!empty($rate)) {

   

                                 if(strtolower($rate['product_shipping_group']) == strtolower($pgg)){
                                     $found_product_rate = true;


                                    // echo $rate['product_shipping_group']."|".$pgg;
                                        if($rate['rate_type'] == 'EACH'){
                                            $price_array['each_price'] += $rate['price'] * $item_qty;
                                        }
                                        else{
                                            $price_array['all_price'][$rate['product_shipping_group']] = $rate['price'];
                                        }

                                     $pk = $rate['pk'];
                                     $shipping_method = $rate['shipping_method'];
                                     $cost = $rate['cost'];

                                     $foundShippingRate = true;
                                     break;

                                 }
                                 else{

                                     
                                     /*
                                if($item_price > $rate['item_price_over'] && $item_price < $rate['item_price_under_and_equal']){


                                    if($rate['rate_type'] == 'EACH'){
                                        $price += $rate['price'] * $item_qty;
                                    }
                                    else{
                                        $price += $rate['price'];
                                    }
                                    $pk = $rate['pk'];
                                    $shipping_method = $rate['shipping_method'];
                                    $cost = $rate['cost'];
                                    break;
                                }
*/

                                 }


                            }




                        }





                    } //end rate array loop


/* ok - so we might not have found a product match - so we need to fall back the rate*/

                        if(!$found_product_rate){
                                                   
                            foreach ($rateArray as $rateLoop) {
                            
                         
$localFound = false;
                                $local_each_price = 0;
                                $local_all_price = 0;
                        
                        
                        
                        
                        
                        
                               
                                foreach ($rateLoop as $rate) {
                                    if (!empty($rate)) {
                                               

                                        if (strtolower($rate['product_shipping_group']) == '*') {
                                        
                                   
                                           // $found_product_rate = true;
$localFound = true;
 $local_all_price_set = false;
$attribute_match = array_flip(explode(',', $rate['attribute_match']));

                   if(!empty($rate['attribute_match'])){
                             
                  if(array_key_exists($product->getAttributeSetId(), $attribute_match)){
                                

                      $local_pk = $rate['pk'];
                      $local_shipping_method = $rate['shipping_method'];
                      $local_cost = $rate['cost'];
                      if($rate['price']){
                      if ($rate['rate_type'] == 'EACH') {
                   
                          $local_each_price = $rate['price'] * $item_qty;
                      } else { 
                        
                          $local_all_price = $rate['price'];  
                      }
                      }
                      break;
                  }
                                                                                                  
                 }
                 
                 
                       
                  else{
                                    
                      $local_pk = $rate['pk'];
                      $local_shipping_method = $rate['shipping_method'];
                      $local_cost = $rate['cost'];
                      if($rate['price']) {
                          if ($rate['rate_type'] == 'EACH') {
                       
                              $local_each_price = $rate['price'] * $item_qty;
                          } else {
                         
                              $local_all_price = $rate['price'];
                          }
                   
                      }
                  }



                               
                                        }
                                        
                                        //else we need to apply the shipping group for things like "Download - so we need to check against the product"
                                        else{
                                        
                                        
                                        
                                        }
                                        
                                        
                                        
                                        
                                    }
                                }



                                $foundShippingRate = true;


                         if($localFound){


                             $price_array['each_price'] += $local_each_price;
                             
                             
                                     if(!$local_all_price_set){             
                         $price_array['all_price']['*'] = $local_all_price;
                         $local_all_price_set = true; 
                         
                          }
                          else{
                   
                          if($price_array['all_price']['*'] > $local_all_price){
                         
                              $price_array['all_price']['*'] = $local_all_price;
                          
                          }
                           }               
                             

                             $pk = $local_pk;
                             $shipping_method = $local_shipping_method;
                             $cost = $local_cost;

                             $foundShippingRate = true;





                         }                                // echo $rate['product_shipping_group']."|".$pgg;
                                /*

                                */

                            }




                        }



                }

                  
                             

                    //total prices up

$price = array_sum($price_array['all_price']) + $price_array['each_price'];



                   // $price = $all_price + $each_price;

                    if($foundShippingRate){
                    //lets set the code in this part of the loop after we figure out the values in the above loop
                    /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
                    $method = $this->resultMethodFactory->create();

                    $method->setCarrier('matrixrate');
                    
                    //Backend Setting
                    $method->setCarrierTitle('Courier');

                    $method->setMethod('matrixrate_' . $pk);
                    $method->setMethodTitle(__($shipping_method));

                    if ($request->getFreeShipping() === true || $request->getPackageQty() == $freeQty) {
                        $shippingPrice = 0;
                    } else {
                        $shippingPrice = $this->getFinalPriceWithHandlingFee($price);
                    }

                    $method->setPrice($price);
                    $method->setCost();

                    $result->append($method);
                    $foundRates = true; // have found some valid rates
                    }else if($has_free_ship_item){
                                       //lets set the code in this part of the loop after we figure out the values in the above loop
                    /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
                    $method = $this->resultMethodFactory->create();

                    $method->setCarrier('matrixrate');
                    
                    //Backend Setting
                    $method->setCarrierTitle('Courier');

                    $method->setMethod('matrixrate_free');
                    $method->setMethodTitle(__("Free Shipping"));

             $shippingPrice = 0;
                    $method->setPrice(0);
                    $method->setCost();

                    $result->append($method);
                    $foundRates = true; // have found some valid rates 
                    
                    }
                    
                    
                    
                    
            }








        if (!$foundRates) {
            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Error $error */
            $error = $this->_rateErrorFactory->create(
                [
                    'data' => [
                        'carrier' => $this->_code,
                        'carrier_title' => $this->getConfigData('title'),
                        'error_message' => $this->getConfigData('specificerrmsg'),
                    ],
                ]
            );
            $result->append($error);
        }

        return $result;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\RateRequest $request
     * @param bool $zipRange
     * @return array|bool
     */
    public function getRate(\Magento\Quote\Model\Quote\Address\RateRequest $request, $zipRange)
    {
        return $this->matrixrateFactory->create()->getRate($request, $zipRange);
    }

    /**
     * @param string $type
     * @param string $code
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCode($type, $code = '')
    {
        $codes = [
            'condition_name' => [
                'package_weight' => __('Weight vs. Destination'),
                'package_value' => __('Order Subtotal vs. Destination'),
                'package_qty' => __('# of Items vs. Destination'),
            ],
            'condition_name_short' => [
                'package_weight' => __('Weight'),
                'package_value' => __('Order Subtotal'),
                'package_qty' => __('# of Items'),
            ],
        ];

        if (!isset($codes[$type])) {
            throw new LocalizedException(__('Please correct Matrix Rate code type: %1.', $type));
        }

        if ('' === $code) {
            return $codes[$type];
        }

        if (!isset($codes[$type][$code])) {
            throw new LocalizedException(__('Please correct Matrix Rate code for type %1: %2.', $type, $code));
        }

        return $codes[$type][$code];
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['matrixrate' => $this->getConfigData('name')];
    }
}
