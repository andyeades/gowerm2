<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */


namespace Elevate\CartAssignments\Helper;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;


class Items extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_coreSession;
    protected $_assetRepo;
    protected $_cartAssignments;
    protected $_ruleFactory;
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var TimezoneInterface
     */
    private $timezone;
    public function __construct(

        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        DateTime $date,
        TimezoneInterface $timezone,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory,
        \Elevate\CartAssignments\Model\QuoteItemAssignmentsFactory $cartAssignments

    ) {

        $this->_coreSession = $coreSession;
            $this->_assetRepo = $assetRepo;
        $this->date = $date;
        $this->_cartAssignments = $cartAssignments;
        $this->timezone = $timezone;
        $this->_ruleFactory = $ruleFactory;

    }


    public function getCartRow(
        $_product,
        $_item, $original_item_qty, $qty
    ) {
        $_product = $_item->getProduct();
        if($qty < 1){
            $qty= $original_item_qty;
        }

        $output = '
<div class="product_name_wrap">
    <div class="d-none d-md-block" style="color:#006548;font-weight:bold;"></div>
    <h2 class="product-name">
        <a href="' . $_product->getProductUrl() . '">';

        $name_string = "";
        $exp_name = explode('-', ($_product->getName()));
        foreach ($exp_name AS $key => $val) {
            $name_string .= $val . "<br />";
        }


        //store config
        $line_discounts_enabled = 1;

        $output .= $name_string;

        $output .= '   </a>
    </h2>';
       // $cart = Mage::getModel('checkout/cart');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();


        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $_pricingHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');


       // $cart->init();
        $quote = $cart->getQuote();
        //add the new product into the standard basket
        //$quoteItem2 = $quote->addProduct($product_check_act, $quoteItemAssignmentsObject->qty);
        //$item = $quote->getItemByProduct($mModel);
        //$item->getProduct()->setIsSuperMode(true); // this is crucial
        $quote->collectTotals()->save();
        $appliedRuleIds = explode(',', $_item->getAppliedRuleIds());
       // print_r($appliedRuleIds);
      //  $discountBreakdown = $_item->getDiscountBreakdown();
        $itemDiscountBreakdown = $_item->getExtensionAttributes()->getDiscounts();
        if ($itemDiscountBreakdown) {
            foreach ($itemDiscountBreakdown as $value) {
                /* @var \Magento\SalesRule\Api\Data\DiscountDataInterface $discount */
                $discount = $value->getDiscountData();
                $ruleLabel = $value->getRuleLabel();
                $ruleID = $value->getRuleID();
                $discountBreakdown[$ruleID] = $discount->getAmount();
            }

        }



       // print_r($_item->getExtensionAttributes()->getDiscounts());

       // var_dump($_item->getExtensionAttributes()->getDiscounts());
       // foreach((array)$_item->getExtensionAttributes()->getDiscounts() as $key){
       //     print_r($key);
       // }



        if(isset($_GET['debug'])){
           if($_GET['debug'] == 1){
               $_cartAssignmentsModal = $objectManager->create('Elevate\CartAssignments\Model\QuoteItemAssignments');
               $assignment = $_cartAssignmentsModal->loadAssignedChildren($_item->getId());

               echo "<pre>";
               print_r($assignment->getData());
               echo "</pre>";

           }
        }



        $rules = $this->_ruleFactory->create()->getCollection()->addFieldToFilter('rule_id', array('in' => $appliedRuleIds));
        $discount_count = 0;
        $offer = '';
        $reduct = 0;
        foreach ($rules as $rule) {

            $rule_name = $rule->getData('name');
            $rule_id = $rule->getData('rule_id');
            $rule_description = $rule->getData('description');
            $rule_simple_action = $rule->getData('simple_action'); //by_percent
            $rule_discount_amount = $rule->getData('discount_amount'); //10.0000
            //do something wzith $rule      
   
            $rule_price = $discountBreakdown[$rule_id];


            $current_basket_amount = $this->_coreSession->getCABasketAmount();

            $current_basket_rule_ids = $this->_coreSession->getCABasketRuleIds();
            if ($rule_simple_action != 'by_percent') {
                continue;

            }
            $discount_count++;
            if (is_numeric($rule_id)) {
                if(isset($current_basket_rule_ids[$rule_id])){

                    $current_basket_rule_ids[$rule_id] = $current_basket_rule_ids[$rule_id] + $rule_price;
                }
                else{
                    $current_basket_rule_ids[$rule_id] = $rule_price;
                }

            }

            $reduct = ($rule_price / $original_item_qty) * $qty;

            $this->_coreSession->setCABasketRuleIds($current_basket_rule_ids);
            $this->_coreSession->setCABasketAmount($current_basket_amount + $reduct);

            $offer .= "<div class=\"
                                   couponbox
    \" style=\"margin-left:0px;margin-right:0px;\">
  <div>$rule_description - SAVE " . $_pricingHelper->currency(($reduct)) . "</div>
  </div>";

        }
        $cp = $_product;

        if ($option = $_item->getOptionByCode('simple_product')) {
            $cp = $option->getProduct();
        }

        if ($line_discounts_enabled == '1') {


            $output_price = $cp->getFinalPrice() - ($reduct / $qty);

        } else {
            $output_price = $cp->getFinalPrice();
        }

      //  $price_type = Mage::getStoreConfig('elevate_assignments/general/price_type');
        $settingsHelper = $objectManager->get('Elevate\CartAssignments\Helper\Settings');
        $price_type = $settingsHelper->getPriceType();
        if ($price_type == 'ex_vat') {

        } else {

            $output_price = $_item->getData('price_incl_tax');
        }

        $extra_class = "";
        if ($line_discounts_enabled == '1' && $reduct > 0) {
            $extra_class = " intprice_extra";
            $output .= '
        <style>

            .fullprice {
                color:           rgba(255, 0, 0, 0.5);
                text-decoration: none;
                position:        relative;
                color:           rgba(0, 0, 0, 0.6);
                display:         inline-block;
            }

            .intprice_extra {
                font-size:   19px;
                font-weight: bold;
                margin-top:  10px;
                display:     inline-block;
                color:       #db2727;
                margin-left: 10px;
            }

            .fullprice:before {
                content:    " ";
                display:    block;
                width:      100%;
                border-top: 2px solid rgba(255, 0, 0, 0.8);
                height:     12px;
                position:   absolute;
                bottom:     0;
                left:       0;
                transform:  rotate(-7deg);
            }
        </style>
        <div style="font-size: 19px;font-weight: bold;margin-top: 10px;" class="fullprice">
            <span class="amount"></span>';


            if ($line_discounts_enabled == '1' && $_item->getDiscountAmount() > 0) {

                $output .=  $_pricingHelper->currency($cp->getFinalPrice(),true,false);
            } else {

                $output .= $_pricingHelper->currency($cp->getSalePrice(),true,false);

            }

            $output .= '</div>';

        }
        $output .= '
    <div style="    font-size: 19px;
    font-weight: bold;
    margin-top: 10px;" class="intprice' . $extra_class . '">';

        if ($line_discounts_enabled == '1' && $_item->getDiscountAmount() > 0) {
            if ($price_type == 'ex_vat') {
                $output_price = $_item->getPrice() - ($_item->getDiscountAmount() / $_item->getQty());

            } else {
                $output_price = $_item->getData('price_incl_tax') - ($_item->getDiscountAmount() / $_item->getQty());


            }
        }

        $output .= $_pricingHelper->currency($output_price,true,false);

        $output .= '</div>';



        //    if ($messages = $this->getMessages()):
        //       foreach ($messages as $message):
        //          $output .= '<p class="item-msg ' . $message['type'] . '">*
        //     ' . ($message['text']) . '
        //  </p>';
        //      endforeach;
        //  endif;
        // $addInfoBlock = $this->getProductAdditionalInformationBlock();
      //  if ($addInfoBlock):
       //     $output .= $addInfoBlock->setItem($_item)->toHtml();
       // endif;
      //  if ($edit_enabled == 1) {
      //      if ($isVisibleProduct):
      //          $output .= '<a class="cart-p-configure-btn" href="' . $this->getConfigureUrl() . '" title="' . Mage::helper('core')->quoteEscape($this->__('Edit item parameters')) . '">' . $this->__('Edit') . '</a>';
      //      endif;
      //  }

        if ($line_discounts_enabled == '1' && $_item->getDiscountAmount() > 0) {
          //  $output .= 'TEST '.$line_discounts_enabled."|".$_item->getDiscountAmount();
            $output .= '
        <div class="evca-linediscount-outer">
          ';
            if ($discount_count > 0) {
                $offer_text = 'offer is';
                if ($discount_count > 1) {
                    $offer_text = 'offers are';
                }
                $output .= "<div class='evca-linediscount-inner' >
  <div style=\" 
  font-weight: initial;

\">The following $offer_text applied to this product</div>
  $offer
  </div>";
            }

            $output .= '</div>';

        }

        $output .= '</div>';

        $response['output'] = $output;
        $response['reduct'] = $reduct;
        return $response;
    }

}
