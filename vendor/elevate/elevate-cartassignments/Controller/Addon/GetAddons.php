<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Getaddons extends \Magento\Framework\App\Action\Action {
    /**
     * @var ProductRepositoryInterface
     */
    protected $has_sku = [];

    protected $_cart;
    protected $productRepository;
    protected $_sessionQuote;
    protected $_itemModel;
    protected $_cartAssignments;
    protected $_cartAssignmentsModal;
    protected $_cartAddons;
    protected $assigned_addons = [];
    protected $has_assigned_html;
    protected $validation_errors = [];
    protected $pricingHelper;
    protected $_coreSession;
    protected $quoteItemObjectMain = []; 
        protected $_ruleFactory;    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session                    $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator     $formKeyValidator
     * @param CustomerCart                                       $cart
     * @param ProductRepositoryInterface                         $productRepository
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Quote\Model\Quote\Item $itemModel,
        \Elevate\CartAssignments\Model\QuoteItemAssignmentsFactory $cartAssignments,
        \Elevate\CartAssignments\Model\QuoteItemAssignments $cartAssignmentsModal,
        \Elevate\CartAssignments\Model\CartAssignmentsFactory $cartAddons,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\SalesRule\Model\RuleFactory $ruleFactory       

    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->_sessionQuote = $sessionQuote;
        $this->_itemModel = $itemModel;
        $this->_cart = $cart;
        $this->_cartAssignments = $cartAssignments;
        $this->_cartAssignmentsModal = $cartAssignmentsModal;
        $this->_cartAddons = $cartAddons;
        $this->pricingHelper = $pricingHelper;
        $this->_coreSession = $coreSession;
         $this->_ruleFactory = $ruleFactory;
        $this->_localeDate = $localeDate;
    }

    /**
     * Get start of day date.
     *
     * @return string
     */
    public function getStartOfDayDate() {
        return $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
    }

    /**
     * Get end of day date.
     *
     * @return string
     */
    public function getEndOfDayDate() {
        return $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
    }

    public function addValidationError(
        $attribute_id,
        $message
    ) {
        $this->validation_errors[$attribute_id] = $message;
    }

    public function getValidationErrors() {

        return $this->validation_errors;
    }

    function getCurrentProductAssignments($quote_item_id_parent) {


        $collection = $this->_cartAssignments->create()->getCollection()->addFieldToFilter('parent_quote_item_id', $quote_item_id_parent);

        $linked_id = '';
        foreach ($collection as $data) {
            //echo "COLLECTION RUN";
            /*
                 [assignment_id] => 5
                 [linked_quote_item_id] => 1000607
            location = top, bottom, trundle = etc
                 [parent_quote_item_id] => 1000606
                 [addon_id] => 1
                 [template_item_id] => 1
                 [created_at] => 2019-07-29 13:30:20
                 [updated_at] => 0000-00-00 00:00:00
                 [qty] => 4.0000
            */

            $this->assigned_addons[$data['parent_quote_item_id'] . "_" . $data['addon_id']][$data['location']][$data['linked_quote_item_id']] = array(
                'linked_quote_item_id' => $data['linked_quote_item_id'],
                'parent_quote_item_id' => $data['parent_quote_item_id'],
                'addon_id'             => $data['addon_id'],
                'qty'                  => $data['qty'],
                'assignment_id'        => $data['quoteitemassignments_id'],
                'location'             => $data['location']

            );

            $linked_id = $data['linked_quote_item_id'];

        }
        // echo "<pre>DEBUG";
        // print_r($this->assigned_addons);
        /// echo "TEST";
        // exit;

        return $linked_id;
    }

    //gets the addon block for a single product
    public function getAddonBlock() {
        $count_addons = 0;
        $mobile_price_update = [];

        /*return output of addons*/
        $productOutputBuilt = '';
        $productOutput = '';
        $quote_item_id_parent = $this->getRequest()->getParam('itemid'); //current item

        //get parent
        /** @var $quote \Magento\Quote\Model\Quote */
        $quote = $this->_sessionQuote->getQuote();

        //get the item quote object
        if ($quote_item_id_parent) {
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $quoteItemObjectMain = $_objectManager->get('Magento\Quote\Model\Quote\Item')->load($quote_item_id_parent);

            $this->quoteItemObjectMain['id'] = $quoteItemObjectMain->getId();
            $this->quoteItemObjectMain['name'] = $quoteItemObjectMain->getName();
            $this->quoteItemObjectMain['product_id'] = $quoteItemObjectMain->getProductId();
            $this->quoteItemObjectMain['sku'] = $quoteItemObjectMain->getSku();
            $this->quoteItemObjectMain['simple_product'] = $quoteItemObjectMain->getOptionByCode('simple_product');
            $this->quoteItemObjectMain['row_total'] = $quoteItemObjectMain->getRowTotal();
            $this->quoteItemObjectMain['row_total_incl_tax'] = $quoteItemObjectMain->getRowTotalInclTax();
            $this->quoteItemObjectMain['discount_amount'] = $quoteItemObjectMain->getDiscountAmount();
            $this->quoteItemObjectMain['qty'] = $quoteItemObjectMain->getQty();

            //    $item_product_match_attribute_one_parent = $cp->getData('choose_size');

            //echo $productData->getProductId();
            // $quoteItemObjectMain = $this->_cart->getQuote()->getItemById($quote_item_id_parent);

            // $quoteItemObjectMain = $this->_itemModel->load($quote_item_id_parent);

        } else {
            return false;
        }

        $item_product_id = $this->quoteItemObjectMain['product_id'];

        $item_product_sku = $this->quoteItemObjectMain['sku'];
        $parent_product = $item_product_id;
        //current product assignements
        $linked_id = $this->getCurrentProductAssignments($quote_item_id_parent);

        $allow_readmore = false;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $parentProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($item_product_id);

        $product = '';

        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('quote_item');
        $attribute_information = "Select product_id FROM " . $tableName . " where parent_item_id = '" . $item_product_id . "'"; //check for the  custom attribute condition". WHERE id = " . $manufacture . ";";
        // fetchOne it return the one value
        $result = $connection->fetchOne($attribute_information);

        if (is_numeric($result)) {
            $parentProduct = $objectManager->create('Magento\Catalog\Model\Product')->load($result);
        }

        //echo $parentProduct->getSku();

        $collection = $this->_cartAddons->create()->getCollection()->setOrder('position', 'ASC')->addFieldToFilter('enable_addon', 1)/*                                             ->addFieldToFilter(
                'assigned_skus', array(
                                   array('finset' => array($parentProduct->getSku())),
                                   array('null' => true),
                                   array('eq' => ''),
                                   array('eq' => 0)

                               )*/ //    )
                                        ->addFieldToFilter(
                'start_date', [
                'date' => true,
                'to'   => $this->getEndOfDayDate()
            ], 'left'
            );
        //  ->addFieldToFilter(
        //      'end_date',
        //      [
        //          'or' => [
        //              0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
        //              1 => ['is' => new \Zend_Db_Expr('null')],
        //         ]
        //    ],
        //   'left');
        //   ->addStoreFilter($this->getStoreId())
        //   ->setCurPage(1);
        // echo $collection->getSelect();

        //these are the candidates to show
        //  echo "<pre>";
        foreach ($collection as $data) {


            $assigned_skus = $data->getData('assigned_skus');
            if (!empty($assigned_skus)) {


                $ass_exp = explode(',', $assigned_skus);
                $ass_exp = array_flip($ass_exp);

                if (!array_key_exists($parentProduct->getSku(), $ass_exp)) {
                    continue;
                }
            }

            //print_r($data->getData());
            $addon_sku = trim($data->getData('sku'));
            $addon_type = $data->getData('addon_type'); //e.g product, static

            /*NEEDS SORTING*/
            $product_match_attribute_one_parent = $data->getData('product_match_attribute_one_parent');
            $item_product_match_attribute_one_parent = $data->getData('product_match_attribute_one_parent');
            $cp = $parentProduct;
            if ($option = $this->quoteItemObjectMain['simple_product']) {
                $cp = $option->getProduct();

                $item_product_match_attribute_one_parent = $cp->getData('choose_size');

                //  $cp = Mage::getModel('catalog/product')->load($cp->getId());
                //     $cp = $objectManager->create('Magento\Catalog\Model\Product')->load($cp->getId());

            }

            if ($data->getData('cartassignments_id') == '2') {
                $item_product_match_attribute_one_parent = $parentProduct->getData('choose_size');
            }

            $skip_product = true;

            if (empty($product_match_attribute_one_parent)) {

                $skip_product = false;

            }
            /*END NEEDS SORTING*/

            /// if the addon is a product addon

            if ($addon_type == 'product') {

                if (!empty($addon_sku)) {
                    //  $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                    $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
                    try {
                        $product = $productRepository->get($addon_sku);
                    } catch(\Exception $e) {
                        $this->addValidationError($data->getData('cartassignments_id'), 'noprod-' . $addon_sku);
                        continue;
                    }

                    // if this is a configurable product - how will we handle
                    if ($product->getTypeId() == "configurable") {


                        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                        $connection = $resource->getConnection();
                        $tableName = $resource->getTableName('quote_item');
                        $attribute_information = "Select product_id FROM " . $tableName . " where parent_item_id = '" . $this->quoteItemObjectMain['id'] . "'"; //check for the  custom attribute condition". WHERE id = " . $manufacture . ";";
                        // fetchOne it return the one value
                        $result = $connection->fetchOne($attribute_information);

                        $product_main = $objectManager->create('Magento\Catalog\Model\Product')->load($result);
                        $item_product_match_attribute_one_parent = $product_main->getData('choose_size');

                        //     $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(NULL, $product);
                        $childProducts = $product->getTypeInstance()->getUsedProducts($product);
                        foreach ($childProducts as $child) {

                            if (!empty($item_product_match_attribute_one_parent)) {

                                if ($item_product_match_attribute_one_parent == $child->getData('choose_size')) {
                                    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($child->getId());
                                    if ($product) {
                                        $skip_product = false;
                                    }
                                };  // You can use any of the magic get functions on this object to get the value
                            }
                        }

                    } else {
                        //for simples
                        // echo "YES.$item_product_match_attribute_one_parent";
                        if (!empty($item_product_match_attribute_one_parent)) {
                            if ($item_product_match_attribute_one_parent == $product->getData('choose_size')) {
                                $skip_product = false;
                            };  // You can use any of the magic get functions on this object to get the value
                        }
                    }

                }
                //dont show if same product
                if ($item_product_sku == $addon_sku) {
                    $this->addValidationError($data->getData('cartassignments_id'), 'same');
                    // continue;
                }

            }

            //why are we doing this here
            if ($skip_product) {

                $this->addValidationError($data->getData('cartassignments_id'), 'Skip');
                continue;
            }

            $quoteItemObject = $this->_itemModel->load($linked_id);

            $quoteItemAssignmentsObject = $this->_cartAssignments->create()->getCollection()->addFieldToFilter('linked_quote_item_id', $linked_id);

            $validation = $this->validateAddon($data, $product, $item_product_id, $item_product_sku, $quote_item_id_parent, $parent_product, $linked_id);
            // $validation = true;
            //skip this addon if doesnt validate
            if (!$validation) {
                if (isset($_GET['debug'])) {
                    if ($_GET['debug'] == 1) {
                        echo "<Pre>";
                        print_r($this->getValidationErrors());
                        echo "</Pre>";
                    }
                }

                continue;
            }

            //check postcode
            $enable_postcode = false;

            if ($data['enable_postcode'] == '1') {
                $enable_postcode = true;
            }
            if (!$quoteItemObject && (($data->getData('addon_type') == 'product') || ($data->getData('addon_type') == 'multibox'))) {

                //$quoteItemAssignmentsObject3 = Mage::getModel('elevate_assignments/quote_item_assignments')->loadAssigned($linked_id);

                $quoteItemAssignmentsObject3 = $this->_cartAssignments->create()->getCollection()->addFieldToFilter('linked_quote_item_id', $linked_id);

                // print_r($quoteItemAssignmentsObject->getData());

                if ($quoteItemAssignmentsObject3) {

                    //   $quoteItemAssignmentsObject3->delete();
                }

                // $product_check = Mage::getModel('catalog/product')->loadByAttribute('sku', $data->sku);

                $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
                $product_check = $productRepository->get($data->getData('sku'));
                if (!$product_check) {

                    //check this delete
                    // $quoteItemAssignmentsObject3->delete();
                    //remove the addons if no product anymore!
                    // continue;
                }

            }

            $count_addons++;
            $mobile_price_update = [];
            $allow_readmore = false;
            $show_div = ' id="moreitems_' . $quote_item_id_parent . '" class="show_me_anchor"';
            if ($count_addons > 3) {
                if (array_key_exists($quote_item_id_parent . "_" . $data->getData('cartassignments_id'), $this->assigned_addons)) {
                    $allow_readmore = true;
                }
                $show_div = ' id="moreitems_' . $quote_item_id_parent . '" class="hide_me_anchor"';
            }

            if ($data->getData('addon_type') == 'product') {
                $addon_box = $this->addonBoxHtml(
                    $quoteItemObjectMain, //parent object
                    $quoteItemAssignmentsObject, //assignments to this product
                    $data, //addon data,
                    $product, //product object - created in validation above only if product sku
                    $linked_id
                );
                $productOutput .= "<span $show_div>" . $addon_box['list'] . "</span>";

                if (isset($mobile_price_update[$addon_box['mobile_price']['id']])) {
                    $mobile_price_update[$addon_box['mobile_price']['id']] = $mobile_price_update[$addon_box['mobile_price']['id']] + $addon_box['mobile_price']['price'];
                }
            } else if ($data->getData('addon_type') == 'multibox') {

                $productOutput .= "<span $show_div>" . $this->addonMultiBoxHtml(

                        $quoteItemObjectMain, $data, $quoteItemAssignmentsObject, $quoteItemObject, $linked_id, $item_product_id
                    ) . "</span>";

            } else if ($data->getData('addon_type') == 'static') {

                $productOutput .= "<span $show_div>" . $this->addonStaticBoxHtml(
                        $quoteItemObjectMain, $data, $quoteItemAssignmentsObject
                    ) . "</span>";

            }

        } //end the collection
        if ($allow_readmore) {
            $productOutput .= '<style>#moreitems_' . $quote_item_id_parent . '.hide_me_anchor  {
            display:block !important;
            }</style>';
        }
        if ($count_addons > 3 && !$allow_readmore) {

            $productOutput .= '<div class="rmlink" id="readmoreitems_' . $quote_item_id_parent . '" style="cursor:pointer;text-align:center;" onclick="readmore(\'' . $quote_item_id_parent . '\');">
          <div href="#" class="read-btn read" style="
    text-decoration: underline;
">View More Offers</div>
        </div>';

            $productOutput .= '
            <script type="text/javascript">

 function readmore(myclass){

         jQuery(\'#moreitems_\'+myclass+\'.hide_me_anchor\').addClass(\'show_me\');
 jQuery(\'#readmoreitems_\'+myclass).addClass(\'hideme\').hide();
        }


  </script>';
        }
        $productOutputBuilt = $productOutput;
        // $price_type = Mage::getStoreConfig('elevate_assignments/general/price_type');

        //NEEDS CONFIG

        // $settingsHelper = $this->helper('Elevate\CartAssignments\Helper\Settings');

        $settingsHelper = $objectManager->get('Elevate\CartAssignments\Helper\Settings');
        $price_type = $settingsHelper->getPriceType();
                 $output_price_add_ex = $this->quoteItemObjectMain['row_total'];
            $output_price_add_inc = $this->quoteItemObjectMain['row_total_incl_tax'];
       
        if ($this->quoteItemObjectMain['discount_amount'] > 0) {
            $output_price_add_ex = $output_price_add_ex - ($this->quoteItemObjectMain['discount_amount']);
              $output_price_add_inc = $output_price_add_inc - ($this->quoteItemObjectMain['discount_amount']);
        }

          $_pricingHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
               
        $qty = 1;
        $original_item_qty = $this->quoteItemObjectMain['qty'];

        /*
        $output = Mage::helper('elevate_assignments/items')->getCartRow($parentProduct,
                                                                        $quoteItemObjectMain,
                                                                        $original_item_qty,
                                                                    $qty);

         */
                 $output = '';
     $output .= '<div class="price-excluding-tax">'.$_pricingHelper->currency($output_price_add_ex,true,false).'</div>';
        $output .= '<div class="price-including-tax">'.$_pricingHelper->currency($output_price_add_inc,true,false).'</div>';
                $response['price_update'] = $output;
                
                
                
                
                
                
                
                
                
                
                
        foreach ($mobile_price_update as $key => $val) {
                            $output = '';
     $output .= '<div class="price-excluding-tax">'.$_pricingHelper->currency($val + $output_price_add_ex,true,false).'</div>';
        $output .= '<div class="price-including-tax">'.$_pricingHelper->currency($val + $output_price_add_inc,true,false).'</div>';
            $response['mobile_price_update'][$key] = $output;
        }
        /// $response['left_side'] =   $output['output'];
     
        $response['list'] = $productOutputBuilt;
        echo json_encode($response);

    }

    public function addonStaticBoxHtml(
        $quote_item_parent = false,
        $data,
        $quoteItemAssignmentsObject
    ) {
        $productOutput = '';
        $product_id = '';
        $quote_item_id_parent = $quote_item_parent->getId();
        $addon_id = $data['addon_id;'];
        //if(start_date < now)
        //if(end_date0
        //title
        //description
        //)
        $already_added = false;

        $productOutput .= '<div class="multibox addonwrap prod_' . $product_id . '">';
        //$productOutput .= print_r($data, true);
        /*
         (
              [addon_id] => 3
              [title] => 2 Years Free Warranty with this product
              [addon_type] => static
              [template_id] => 0
              [description] => We offer guarantees on all our mattresses for 2 years against any defects.
              [start_date] => 2019-07-30 22:02:29
              [end_date] => 2019-07-30 22:02:29
              [sku] => 12345
              [assigned_categories] => 0
              [conditions] =>
              [assigned_skus] =>
              [promotion_message] =>
              [enable_countdown_timer] => 0
              [countdown_time] => 2019-08-28 23:59:59
              [countdown_background_colour] =>
              [countdown_font_colour] =>
              [countdown_font_colour_overlay] =>
              [enable_image_overlay_banner] =>
              [enable_quantity] => 0
              [match_quantity] => 0
              [position] => 0
          )
        */
        $icon = $data['custom_icon'];
        $link_type = $data['link_type'];
        $link_url = $data['link_url'];
        $link_static_block_id = $data['link_static_block_id'];
        $link_text = $data['link_text'];
        $link_style = $data['link_style'];
        $lightbox_title = $data['lightbox_title'];
        $lightbox_footer = $data['lightbox_footer'];
        $inc_title = '';
        $inc_footer = '';

        if (!empty($lightbox_footer)) {

            $inc_footer = 'data-footer="#lbfooter_' . $quote_item_id_parent . "_" . $data['id'] . '"';
            $link_output = '<div  style="display:none;" id="lbfooter_' . $quote_item_id_parent . "_" . $data['id'] . '">' . $lightbox_footer . '</div>';
        }
        if (!empty($lightbox_title)) {

            $link_output .= '<div  style="display:none;" id="lbtitle_' . $quote_item_id_parent . "_" . $data['id'] . '">' . $lightbox_title . '</div>';
            $inc_title = 'data-title="#lbtitle_' . $quote_item_id_parent . "_" . $data['id'] . '"';
        }

        $discount_rule_id = $data['discount_rule_id'];
        $link_output = '';

        if ($link_type == 'target_blank') {

            $link_output = '<a target="_blank" class="itemrowlink" style="' . $link_style . '" href="' . $link_url . '">' . $link_text . '</a>';
        }
        if ($link_type == 'lightbox') {

            $link_output .= '<span class="evlightbox itemrowlink" ' . $inc_title . ' ' . $inc_footer . '  data-title-type="div" data-footer-type="div" data-body-type="url" data-body="/assignments/addon/getstaticblock/id/' . $link_static_block_id . '" style="' . $link_style . '">' . $link_text . '</span>
            <script>ELEVATE.Lightbox.attachEventHandlers();</script>
            ';
        }

        $productOutput .= '<div class="row">
         <div class="col-md-2 col-xs-3 col-3" style="padding:0;"><img style="max-width:100%;" src="/media' . $icon . '" />
                  </div>

                   <div class="col-md-10 col-xs-9 col-9" >
                    <div class="replacewrap">
                        <div style="font-weight: bold;margin-bottom: 10px;font-size: 14px;">' . $data['title'] . '</div>';

        $productOutput .= '    <div style-"font-size: 13px;">' . $data['description'] . '</div>

        <div>' . $link_output . '</div>
                    </div>';

        $productOutput .= '
                    </div>
                    </div>
                    <div style="clear:both;"></div>
                    </div>';

        return $productOutput;

    }

    public function addonMultiBoxHtml(
        $quote_item_parent = false,
        $data,
        $quoteItemAssignmentsObject,
        $quoteItemObject,
        $linked_id,
        $item_product_id = false
    ) {


        $productOutput2 = '';
        // $collection = $quoteItemAssignmentsObject;
        //       foreach($collection as $obj) {
        $product_sku = '';
        //$quoteItemAssignmentsObject = $obj;
        //           echo "<pre>";
        // print_r($quoteItemAssignmentsObject);
        //         print_r($obj);
        // break;

        //  $assigned_locations[$obj->location] =
        // }
        $addon_id = $data['cartassignments_id'];
        $product_id_parent = $quote_item_parent->getProductId();

        $prody = $this->getRequest()->getParam('product_id');
        $req_item_id = $this->getRequest()->getParam('itemid');
        $product_id = $prody;

        $arrit = [];

        // 1291983
        $item_id = $this->quoteItemObjectMain['id'];//$quote_item_parent->getId();
        $already_added = false;
        $title = '';
        $description = '';

        $productOutput = '<div class="multibox addonwrap prod_' . $product_id . '">';

        $productOutput .= '<div >
                       <div style="font-weight: bold;margin-bottom: 10px;font-size: 16px;"  class="mbpromotop">
                           <span style="color:#db2727;">SAVE 10%</span> on a mattress when you buy a bed
                       </div>
                    <div class="replacewrap">
                    <div style="font-weight: bold;margin-bottom: 10px;font-size: 14px;">' . $title . '</div>';
        $productOutput .= '<div style-"font-size: 13px;">' . $description . '</div>';

        $showOutput = false;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $_product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);

        //$productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
        //$product_check = $productRepository->get($data->getData('sku'));

        //  $_product = Mage::getModel('catalog/product')->load($product_id);
        $_Origproduct = $_product;
        // do stuff here

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $par_prody = $prody;

        //  $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product_id);
        $product_paa = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($prody);
        if (isset($product_paa[0])) {
            //this is parent product id..
            $par_prody = $product_paa[0];
        }

        $collection = $objectManager->create('Elevate\ProductAddons\Model\ResourceModel\LinkedProducts\Collection')->addFieldToFilter('product_id', $par_prody);

        $isBuyTogether = false;
        $isBuyTogether2 = false;
        $isBuyTogether3 = false;

        foreach ($collection as $row) {

            if ($row['linked_addon_type'] == 'top') {

                $_buyTogetherProducts[] = $row['linked_product_id'];
                $isBuyTogether = true;
                $showOutput = true;
            } else {
                $complete_hide = 'style="display:none;"';
            }
            if ($row['linked_addon_type'] == 'bottom') {
                $isBuyTogether2 = true;
                $showOutput = true;
            }
            if ($row['linked_addon_type'] == 'trundle') {
                $isBuyTogether3 = true;
                $showOutput = true;
            }

        }

        $result = $item_id;
        $type_id = $_product->getTypeId();

        if ($type_id == 'configurable') {


            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('quote_item');
            $attribute_information = "Select item_id FROM " . $tableName . " where parent_item_id = '" . $item_id . "'"; //check for the  custom attribute condition". WHERE id = " . $manufacture . ";";
            // fetchOne it return the one value
            $result = $connection->fetchOne($attribute_information);
        }

        $mattress_one_title = "Choose a Mattress";

        if ($isBuyTogether2) {
            $mattress_one_title = "Choose a Top Mattress";
        }

        //get the existing linkups
        if (array_key_exists($req_item_id . "_" . $data['cartassignments_id'], $this->assigned_addons)) {

            $count = 0;
            // $count = count($this->assigned_addons[$item_id . "_" . $data->getAddonId()]);
            foreach ($this->assigned_addons[$req_item_id . "_" . $data['cartassignments_id']] as $location => $subcollection) {
                foreach ($subcollection as $linked_id => $addon) {
                    $count++;
                }
            }

            $i = 0;
            // $productOutput .= '<pre>';
            // $productOutput .= print_r($this->assigned_addons, true);
            // $productOutput .= '</pre>';
            foreach ($this->assigned_addons[$req_item_id . "_" . $data['cartassignments_id']] as $location => $subcollection) {

                foreach ($subcollection as $linked_id => $addon) {

                    $i++;
                    $row_style = " border-bottom: 1px solid #ccc;
    padding-bottom: 20px;
    margin-bottom: 20px;position:relative;";

                    if ($i == $count) {

                        $row_style = " border-bottom: 0px solid #ccc;

   position:relative;";

                    }

                    //print_r($addon);

                    $assignment_id = $addon['assignment_id'];

                    //         print_r($this->assigned_addons);
                    $linked_item_id = $addon['linked_quote_item_id'];

                    $quoteItemAssignmentsObject = $this->_cartAssignmentsModal->loadAssigned($linked_item_id);

                    // $quoteItemAssignmentsObject = Mage::getModel('elevate_assignments/quote_item_assignments')->loadAssigned($linked_item_id);
                    $cartsingl = $this->_cart;
                    $quoteItemObject = $cartsingl->getQuote()->getItemById($linked_item_id);

                    $quoteProduct = $quoteItemObject->getProduct();
                    $product_image = '';//Mage::helper('catalog/image')->init($quoteProduct, 'small_image')->resize(160, 160);
                    $product_name = $quoteProduct->getName();
                    $product_id = $quoteProduct->getId();
                    $product_sku = $quoteProduct->getSku();
                    // $productBlock = $this->getLayout()->createBlock('catalog/product_price');
                    // $price_block = $productBlock->getPriceHtml($quoteProduct);
                    $product_price = 0; //$quoteProduct->getData('price');
                    $final_price = $product_price;
                    $price_type = 'inc_vat';

                    if ($price_type == 'ex_vat') {

                        $final_price = Mage::helper('tax')->getPrice($quoteProduct, $quoteProduct->getFinalPrice(), false);

                    } else {

                        $final_price = (float)$quoteProduct->getFinalPrice();
                        // $final_price = (float)$quoteProduct->getData('special_price');

                    }

                    if ($final_price < 0.01) {
                        $final_price = $product_price;

                    }

                    $line_discounts_enabled = true;

                    $productOutput2 .= '';
                    $has_quant = $quoteItemAssignmentsObject['qty'];
                    $col_hid = '';
                    $price_block = '';
                    $spinner_code = '';
                    // $has_quant = $quoteItemAssignmentsObject->qty;
                    //  $productOutput .= "<pre>".$has_quant."</pre>";

                    $productOutput2 .= '


            <div class="evca-selected-item-row">
                <div class="row" style="' . $row_style . '">
                <div class="col-12">';
                    if ($has_quant > 0) {
                        /*Increment Code*/
                        $productOutput2 .= '<div class="addon-change-qty-outer">
        <span class="decrement_qty change_addon_qty_dec change_addon_qty" ' . $col_hid . 'data-type="decrement"><i class="fa fa-minus" style="vertical-align: middle;"></i></span>
        <input type="text" pattern="\d*" name="cart[\'assignment_' . $assignment_id . '\'][qty]"
        value="' . $has_quant . '" size="4" data-quote_item_assignment_id="' . $assignment_id . '" title="Qty" class="change_addon_qty_input input-text qty" maxlength="12"/>
        <span class="increment_qty change_addon_qty_incr change_addon_qty" data-type="increment"><i class="fa fa-plus" style="vertical-align: middle;"></i></span>
        <div style="clear:both;"></div></div>
        <script>ELEVATE.Assignments.attachEventHandlers();</script>';
                        /*End Increment Code*/
                    }
                    $productOutput2 .= '</div>
                <div class="col-md-3 col-xs-3 col-3">';



                    $productOutput2 .= ' <img src="' . $this->addonBoxImage($quoteProduct) . '" /></div>
                <div class="mid-options-col col-md-8 col-xs-7 col-7 leftalign">
                 <strong>Selected ' . ucwords($location) . ' Mattress</strong>
                ' . $product_name . '' . str_replace('Was From', 'Was', str_replace('Now From', 'Now', $price_block)) . '<div class="prod-price-offer">
              Offer Price              <span class="price ep3 ep99999" id="product-price-1291988">&pound;' . number_format((float)($final_price * 0.9), 2) . '                 </span>
            </div>

    ';

                    $productOutput2 .= '</div>
                <div class="right-options-col col-md-1 col-xs-2 col-2">

 <input class="evca_remove_addon_checkbox" onclick="removeAddon(\'' . $addon['assignment_id'] . '\');"  type="checkbox" checked="checked">


                </div>
                ';
                    $productOutput2 .= '<div class="addon_price_float"
                id="rowpriceprod_' . $spinner_code . '">
&pound;' . number_format((float)($final_price * 0.9 * $has_quant), 2) . '</div>';
                    //$productOutput .= 'TEST';
                    if ($line_discounts_enabled == '1' && $quoteItemObject) {


                        $appliedRuleIds = explode(',', $quoteItemObject->getAppliedRuleIds());

                        $new_quote = $cartsingl->getQuote();
                        $new_quote->collectTotals();
                        $discountAmount = $quoteItemObject->getDiscountAmount();

                        $itemDiscountBreakdown = $quoteItemObject->getExtensionAttributes()->getDiscounts();
                        if ($itemDiscountBreakdown) {
                            foreach ($itemDiscountBreakdown as $value) {
                                /* @var \Magento\SalesRule\Api\Data\DiscountDataInterface $discount */
                                $discount = $value->getDiscountData();
                                $ruleLabel = $value->getRuleLabel();
                                $ruleID = $value->getRuleID();
                                $discountBreakdown[$ruleID] = $discount->getAmount();
                            }

                        }
                        //$productOutput .= 'andy';
                       // $productOutput .= print_r($discountBreakdown, true);
                        $ruleFactory = $objectManager->create('Magento\SalesRule\Model\RuleFactory')->create();

                        $rules = $ruleFactory->getCollection()->addFieldToFilter('rule_id', array('in' => $appliedRuleIds));

                        $discount_count = 0;
                        $offer = '';
                        foreach ($rules as $rule) {
                            $rule_name = $rule->getData('name');
                            $rule_id = $rule->getData('rule_id');

                            $rule_description = $rule->getData('description');
                            $rule_simple_action = $rule->getData('simple_action'); //by_percent
                            if ($rule_simple_action != 'by_percent') {
                                continue;

                            }
                            $rule_discount_amount = $rule->getData('discount_amount'); //10.0000
                            //do something with $rule
                            $discount_count++;

                            $rule_price = $discountBreakdown[$rule_id];

                           // $rule_price = $discountAmount;
                            $current_basket_amount = $this->_coreSession->getCABasketAmount();

                            $current_basket_rule_ids = $this->_coreSession->getCABasketRuleIds();

                            if (is_numeric($rule_id)) {
                            if(isset($current_basket_rule_ids[$rule_id])){
                                $current_basket_rule_ids[$rule_id] = $current_basket_rule_ids[$rule_id] + $rule_price;
                                }
                                else{
                                 $current_basket_rule_ids[$rule_id] = $rule_price;
                                }
                            }

                            $reduct = (($rule_price / $quoteItemObject->getQty()) * $has_quant);
                            // $quoteItemObject->getQty() *$has_quant
                            // Mage::getSingleton('core/session')->setCABasketRuleIds($current_basket_rule_ids);
                            // Mage::getSingleton('core/session')->setCABasketAmount($current_basket_amount+$reduct);

                            $offer .= "<div class=\"couponbox

    \">
  <div>$rule_description - Save &pound;" . number_format((float)($reduct), 2) . "</div>
  </div>";
                        }
                        if ($discount_count > 0) {
                            $productOutput2 .= '<div  class="evca_discount_text_container">';
                            $offer_text = 'offer is';
                            if ($discount_count > 1) {
                                $offer_text = 'offers are';
                            }
                            $productOutput2 .= '<div>
  <div class="evca_offer_text">The following ' . $offer_text . ' applied to this product' . $offer . '</div>';

                            $productOutput2 .= '</div>';
                        }

                        $productOutput2 .= '</div>';

                    }
                    $productOutput2 .= '</div>';

                    $arrit[$location] = array('qty' => 0);
                    $arrit[$location]['qty'] = $arrit[$location]['qty'] + $has_quant;

                }
            }
        }

        $can_show = false;

        $topQty = 0;
        if (isset($arrit['top']['qty'])) {
            $topQty = (int)$arrit['top']['qty'];
        }

        $orig_quote_qty = (int)$quote_item_parent->getQty();
        // $productOutput .=  "".$topQty."|".$orig_quote_qty."|".$quoteItemAssignmentsObject['qty'];;
        if ($orig_quote_qty > $topQty) {
        } else {
            $isBuyTogether = false;
        }

        $topQty = 0;
        if (isset($arrit['bottom']['qty'])) {
            $topQty = (int)$arrit['bottom']['qty'];
        }       // $orig_quote_qty = (int)$quote_item_parent['qty'];
        //  $productOutput .=  "".$topQty."|".$orig_quote_qty;
        if ($orig_quote_qty > $topQty) {
        } else {
            $isBuyTogether2 = false;
        }

        $topQty = 0;
        if (isset($arrit['trundle']['qty'])) {
            $topQty = (int)$arrit['trundle']['qty'];
        }       // $orig_quote_qty = (int)$quote_item_parent['qty'];
        //  $productOutput .=  "".$topQty."|".$orig_quote_qty;
        if ($orig_quote_qty > $topQty) {
        } else {
            $isBuyTogether3 = false;
        }
        //print_r($arrit);
        if ($isBuyTogether) {
            $words = '<strong>Top</strong>';
            if (!$isBuyTogether2) {

                $words = '';
            }

            $productOutput .= '<div class="btn btn-info"  onclick="addMultiItemPopup(\'' . $item_id . '\', \'' . $prody . '\', \'top\', \'' . $addon_id . '\');"><i class="fa fa-plus-circle" aria-hidden="true"></i>Choose ' . $words . ' Mattress <span class="seeoptions">(See Options)</span></div>';
        }
        if ($isBuyTogether2) {
            $productOutput .= '<div class="evca-breaker-line"></div>';
            $productOutput .= '<div class="btn btn-info"  onclick="addMultiItemPopup(\'' . $item_id . '\', \'' . $prody . '\', \'bottom\', \'' . $addon_id . '\');"><i class="fa fa-plus-circle" aria-hidden="true"></i>Choose <strong>Bottom</strong> Mattress <span class="seeoptions">(See Options)</span></div>';

        }
        if ($isBuyTogether3) {
            $productOutput .= '<div class="evca-breaker-line"></div>';
            $productOutput .= '<div class="btn btn-info"  onclick="addMultiItemPopup(\'' . $item_id . '\', \'' . $prody . '\', \'trundle\', \'' . $addon_id . '\');"><i class="fa fa-plus-circle" aria-hidden="true"></i>Choose <strong>Trundle</strong> Mattress <span class="seeoptions">(See Options)</span></div>';
        }

        $productOutput .= $productOutput2;

        $productOutput .= '
      <div id="multicontainer_' . $item_id . '"></div>
      ';

        $productOutput .= '</div>
                     ';

        $checked = '';

        $location = 'add';
        $quote_item_id_parent = ''; //needs adding
        if (array_key_exists($product_sku, $this->has_sku)) {
            $already_added = true;
            $checked = 'checked="checked"';
            $location = 'remove';
            $remove_item_id = $this->has_sku[$product_sku];

            $clickEvent = 'addItemToCart(' . $product_id . ', ' . $quote_item_id_parent . ', \'' . $location . '\', \'' . $remove_item_id . '\', false, \'' . $addon_id . '\', this.id, \'check\', \'rowprice_' . $remove_item_id . '\');';
        } else {


            $clickEvent = 'checkPostcodeArea(\'#mattcontainer_' . $quote_item_id_parent . ' .prod_' . $product_id . ' .col-md-10 .replacewrap\', \'' . $product_id . '\', \'' . $quote_item_id_parent . '\');';
        }

        $productOutput .= '</div> ';

        if ($already_added) {
            $productOutput .= '<div class="addon_price_float" id="rowprice_' . $remove_item_id . '" style="    right: -190px !important;
    width: 120px;
    text-align: left !important;">&pound;' . round($product_price * $this->has_qty[$product_sku], 2) . '</div>';

        }
        else{


            if ($already_added) {
                $productOutput .= '<div class="addon_price_float" id="rowprice_' . $remove_item_id . '" style="    right: -190px !important;
    width: 120px;
    text-align: left !important;"></div>';

            }
        }
        $productOutput .= ' <div style="clear:both;"></div></div>';
        if (!$showOutput) {
            return '';
        }

        return $productOutput;

    }

    public function validateAddon(
        $data,
        $product,
        $item_product_id,
        $item_product_sku,
        $quote_item_id_parent,
        $parent_product,
        $linked_id
    ) {


        /*
         * contained in data
         *  [addon_id] => 1
         *  [enable_addon] => 1/0
            [title] => Hypoallergenic Comfort Sleep Pillows - 2pk
            [addon_type] => product
            [template_id] => 0
            [description] => Enjoy a fresh nights sleep for less with a new set of pillows. Supreme hypoallergenic properties and a breathable comfort sleep
            [start_date] => 2019-07-09 00:00:00
            [end_date] => 2019-07-17 00:00:00
            [sku] => ADDON-PILLOW-2PK
            [product_match_attribute_one_parent] =>
            [product_match_attribute_one_child] =>
            [product_match_attribute_two_parent] =>
            [product_match_attribute_two_child] =>
            [assigned_categories] => 0'dependant_addon_blacklist',dependant_addon_ids
            [conditions] =>
            [assigned_skus] =>
            [promotion_message] => Testing
            [enable_countdown_timer] => 1
            [countdown_time] => 2019-08-28 23:59:59
            [countdown_background_colour] => #FFFFFF
            [countdown_font_colour] => #000000
            [countdown_font_colour_overlay] => #000000
            [enable_image_overlay_banner] => /homepage/immune_support_factor.jpg
            [enable_quantity] => 1
            [match_quantity] => 0
            [cap_qty_type] =>
            [cap_qty_amount] => 0
            [position] => 12345

         * if product type - validates product exists
         * check if right category
         *
         *
         * */

        //product attribute match check
        //print_r($data->getAttributes()->getData());
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if (!$parent_product) {
            $this->addValidationError($data['addon_id'], 'no product');

        }
        //echo $parent_product->getId();
       $store_ids = explode(',', $data['store_ids']);
        $store_ids = array_flip($store_ids);

                     $current_store_id = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId();
        if(!array_key_exists($current_store_id, $store_ids) && !array_key_exists(0, $store_ids)){

            $this->addValidationError($data['addon_id'], 'nostore-'.$current_store_id);
            return false;


        }
        // $parent_product = Mage::getModel('catalog/product')->load($parent_product->getId());
        if (!is_object($parent_product)) {
            //       echo "PRODUCT";
            $parent_product = $objectManager->create('Magento\Catalog\Model\Product')->load($parent_product);
        }




$attribute_matchups[1] = array('attribute_id' => '1320', 'value' => '1257');
$attribute_matchups[3] = array('attribute_id' => '1320', 'value' => '1257');
$attribute_matchups[5] = array('attribute_id' => '1320', 'value' => '1257');
$attribute_matchups[2] = array('attribute_id' => '1320', 'value' => '1792');
$attribute_matchups[4] = array('attribute_id' => '1320', 'value' => '1792');
$attribute_matchups[7] = array('attribute_id' => '1320', 'value' => '1261');
$attribute_matchups[8] = array('attribute_id' => '1320', 'value' => '1256');
$attribute_matchups[46] = array('attribute_id' => '1320', 'value' => '1257');
$attribute_matchups[49] = array('attribute_id' => '1320', 'value' => '1792');
$attribute_matchups[51] = array('attribute_id' => '1320', 'value' => '1257');
$attribute_matchups[52] = array('attribute_id' => '1320', 'value' => '1792');

  
        if ($data->getData()) { 

                  //  echo "ADDON ID = ".$data['cartassignments_id']."<br>";
                    
                                        
             if(array_key_exists($data['cartassignments_id'], $attribute_matchups)){
           $attribute_match_attribute_code = 'delivery';
              
            if($attribute_matchups[$data['cartassignments_id']]['value'] == $parent_product->getDelivery()){
                       
            }      
            else{
                return false;
            }
            
       //needs addon matc
       
       //delivery
       
       
       
       }
        /*
            if ($data->getAttributes()) {
                foreach ($data->getAttributes()->getData() as $attributes) {
                    $attribute_id = $attributes['attribute_id'];
                    $attribute_code = Mage::helper('elevate_assignments/attributes')->attributeIdToCode($attribute_id);

                    $parent_attribute_value = $parent_product->getData($attribute_code);

                    $value = $attributes['value'];
                    if ($parent_attribute_value == $value) {

                        //echo "MATCH";
                    } else {
                        $this->addValidationError($data['addon_id'], 'NO MATCH');

                        return;
                        //echo "NOMATCH";
                    }
                }
            }
            */
            //EADES

            //additions needed - validate if product is purchasable
            //if match quantity - validate there is enough stock left to match

            //check if correct date
            $assigned_skus = $data['assigned_skus'];

            if (is_object($parent_product)) {
                if ($assigned_skus) {
                  //  echo "PARENT";

                }

                if (!empty($assigned_skus)) {

                    $assigned_skus_exp = explode(',', $assigned_skus);

                    foreach ($assigned_skus_exp as $key => $val) {

                        if ($parent_product->getSku() == $val) {
                            return true;
                        }
                    }
                    $this->addValidationError($data['addon_id'], 'assigned sku er');

                    return false;
                }
            }
            if ($assigned_skus) {
                ///  echo $assigned_skus;
                // exit;
            }
            ///// VALIDATION START
            //If this is a product - check it is valid otherwise just skip
            //dependant addon validation

            $dependant_addon_blacklist = $data['dependant_addon_blacklist'];
            $dependant_addon_blacklist_exp = explode(',', $dependant_addon_blacklist);

            foreach ($dependant_addon_blacklist_exp as $key => $val) {


                if (is_numeric($val)) {
                    if (array_key_exists($quote_item_id_parent . "_" . $val, $this->assigned_addons)) {

                        $this->addValidationError($data['addon_id'], 'black');

                        return false;

                    }
                }

            }
            $dependant_addon_ids = $data['dependant_addon_ids'];
            $dependant_addon_ids_exp = explode(',', $dependant_addon_ids);
           
            foreach ($dependant_addon_ids_exp as $key => $val) {


                if (is_numeric($val)) {
                    if (array_key_exists($quote_item_id_parent . "_" . $val, $this->assigned_addons)) {


                    } else {
                        $this->addValidationError($data['addon_id'], 'depend err');

                        return false;
                    }
                }

            }

            //get the current categories for the product
            //get from index table as it contains the anchor categories too

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('catalog_category_product_index');
            $attribute_information = "Select category_id FROM " . $tableName . " WHERE product_id = $item_product_id"; //check for the  custom attribute condition". WHERE id = " . $manufacture . ";";
            // fetchOne it return the one value
            $results = $connection->fetchCol($attribute_information);

            $cats = array_flip($results);
              
            //Assigned categories for the addon
            $match_id = $data['assigned_categories'];
             
            if ($data['addon_type'] == 'multibox') {

                // $check = Mage::helper('core')->isModuleEnabled('Elevate_AssociatedConfigurable');

                $check = true;
                if (!$check) {
                    $this->addValidationError($data['addon_id'], 'NO multi');

                    return false;
                }

                //$parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($parent_product->getId());
                // if (isset($parentIds[0])) {
                //     $par = Mage::getModel('catalog/product')->load($parentIds[0]);
                // do stuff here
                // }

                // $_buyTogetherProducts = Mage::getModel('buytogether/association')->getAssociatedProducts($par)->getItems();
                /// if (count($_buyTogetherProducts) > 0) {

                //    return true;
                //}

            }

            if (!empty($match_id) && $match_id != 0) {
                  

                $match_id_exp = explode(',', $match_id);
                       
                $do_break = false;
                             
                //   if ($_GET['debug'] == 1) {
                //echo $data->getData('addon_id');
                //print_r($match_id_exp);
                //   }
                foreach ($match_id_exp as $key => $val) {
                    if (array_key_exists(trim($val), $cats)) {
                        
                        $do_break = true;

                    }
                }
                if (!$do_break) {

                    $this->addValidationError($data['addon_id'], 'NO MATCH');

                    //      echo "RET";
                    return false;
                }
                //   echo "YES";
                //  exit;
            }
        } else {
            return false;
        }

        //all valid
        return true;

    }

    public function mattressAction() {

        $quote_item_id_bed = $this->getRequest()->getParam('itemid');
        $product_id = $this->getRequest()->getParam('prodid');
        $type = $this->getRequest()->getParam('type');
        $addon_id = $this->getRequest()->getParam('addon_id');
        //$product_id = 1291249;

        $_product = Mage::getModel('catalog/product')->load($product_id);
        $bed_size = $_product->getChooseSize();

        // do stuff here

        $bed_id = $product_id;

        //  $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product_id);
        if (!$parentIds)
            $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product_id);
        if (isset($parentIds[0])) {
            $_product = Mage::getModel('catalog/product')->load($parentIds[0]);
            // do stuff here
        }

        $_buyTogetherProducts = Mage::getModel('buytogether/association')->getAssociatedProducts($_product)->getItems();
        if (count($_buyTogetherProducts) > 0) {
            $isBuyTogether = true;

        } else {
            //   $complete_hide = 'style="display:none;"';
        }

        $_buyTogetherProducts2 = Mage::getModel('associatedconfigurabletwo/association')->getAssociatedProducts($_product)->getItems();

        $isBuyTogether2 = false;
        if (count($_buyTogetherProducts2) > 0) {
            $isBuyTogether2 = true;
        }

        $mattress_one_title = "Choose a Mattress";

        if ($isBuyTogether2) {
            $mattress_one_title = "Choose a Top Mattress";
        }

        $_buyTogetherProducts3 = Mage::getModel('associatedconfigurablethree/association')->getAssociatedProducts($_product)->getItems();
        $isBuyTogether3 = false;
        if (count($_buyTogetherProducts3) > 0) {
            $isBuyTogether3 = true;

        }
        $html .= '
        <div class="outershell">

  <input type="radio" id="tab1" name="tab" checked>
  <label for="tab1"><i class="fa fa-code"></i> Recommended</label>
  <input type="radio" id="tab2" name="tab">
  <label for="tab2"><i class="fa fa-history"></i> By Category</label>

  <input type="radio" id="tab3" name="tab">
  <div class="line"></div>
  <div class="content-container">
    <div class="content c1-content" ">

      <p>Choose from our 4 recommended mattresses</p>

';

        $html .= '<div class="hide_item" id="full_add_wrap">';
        //echo $location;
        $html .= '
               <div id="additional_wrap">
                <div class="buytogethers row">

                    <div class="row ">
                    <ul class="products-grid">';

        //    $html .= '</div></div></li>';

        if (count($_buyTogetherProducts) > 0 && $type == 'top') {
            // Iterate the associated products and create the required colour swatches
            if (!empty($_buyTogetherProducts)) {
                foreach ($_buyTogetherProducts as $_associatedProduct) {


                    if ($_product->getId() == '1291271' || $_product->getId() == '1291272' || $_product->getId() == '1291276' || $_product->getId() == '1285260' || $_product->getId() == '1289205' || $_product->getId() == '1286259' || $_product->getId() == '1285386' || $_product->getId() == '1292649') {

                        //dont do anything for the quadruple beds - which are also in triple sleeper!!
                        $bed_size = 2637;
                    }

                    $html .= '' . $this->getHtmlRow($_associatedProduct, 'top', $quote_item_id_bed, $bed_size, $addon_id) . "";

                }
            }
            //$encode_mattress_size = json_encode($mattress_size_data)
        } // end has associated products

        // Add the current product to the associated list if it isn't already in there
        if (count($_buyTogetherProducts2) > 0 && $type == 'bottom') {


            // Iterate the associated products and create the required colour swatches
            if (!empty($_buyTogetherProducts2)) {
                foreach ($_buyTogetherProducts2 as $_associatedProduct) {
                    $html .= '' . $this->getHtmlRow($_associatedProduct, 'bottom', $quote_item_id_bed, $bed_size, $addon_id) . "";

                }
            }
            //$encode_mattress_size = json_encode($mattress_size_data)
        } // end has associated products

        if (count($_buyTogetherProducts3) > 0 && $type == 'trundle') {
            // Iterate the associated products and create the required colour swatches
            if (!empty($_buyTogetherProducts3)) {
                foreach ($_buyTogetherProducts3 as $_associatedProduct) {
                    $html .= '' . $this->getHtmlRow($_associatedProduct, 'trundle', $quote_item_id_bed, $bed_size, $addon_id) . "";

                }
            }
            //$encode_mattress_size = json_encode($mattress_size_data)
        } // end has associated products
        $html .= '</ul>

                 </div>
                  </div>




              </div>

       ';

        $html .= '</div>';

        $html .= '     </div>
    <div class="content c2-content">
      <h3>10% off any mattress when you buy a bed</h3>
      <p>Shop our range of mattresses online now</p>

      ';
        foreach ($catArray as $key => $val) {
            $html .= '<a href="' . $val['link'] . '">' . $val['name'] . '</a>';
        }

        $html .= '<div  class="bt_addtocart">
                        <div class="button btn-cart">Select</div>
                      </div>
    </div>

  </div>
</div>';
        $html .= '   <script>ELEVATE.Lightbox.attachEventHandlers();</script>';

        // $html = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($html));
        $response['list'] = $html;

        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

    }

    //multibox - how?
    public function getHtmlRow(
        $_associatedProduct,
        $location,
        $quote_item_id_bed,
        $bed_size,
        $addon_id
    ) {


        $product_name = $_associatedProduct->getName();
        $productBlock = $this->getLayout()->createBlock('catalog/product_price');
        $price_block = $productBlock->getPriceHtml($_associatedProduct);

        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(NULL, $_associatedProduct);
        $skip_product = true;

        foreach ($childProducts as $child) {
            //for this bed size lets default

            //    $_associatedProduct->
            $bed_size_arr['2639'] = "Small Single";
            $bed_size_arr['2637'] = "Single";
            $bed_size_arr['2641'] = "Double";
            $bed_size_arr['2638'] = "Small Double";

            if ($bed_size == $child->getChooseSize()) {
                $product_id = $child->getId();
                $productBlock = $this->getLayout()->createBlock('catalog/product_price');
                $price_block = $productBlock->getPriceHtml($child);
                $skip_product = false;

                $rrp_price = ($child->getFinalPrice());
                $now_price = ($child->getFinalPrice() * 0.9);
                $save_price = (($child->getFinalPrice() - ($child->getFinalPrice() * 0.9)));
                $categories = $child->getCategoryIds();
                foreach ($categories as $categoryId) {
                    $_category = Mage::getModel('catalog/category')->load($categoryId);
                    $categoryData = $_category->getData();
                    // if($categoryData['level']==2){
                    $catArray[$_category->getId()]['name'] = $_category->getName();
                    $catArray[$_category->getId()]['link'] = $_category->getUrl();

                    // }
                }
                //2639 = small single
                //2637 = single
                //2641 = double
                //2638 = small double
                $bed_size_arr['2639'] = "Small Single";
                $bed_size_arr['2637'] = "Single";
                $bed_size_arr['2641'] = "Double";
                $bed_size_arr['2638'] = "Small Double";
                // $html .=  $bed_size_arr[$bed_size];
            }
        }

        if ($skip_product) {

            return;
        }
        $ev_general_helper = Mage::helper('elevate_general');

        if ($last) {
            $lastitem = 'last';
        }
        $firmnessvalue = $_associatedProduct->getResource()->getAttribute('mattress_firmness')->getFrontend()->getValue($_associatedProduct);

        $firmness = '<div class="product-firmness">';
        if ($firmnessvalue != 'No') {
            $firmness_helper = Mage::helper('elevate_firmnessrating');
            $firmness_rating_data = $firmness_helper->getFirmnessGridHtml($_associatedProduct);
            $firmness .= $firmness_rating_data;
        }
        $firmness .= '</div>';

        $icons = '<div class="product-icons">';
        if ($attributeSetId == '11' || $attributeSetId == '14') {
            $product_icons = $ev_general_helper->showMattressCategoryIcons($_associatedProduct);
            $icons .= $product_icons;
        }

        $icons .= '</div>';

        $htmls .= '

<div class="row">
    <div class="col-md-3" style="padding:0px;">
        <img class="lazyload"  style=" max-width: 100%;" src="' . Mage::helper("catalog/image")->init($_associatedProduct, "small_image")->resize(320, 320) . '" data-src="' . Mage::helper("catalog/image")->init($_associatedProduct, "small_image")->resize(320, 320) . '" >

    </div>
    <div class="col-md-9">
     <div>' . $firmness . '</div>

    </div>
    </div>
    <div class="row">
     <div class="col-md-3">
     </div>

      <div class="col-md-9">

      <div>' . $_associatedProduct->getName() . '</div>

      <div>' . str_replace('Was From', 'Was', str_replace('Now From', 'Now', $price_block)) . '


      </div>

      <div>
      <div class="bt_addtocart bt_selectprodone" onclick="addItemToCart(' . $product_id . ', ' . $quote_item_id_bed . ', \'' . $location . '\', null, null, \'' . $addon_id . '\');" id="bt_addtocart_' . $product_id . '" data-parent="' . $product_id . '" data-prodid="0" data-mattid="0" data-price="0" data-rrp="0" data-name="' . $product_name . '" data-image="' . $main_image2_src . '">
                    <div class="button btn-cart"><span>Add to Basket</span></div>
                </div>
      </div>
      <div class="sr-clear">
    <div class="star-rating" style="width: 93.74px"><span style="width: 100%;"></span></div>
</div>
      </div>
</div>

';

        $html .= '<div class="buytogether_item2">
                <div class="bt_i_in">

                  <div id="bt_item_name2_' . $product_id . '" class="bt_item_name"> ' . $product_name . ' &nbsp;<span  style="display:none;" data-size="modal-lg"  class="evlightbox" data-json="1" data-body="/mattress/product/buytogether/id/' . $product_id . '" style="text-decoration: underline;color: #f0386c;cursor:pointer;">Show Information</span>
                  </div>
                  <div class="bt-add-item-inner" style="background:none;">
                    <div class="add_item_left">
                      <div class="add_item_inner">
                        <span><img class="lazyload" src="/media/loader.jpg" data-src="/media/10-off-mattress-main.jpg"/></span>
                        <div id="bt_img_2_' . $product_id . '" class="the-img">
                          <span style="cursor:pointer;" class="evlightbox" data-body-type="url"  data-body="/mattress/product/buytogether/id/' . $product_id . '"><img class="lazyload" src="/media/loader.jpg"
                          data-src="' . Mage::helper("catalog/image")->init($_associatedProduct, "small_image")->resize(320, 320) . '"/></span>
                        </div>
                      </div>
                    </div>
                    <div class="add_item_right">
                      <div class="bt-price-normal">
                        <span class="bt_price_save_text">Save:</span> <span class="bt_price_perc">' . $save_price . '</span>
                        <span class="bt_price_text">Was: </span>
                        <span class="bt_price">' . $rrp_price . '</span>
                      </div>
                      <div class="bt-price-final">
                        <span class="bt_price_final_text">Offer Price: </span>
                        <span class="bt_price_save">' . $now_price . '</span>
                      </div>
                      <div onclick="addItemToCart(' . $product_id . ', ' . $quote_item_id_bed . ', \'' . $location . '\', null, null, \'' . $addon_id . '\');" id="bt_addtocart_' . $product_id . '" class="bt_addtocart bt_selectprodtwo d-none d-md-block" id="bt_addtocart2_' . $product_id . '" data-parent="' . $product_id . '" data-prodid="0" data-mattid="0" data-price="0" data-rrp="0" data-name="' . $product_name . '" data-image="' . $main_image2_src . '">
                        <div class="button btn-cart">Select</div>
                      </div>
                    </div>
                  </div>
                        <div onclick="addItemToCart(' . $product_id . ', ' . $quote_item_id_bed . ', \'' . $location . '\', null, null, \'' . $addon_id . '\');" id="bt_addtocart_' . $product_id . '" class="bt_addtocart bt_selectprodtwo d-md-none" id="bt_addtocart2_' . $product_id . '" data-parent="' . $product_id . '" data-prodid="0" data-mattid="0" data-price="0" data-rrp="0" data-name="' . $product_name . '" data-image="' . $main_image2_src . '">
                        <div class="button btn-cart">Select</div>
                      </div>
                  <div style="clear:both;"></div>
                </div>
                <div style="clear:both"></div>
              </div>';

        return $html;
    }

    //multibox - how?
    public function getHtmlRow5(
        $_associatedProduct,
        $location,
        $quote_item_id_bed,
        $bed_size,
        $addon_id
    ) {


        $mat_product = $_associatedProduct;
        $image = Mage::helper('catalog/image')->init($_associatedProduct, 'small_image')->resize(160, 160);
        $product_name = $_associatedProduct->getName();

        $productBlock = $this->getLayout()->createBlock('catalog/product_price');
        $price_block = $productBlock->getPriceHtml($_associatedProduct);
        $rrp_price = '';//default price empty - ajax after
        $now_price = '';
        $link = '';//$_associatedProduct->getUrlPath();   //default price empty - ajax after

        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(NULL, $_associatedProduct);
        foreach ($childProducts as $child) {

            //for this bed size lets default
            if ($bed_size == $child->getChooseSize()) {
                $product_id = $child->getId();

                $productBlock = $this->getLayout()->createBlock('catalog/product_price');
                $price_block = $productBlock->getPriceHtml($child);

                $rrp_price = $child->getPrice();//default price empty - ajax after
                $now_price = round($child->getFinalPrice() * 0.9, 2);

            }

        }

        $ev_general_helper = Mage::helper('elevate_general');

        if ($last) {
            $lastitem = 'last';
        }

        $html .= '<li class="item ' . $lastitem . '">
<div class="prod-item-inner">

<a href="' . $mat_product->getUrlPath() . '" title="American White Finish Solid Pine Wooden Bunk Bed" class="product-image">
    <div class="p-img-icon save-icon">40% OFF</div>
    <img class=" lazyloaded" src="' . Mage::helper("catalog/image")->init($mat_product, "small_image")->resize(320, 320) . '" data-src="' . Mage::helper("catalog/image")->init($mat_product, "small_image")->resize(320, 320) . '" >
</a>
<a class="product-name" href="' . $mat_product->getUrlPath() . '" title="' . $mat_product->getName() . '">' . $mat_product->getName() . '
</a>
<div class="sr-clear">
    <div class="star-rating" style="width: 93.74px"><span style="width: 100%;"></span></div>
</div>
';

        $firmnessvalue = $mat_product->getResource()->getAttribute('mattress_firmness')->getFrontend()->getValue($mat_product);

        $html .= '<div class="product-firmness">';
        if ($firmnessvalue != 'No') {
            $firmness_helper = Mage::helper('elevate_firmnessrating');
            $firmness_rating_data = $firmness_helper->getFirmnessGridHtml($mat_product);
            $html .= $firmness_rating_data;
        }
        $html .= '</div>';

        $html .= '<div class="product-icons">';
        if ($attributeSetId == '11' || $attributeSetId == '14') {
            $product_icons = $ev_general_helper->showMattressCategoryIcons($mat_product);
            $html .= $product_icons;
        }
        $html .= '</div>';

        //$productBlock = $this->getLayout()->createBlock('catalog/product_price');
        //$price_block = $productBlock->getPriceHtml($mat_product);

        $html .= $price_block;
        $html .= '<div class="bt_addtocart bt_selectprodone" onclick="addItemToCart(' . $product_id . ', ' . $quote_item_id_bed . ', \'' . $location . '\', null, null, \'' . $addon_id . '\');" id="bt_addtocart_' . $product_id . '" data-parent="' . $product_id . '" data-prodid="0" data-mattid="0" data-price="0" data-rrp="0" data-name="' . $product_name . '" data-image="' . $main_image2_src . '">
                    <div class="button btn-cart"><span>Add to Basket</span></div>
                </div>';
        $html .= '
</div>
</li>

';

        return $html;
    }

    /**
     * Initialize product instance from request data
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    protected function _initProduct() {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch(NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Add product to shopping cart action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute() {

        //call the addons
        $this->getAddonBlock();

        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // $cart = $objectManager->get('\Magento\Checkout\Model\Cart');

        exit;
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $params = $this->getRequest()->getParams();
        $result = [];
        try {
            if (isset($params['qty'])) {
                $filter = new \Zend_Filter_LocalizedToNormalized(
                    ['locale' => $this->_objectManager->get('Magento\Framework\Locale\ResolverInterface')->getLocale()]
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                return $this->cartResponse();
            }

            $this->cart->addProduct($product, $params);
            if (!empty($related)) {
                $this->cart->addProductsByIds(explode(',', $related));
            }

            $this->cart->save();

            /**
             * @todo remove wishlist observer \Magento\Wishlist\Observer\AddToCart
             */
            $this->_eventManager->dispatch(
                'checkout_cart_add_product_complete', [
                                                        'product'  => $product,
                                                        'request'  => $this->getRequest(),
                                                        'response' => $this->getResponse()
                                                    ]
            );

            if (!$this->_checkoutSession->getNoCartRedirect(true)) {

                //TODO: potential speed optimisation here - no idea why magento team was assessing the stock before outputting the reponse
                //the item had already been added to the quote with no error!
                //needs investigation & update of another overcomplicated magento implementation.

                if (!$this->cart->getQuote()->getHasError()) {
                    $message = __(
                        'You added %1 to your shopping cart.', $product->getName()
                    );
                    $this->messageManager->addSuccessMessage($message);
                }

                //lets carry on doing this - why did magento decide to do this - did they hit some sort of issue or race condition??
                if ($product && !$product->getIsSalable()) {
                    $result['product'] = [
                        'statusText' => __('Out of stock')
                    ];
                } else {
                    $result['success'] = 1;
                }

                return $this->cartResponse($result);
            }
        } catch(\Magento\Framework\Exception\LocalizedException $e) {
            if ($this->_checkoutSession->getUseNotice(true)) {
                $this->messageManager->addNotice(
                    $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($e->getMessage())
                );
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addError(
                        $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($message)
                    );
                }
            }

            $url = $this->_checkoutSession->getRedirectUrl(true);

            if (!$url) {
                $cartUrl = $this->_objectManager->get('Magento\Checkout\Helper\Cart')->getCartUrl();
                $url = $this->_redirect->getRedirectUrl($cartUrl);
            }

            return $this->cartResponse();

        } catch(\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);

            return $this->cartResponse();
        }
    }

    //gets the product image config if possible - needs more settings adding
    public function addonBoxImage($_product) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        if ($_product->getTypeId() == 'simple') {
            // $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($_product->getId());

            $product = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($_product->getId());
            if (isset($product[0])) {
                //this is parent product id..

                $_product = $objectManager->create('Magento\Catalog\Model\Product')->load($product[0]);
            }

        }

        $helperImport = $objectManager->get('\Magento\Catalog\Helper\Image');

        $imageUrl = $helperImport->init($_product, 'product_page_image_small')->setImageFile($_product->getSmallImage()) // image,small_image,thumbnail
                                 ->resize(380)->getUrl();

        return $imageUrl;
    }

    public function addonBoxHtml(
        $quoteItemObjectMain,
        //parent object
        $quoteItemAssignmentsObject,
        //assignments to this product
        $data,
        //addon data
        $product,
        $linked_id
    ) {
        $productOutput = '';
        $type = $data->getData('addon_type'); //e.g product / multibox

        $quote_item_id_parent = $this->quoteItemObjectMain['id'];//$this->quoteItemObjectMain['id'];
        $product_id = $product->getId(); // nthis is the product id that gets added to the basket

        $product_price = $product->getData('price');
        $final_price = (float)$product->getData('special_price');
        if ($final_price < 0.01) {
            $final_price = $product_price;

        }
        $product_check = false;
        $title = $data->getData('title');
        $link_output = '';
        $link_type = $data->getData('link_type');
        $link_url = $data->getData('link_url');
        $link_static_block_id = $data->getData('link_static_block_id');
        $link_text = $data->getData('link_text');
        $link_style = $data->getData('link_style');
        $lightbox_title = $data->getData('lightbox_title');
        $lightbox_footer = $data->getData('lightbox_footer');
        $inc_title = '';
        $inc_footer = '';

        if (!empty($lightbox_footer)) {

            $inc_footer = 'data-footer="#lbfooter_' . $quote_item_id_parent . "_" . $data->getData('cartassignments_id') . '"';
            $link_output = '<div style="display:none;" id="lbfooter_' . $quote_item_id_parent . "_" . $data->getData('cartassignments_id') . '">' . $lightbox_footer . '</div>';
        }
        if (!empty($lightbox_title)) {

            $link_output .= '<div style="display:none;" id="lbtitle_' . $quote_item_id_parent . "_" . $data->getData('cartassignments_id') . '">' . $lightbox_title . '</div>';
            $inc_title = 'data-title="#lbtitle_' . $quote_item_id_parent . "_" . $data->getData('cartassignments_id') . '"';
        }

        $discount_rule_id = $data['discount_rule_id'];

        if ($link_type == 'target_blank') {

            $link_output = '<a target="_blank" class="itemrowlink" style="' . $link_style . '" href="' . $link_url . '">' . $link_text . '</a>';
        }
        if ($link_type == 'lightbox') {

            $link_output .= '

            <span class="evlightbox itemrowlink" data-size="modal-lg" ' . $inc_title . ' ' . $inc_footer . '  data-title-type="div" data-footer-type="div" data-body-type="url"  data-body="/assignments/addon/getstaticblock/id/' . $link_static_block_id . '" style="' . $link_style . '">' . $link_text . '</span>
            <script>ELEVATE.Lightbox.attachEventHandlers();</script>
            ';
        }

        $description = $data->getData('description');
        $product_sku = $product->getData('sku');
        $icon = $data['custom_icon'];
        $postcode_check = false;

        if ($data['enable_postcode'] == '1') {
            $postcode_check = true;

        }

        $spinner_code = false;

        $addon_image = $this->addonBoxImage($product);

        //print_r($data->getData());

        unset($quoteItemAssignmentsObject);
        $quoteItemAssignmentsObject = false;
        $quoteItemObject = false;

        $req_item_id = $quote_item_id_parent;
        $req_item_pre = $this->getRequest()->getParam('itemid');

        if (is_numeric($req_item_pre)) {

            $req_item_id = $req_item_pre;
        }

        if (array_key_exists($req_item_id . "_" . $data->getId(), $this->assigned_addons)) {

            $addon_loop = $this->assigned_addons[$req_item_id . "_" . $data->getId()];
            if (is_array($addon_loop)) {
                foreach ($addon_loop as $location => $subcollection) {

                    if (is_array($subcollection)) {
                        foreach ($subcollection as $linked_id => $addon) {
                            $quoteItemAssignmentsObject = $addon;
                            break;
                        }
                    }
                }

            }
        }
        if ($quoteItemAssignmentsObject) {
            //get the item quote object

            if (is_numeric($quoteItemAssignmentsObject['linked_quote_item_id'])) {
                $quoteItemObject = $this->_itemModel->load($quoteItemAssignmentsObject['linked_quote_item_id']);
            }
        }

        if (!$quoteItemObject) {


            $quoteItemAssignmentsObject = $this->_cartAssignmentsModal->load($quoteItemAssignmentsObject['assignment_id']);

            //$quoteItemAssignmentsObject = Mage::getModel('elevate_assignments/quote_item_assignments')->load();

            if ($quoteItemAssignmentsObject) {
                //  echo "DELETE ME";
                //  $quoteItemAssignmentsObject->delete();
            }
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            //    $product_check = Mage::getModel('catalog/product')->loadByAttribute('sku', $data['sku']);
            $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
            $product_check = $productRepository->get($data['sku']);
            if (!$product_check) {

                //check this delete
                //  $quoteItemAssignmentsObject->delete();
                //remove the addons if no product anymore!
                // continue;
            } else {

                if ($product_check->getTypeId() == 'simple') {


                    // $product_check_act = Mage::getModel('catalog/product')->load($product_check->getId());
                    // $cart = Mage::getModel('checkout/cart');
                    // $cart->init();
                    // $quote = $cart->getQuote();
                    //add the new product into the standard basket
                    //$quoteItem2 = $quote->addProduct($product_check_act, $quoteItemAssignmentsObject->qty);
                    //$item = $quote->getItemByProduct($mModel);
                    //$item->getProduct()->setIsSuperMode(true); // this is crucial
                    //   $quote->collectTotals()->save();

                }

            }
            /*sku
                        $mModel = Mage::getModel('catalog/product')->load($addon_product_id);


                   */
        }

        //override with icon
        if (!empty($icon)) {
            $addon_image = '/media' . $icon;
        }

        /*HTML BELOW*/


        // EV RJ - Moved here to as I need to know when something doesn't have qty wrapper

        $outputClass = 'no-increment-qty';
        $incrementQtyHtml = '';
        //print_r($quoteItemAssignmentsObject);
        $col_hid = '';
        $qty = $quoteItemAssignmentsObject['qty'];
        //echo "QUANT:".$qty;
        //exit;
        if ($qty < 2) {
            $col_hid = 'style="color:#fff !important;"';
        }

        $match_enabled = $data->getData('match_quantity');

        if ($qty > 0 && $match_enabled != '1') {

            if (array_key_exists($quote_item_id_parent . "_" . $data->getData('cartassignments_id'), $this->assigned_addons)) {
                /*Increment Code*/
                $outputClass = '';
                $incrementQtyHtml .= '<div class="addon_qty_wrapper">
        <span class="decrement_qty change_addon_qty_dec change_addon_qty" ' . $col_hid . 'data-type="decrement"><i class="fa fa-minus" style="vertical-align: middle;"></i></span>
        <input type="text" pattern="\d*" name="cart[\'assignment_' . $quoteItemAssignmentsObject['assignment_id'] . '\'][qty]"
        value="' . $qty . '" size="4" data-quote_item_assignment_id="' . $quoteItemAssignmentsObject['assignment_id'] . '" title="Qty" class="change_addon_qty_input input-text qty" maxlength="12"/>
        <span class="increment_qty change_addon_qty_incr change_addon_qty" data-type="increment"><i class="fa fa-plus" style="vertical-align: middle;"></i></span>
        </div>
        <script>ELEVATE.Assignments.attachEventHandlers();</script>';
                /*End Increment Code*/
            }
        }


        $already_added = false;
        $productOutput .= '<div class="addonwrap prod_' . $quote_item_id_parent . "_" . $data->getData('cart_assignments') . '">

       <div class="addonrow '.$outputClass.'">
        <div class="evlightbox addonlight"  data-body-type="ajax" data-json="true" data-size="modal-lg" data-title="Quick View" data-footer-type="div"
             data-body="/ev_cartassignments/show/popup/id/' . $product->getId() . '"
   ><i class="fa fa-info-circle"></i></div>';

        $productOutput .= $incrementQtyHtml;

        $productOutput .=         '<div class="addon-light-inner col-md-2 col-xs-3 col-3">';


        $productOutput .= '<span class="evlightbox "  data-size="modal-lg" data-title="Quick View" data-footer-type="div" data-body-type="url" data-body="/assignments/addon/cartassignments/id/' . $product->getId() . '" style="cursor:pointer !important; ' . $link_style . '">';
        $productOutput .= '     <img style="max-width:100%; cursor:pointer;" src="' . $addon_image . '" ></div></span>';

        if (is_numeric($qty) && $match_enabled == '1') {
            $qty_text = 'x ' . $qty;
        }

        $line_discounts_enabled = '1';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_pricingHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');

        $productOutput .= '    <div class="col-md-10 col-xs-9 col-9">';
        $extra_class = "";
        if ($line_discounts_enabled == '1') {
            if ($data['discount_percentage'] > 0) {
                $extra_class = " intprice_extra2";

                $productOutput .= ' <div style="" class="fullprice"><span class="amount"></span>' . $_pricingHelper->currency($final_price,true,false) . '</div>';
            }
        }

        $quoteItemChildbject = false;
        $qty_text = '';
        $link_output = '';
        $generate = false;
        $remove_item_id = '';
        //  $productOutput .= "$final_price | ".$quoteItemObject->getDiscountAmount()."<br>";
        if (is_array($this->assigned_addons)) {

            if (array_key_exists($quote_item_id_parent . "_" . $data->getData('cartassignments_id'), $this->assigned_addons)) {


                foreach ($this->assigned_addons[$quote_item_id_parent . "_" . $data->getData('cartassignments_id')] as $location => $subcollection) {

                    foreach ($subcollection as $linked_id => $addon) {
                        //  echo "FOUR";
                        // print_r($addon);
                        $my_linked_item = $linked_id;
                        $remove_item_id = $addon['assignment_id'];
                        break;
                    }
                }
                if (is_numeric($my_linked_item)) {
                    //  $productOutput .= "$final_price | ".$quoteItemObject->getDiscountAmount()."<br>";

                    $quoteItemChildbject = $this->_cart->getQuote()->getItemById($my_linked_item);
                    if ($quoteItemChildbject) {
                        $final_price = $quoteItemChildbject->getPrice();
                    }
                } else {
                    //  $mModel = Mage::getModel('catalog/product')->load($addon_product_id);
                    $generate = true;
                }
            } else {

                $generate = true;
            }
        }

        //if left over in the table - but not in the cart - lets remove the association
        if (!$quoteItemChildbject) {

            $assigned_item = $this->_cartAssignmentsModal->load($quoteItemAssignmentsObject['assignment_id']);
            //print_r($assigned_item->getData());
            //  $assigned_item->delete($quoteItemAssignmentsObject['assignment_id']);
            //echo "DELETE".$quoteItemAssignmentsObject['assignment_id'];

        }

        if ($generate) {
            if ($product_check) {
                if ($product_check->getTypeId() == 'simple') {

                    $final_price = $product_check->getFinalPrice();
                    //add the new product into the standard basket

                }
            }
        }  
        
        
             $final_price_ex = $final_price;
 $final_price_inc = $final_price;                                        
        if ($quoteItemObject) {
            $final_price = $quoteItemObject->getPrice(); 
            
 
  $final_price_ex = $quoteItemObject->getPrice();
                $final_price_inc = $quoteItemObject->getData('price_incl_tax');
              
                                                              
        }
        else{
              $final_price_ex = $product_check->getFinalPrice();
                                          
             $final_price_inc = $product_check->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();  
             
                                                                                             
        }                        
        
 
                         
        if ($line_discounts_enabled == '1' && $quoteItemObject) {


            //$final_price_end = $final_price - ((($final_price / 100) * $data['discount_percentage']));
            //$final_price = $final_price_end;
            

            if ($quoteItemObject->getDiscountAmount() > 0) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $settingsHelper = $objectManager->get('Elevate\CartAssignments\Helper\Settings');
                $price_type = $settingsHelper->getPriceType();

                if ($price_type == 'ex_vat') {

                    $rot_total = $quoteItemObject->getRowTotal() / $quoteItemObject->getQty();
                     
                } else {


                    $rot_total = $quoteItemObject->getRowTotalInclTax() / $quoteItemObject->getQty();
              
                    // echo $rot_total;
                }

                $final_price = $rot_total - ($quoteItemObject->getDiscountAmount() / $quoteItemObject->getQty());
                
                       $rot_total_ex = $quoteItemObject->getRowTotal() / $quoteItemObject->getQty();
                    $rot_total_inc = $quoteItemObject->getRowTotal() / $quoteItemObject->getQty();
                    
                    
                   $final_price_ex =  $rot_total_ex - ($quoteItemObject->getDiscountAmount() / $quoteItemObject->getQty());
                  $final_price_inc = $rot_total_inc - ($quoteItemObject->getDiscountAmount() / $quoteItemObject->getQty());
            } else {

                $final_price = $quoteItemObject->getRowTotalInclTax() / $quoteItemObject->getQty();
                  $final_price_ex =  $quoteItemObject->getRowTotal() / $quoteItemObject->getQty();
                  $final_price_inc = $quoteItemObject->getRowTotalInclTax() / $quoteItemObject->getQty();
            }

        } else {


            $final_price = $final_price - (($final_price / 100) * $data->getData('discount_percentage'));
                $final_price_ex = $final_price_ex - (($final_price_ex / 100) * $data->getData('discount_percentage'));
                    $final_price_inc = $final_price_inc - (($final_price_inc / 100) * $data->getData('discount_percentage'));
        }
                  
 
                   $output_price = '';
         $_pricingHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
                    $output_price .= '<div class="price-excluding-tax">'.$_pricingHelper->currency($final_price_ex,true,false).'</div>';
        $output_price .= '<div class="price-including-tax">'.$_pricingHelper->currency($final_price_inc,true,false).'</div>';
        
        
        $productOutput .= '        <div style="font-weight: bold;margin-bottom: 10px;font-size: 16px;" class="mbpromotop ' . $extra_class . '">' . $output_price . ' ' . $qty_text . '
                         </div>';
        $productOutput .= '<div class="replacewrap">';
        
        
        $productOutput .= '<span class="evlightbox " data-body-type="ajax" data-size="modal-lg" data-json="true" data-title="Quick View" data-footer-type="div" 
             data-body="/ev_cartassignments/show/popup/id/' . $product->getId() . '" style="cursor:pointer; ' . $link_style . '">
                             <div style="font-weight: bold;margin-bottom: 10px;font-size: 14px;">' . $title . '</div></span>

            <script>ELEVATE.Lightbox.attachEventHandlers();</script>
            ';

        $productOutput .= '<div style="font-size: 13px" class="addon_description">' . $description . '</div> ';

        $productOutput .= '<div>' . $link_output . '</div>';

        if ($data['enable_countdown_timer'] == '1') {

            $background_color = $data['countdown_background_colour'];
            $font_colour_overlay = $data['countdown_font_colour_overlay'];
            $font_colour = $data['countdown_font_colour'];

            $productOutput .= '
              <div class="elements col-md-8" style="text-align: center;padding: 0px;margin-top: 10px;">
              <ul class="' . $quote_item_id_parent . "_" . $data['id'] . ' countdown_timer" style="float: right;margin-right: 10px;">
                <li><div class="dblock days" style="background-color: ' . $background_color . ';color: ' . $font_colour_overlay . ' !important;">0</div>
                  <div class="dtext" style="color: ' . $font_colour . ' !important;">DAYS</div>
               </li>
                <li><div class="dblock hours" style="background-color: ' . $background_color . ';color: ' . $font_colour_overlay . ' !important;">0</div>
                   <div class="dtext" style="color: ' . $font_colour . ' !important;">HOURS</div>
                 </li>
                <li><div class="dblock minutes" style="background-color: ' . $background_color . ';color: ' . $font_colour_overlay . ' !important;">0</div>
                    <div class="dtext" style="color: ' . $font_colour . ' !important;">MINUTES</div>
                </li>
                <li><div class="dblock seconds" style="background-color: ' . $background_color . ';color: ' . $font_colour_overlay . ' !important;">0</div>

                <div class="dtext" style="color: ' . $font_colour . ' !important;">SECONDS</div>
                 </li>
              </ul>
            <script>
            var deadline = new Date("' . $data->getData('countdown_time') . '");
            initializeClock(\'' . $quote_item_id_parent . "_" . $data->getData('cartassignments_id') . '\', deadline);
            </script>
            </div>';
        }

        $productOutput .= '</div>';

        $checked = '';
        $location = 'add';
        $addon_id = $data->getData('cartassignments_id');
        if (is_array($this->assigned_addons)) {

            /*  echo "<pre>";

              echo "CART ASSIGNMENT ID::".$data->getData('cartassignments_id')."<br>";
              echo "PAR::".$quote_item_id_parent."<br>";

             print_r($this->assigned_addons);
            echo "</pre>";
   */

            //   echo "<pre>";
            //   print_r($this->assigned_addons);
            // echo $quote_item_id_parent . "_" . $data->getData('cartassignments_id')."  <<<br>";
            if (array_key_exists($quote_item_id_parent . "_" . $data->getData('cartassignments_id'), $this->assigned_addons)) {

                //echo "EADES";
                //loop the rows in the basket
                //

                $already_added = true;
                $checked = 'checked="checked"';
                $location = 'remove';

                $clickEvent = 'removeAddon(\'' . $remove_item_id . '\', \'rowpriceprod_' . $quote_item_id_parent.$addon_id.'\');';
            } else {
                $addon_id = $data->getData('cartassignments_id');
                $clickEvent = 'checkPostcodeArea(\'#mattcontainer_' . $quote_item_id_parent . ' .prod_' . $quote_item_id_parent . "_" . $data['id'] . ' .col-md-10 .replacewrap\', \'' . $product_id . '\', \'' . $quote_item_id_parent . '\', \'' . $addon_id . '\');';
                if (!$postcode_check) {
                    $clickEvent = 'addItemToCart(' . $product_id . ', ' . $quote_item_id_parent . ', \'' . $location . '\', \'' . $remove_item_id . '\', false, \'' . $addon_id . '\', this.id, \'check\', \'rowpriceprod_' . $quote_item_id_parent.$addon_id.'\');';
                }
            }
        } else {

            $addon_id = $data->getData('cartassignments_id');
            $clickEvent = 'checkPostcodeArea(\'#mattcontainer_' . $quote_item_id_parent . ' .prod_' . $quote_item_id_parent . "_" . $data['id'] . ' .col-md-10 .replacewrap\', \'' . $product_id . '\', \'' . $quote_item_id_parent . '\', \'' . $addon_id . '\');';
            if (!$postcode_check) {
                $clickEvent = 'addItemToCart(' . $product_id . ', ' . $quote_item_id_parent . ', \'' . $location . '\', \'' . $remove_item_id . '\', false, \'' . $addon_id . '\', this.id, \'check\', \'rowpriceprod_' . $quote_item_id_parent.$addon_id.'\');';
            }
        }

        $output['mobile_price']['price'] = 0;
        $output['mobile_price']['id'] = 'rowpriceprod_' . $quote_item_id_parent;

        $productOutput .= '</div><input data-ae="1" id="postcode_' . $quote_item_id_parent . "_" . $data->getData('cartassignments_id') . '" onclick="' . $clickEvent . '" class="inposittion" type="checkbox" ' . $checked . '> ';
        // $output['mobile_price']['price'] = ( $quoteItemObjectMain->getRowTotal());
        $output['mobile_price']['id'] = 'rowpriceprod_' . $quote_item_id_parent;
        $output['mobile_price']['price'] = 0;
        if ($already_added) {


            if ($quoteItemChildbject) {
                $output['mobile_price']['price'] = ($quoteItemChildbject->getRowTotal());
                $my_price = $quoteItemChildbject->getPrice();
                // $output_price = $_item->getPrice() - ($_item->getDiscountAmount() / $_item->getQty());
            } else if ($line_discounts_enabled == '1' && $quoteItemChildbject) {
                $my_price = $quoteItemChildbject->getPrice();
                //      $my_price =  $my_price - (($my_price / 100) * $data->getData('discount_percentage'));

            } else {

            }

            $output['mobile_price']['id'] = 'rowpriceprod_' . $quote_item_id_parent;

            //    $productOutput .= print_r($this->has_qty, true);

        }

        $productOutput .= ' <div style="clear:both;"></div>';

        if ($line_discounts_enabled == '1' && $quoteItemObject) {


            $appliedRuleIds = explode(',', $quoteItemObject->getAppliedRuleIds());

            $new_quote = $this->_cart->getQuote();
            $new_quote->collectTotals();
            $discountBreakdown = [];   
            $itemDiscountBreakdown = false;            
            if($quoteItemChildbject){              
        $itemDiscountBreakdown = $quoteItemChildbject->getExtensionAttributes()->getDiscounts();     
        
        }                
        if ($itemDiscountBreakdown) {
            foreach ($itemDiscountBreakdown as $value) {
                /* @var \Magento\SalesRule\Api\Data\DiscountDataInterface $discount */
                $discount = $value->getDiscountData();
                $ruleLabel = $value->getRuleLabel();
                $ruleID = $value->getRuleID();
                $discountBreakdown[$ruleID] = $discount->getAmount();
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
                       $reduct = (($rule_price / $quoteItemObject->getQty()) * $qty);
        //    $reduct = ($rule_price / $original_item_qty) * $qty;

            $this->_coreSession->setCABasketRuleIds($current_basket_rule_ids);
            $this->_coreSession->setCABasketAmount($current_basket_amount + $reduct);

            $offer .= "<div class=\"
                                   couponbox
    \" style=\"margin-left:0px;margin-right:0px;\">
  <div>$rule_description - SAVE " . $_pricingHelper->currency(($reduct)) . "</div>
  </div>";

        }
            if ($discount_count > 0) {
                $offer_text = 'offer is';
                if ($discount_count > 1) {
                    $offer_text = 'offers are';
                }
                $productOutput .= '  <div style="clear:both;"></div>   <div style="
      padding-top: 20px !important;
    border-top: 1px solid #ccc;
    margin-top: 10px;
    padding-bottom: 0px;
">';

                $productOutput .= '<div>
  <div class="evca_offer_text">The following ' . $offer_text . ' applied to this product</div>
   ' . $offer . '
  </div>';
                $productOutput .= '</div>';
            }

        }

        $productOutput .= '</div>';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $settingsHelper = $objectManager->get('Elevate\CartAssignments\Helper\Settings');
        $price_type = $settingsHelper->getPriceType();

        if ($already_added) {
          
            
            
           $final_price_ex = $quoteItemObject->getRowTotal();   
           $final_price_inc = $quoteItemObject->getRowTotalInclTax();     
                                                                         
            if ($quoteItemObject->getDiscountAmount() > 0) {
                $output_price_add = $output_price_add - ($quoteItemObject->getDiscountAmount());
                
                  $final_price_ex = $final_price_ex - ($quoteItemObject->getDiscountAmount());
                    $final_price_inc = $final_price_inc - ($quoteItemObject->getDiscountAmount());                                                                  
            }
                    
            
                   $final_price_ex = ($final_price_ex / $quoteItemObject->getQty()) * $qty;
                         $final_price_inc = ($final_price_inc / $quoteItemObject->getQty()) * $qty;
 
                   $output_price = '';
         $_pricingHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
                    $output_price .= '<div class="price-excluding-tax">'.$_pricingHelper->currency($final_price_ex,true,false).'</div>';
        $output_price .= '<div class="price-including-tax">'.$_pricingHelper->currency($final_price_inc,true,false).'</div>';
            
                    
         

            $productOutput .= '<div class="addon_price_float floattop" data-qty="' . $qty . '" id="rowpriceprod_' . $quote_item_id_parent.$addon_id . '">
' . ($output_price) . '</div>';
        }
        else{
            $productOutput .= '<div class="addon_price_float floattop" data-qty="' . $qty . '" id="rowpriceprod_' . $quote_item_id_parent.$addon_id . '">
</div>';

        }
        $productOutput .= '</div>';
        $output['list'] = $productOutput;

        return $output;
    }

    /**
     * Cart response
     *
     * @param array $result
     *
     * @return $this
     */
    protected function cartResponse($result) {
        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }
}
