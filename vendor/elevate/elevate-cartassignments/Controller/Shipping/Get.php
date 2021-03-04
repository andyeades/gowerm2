<?php

namespace Elevate\CartAssignments\Controller\Shipping;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class Get extends \Magento\Framework\App\Action\Action
{


    /**
     * @var Session
     */
    private $session;
    /**
     * @var StockItemRepository
     */

    /**
     * @var Data
     */
    protected $_jsonHelper;

    /**
     * @var LoggerInterface
     */
    protected $_logger;




    /**
     * Index constructor.
     * @param Context $context
     * @param Data $jsonHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $session
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->_logger = $logger;
        $this->session = $session;


    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {


$html = '';

$html = '';
$om = \Magento\Framework\App\ObjectManager::getInstance();
$customerSession = $om->get('Magento\Customer\Model\Session');
//$customerData = $customerSession->getCustomer()->getData(); //get all data of customerData


//doing lookup - not logged in

$helper = $om->get('Elevate\Promotions\Helper\Data');
$not_logged_in_email = $helper->getConfig('mconnector/custom_settings/not_logged_in_email');

      $currentCustomer = false;
        if(!empty($not_logged_in_email)){
        $customerFactory = $om->get('Magento\Customer\Model\CustomerFactory');
            $currentCustomer = $customerFactory->create()->loadByEmail($not_logged_in_email);
           
        }

        if(!$currentCustomer || !$currentCustomer->getId()){

            $currentCustomer = $customerSession->getCustomer();

            $is_contact = (bool) $currentCustomer->getIsContact();
            if($is_contact){
                $parent_customer_id = $currentCustomer->getNavContactCustomerId();
                if(!empty($parent_customer_id)){
                    $parent_customer =  $currentCustomer
                        ->getCollection()
                        ->addAttributeToSelect(['*'])
                        ->addAttributeToFilter('navision_customer_id', $parent_customer_id)
                        ->getFirstItem();
                    if($parent_customer && $parent_customer->getId()){
                        $currentCustomer = $parent_customer;
                    }
                }

            }
         
          
        }

   
        $NAV_shipping_code = $currentCustomer->getData('nav_shipping_method_code');//get id of customer
       
   $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');

$connection = $resource->getConnection();


//Select All Data Present in Table

    $sql = "SELECT delivery_cms_block FROM webshopapps_matrixrate WHERE customer_shipping_group = '$NAV_shipping_code'";

   
           $result = $connection->fetchOne($sql); /****** Return Array with values******/
          
            
$staticBlock = $objectManager->get('Magento\Cms\Block\BlockFactory')->create();

// Change the your-block-id for the correct block ID
$staticBlock->setBlockId($result);

$html = $staticBlock->toHtml();        
               
         if(empty($html)){
 $NAV_shipping_code = 'FLAT500';

   

                $html =
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods</td>
			<td class=\'text-right\'>&pound;5.00*</td>
		</tr>
	</tbody>
</table>';

     
if($NAV_shipping_code != 'FOC'){
$html .= '<p><em>*Excludes assesments & training and downloadable software (&pound;0.00)</em></p>';
}
            }

           
       $reponse['html'] = $html;
      
             echo json_encode($reponse);
    
        exit;
  

//if we have requirement - we can also get the billing details
      //  $billingAddress = $cart->getQuote()->getBillingAddress();
      //  echo '<pre>'; print_r($billingAddress->getData()); echo '</pre>';

      //  $shippingAddress = $cart->getQuote()->getShippingAddress();
      //  echo '<pre>'; print_r($shippingAddress->getData()); echo '</pre>';

        $responseData = [
            'errors' => false,
            'has_basket' => $hasBasket,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'sub_total' => $subTotal,
            'grand_total' => $grandTotal,
            'item_data' => $itemData
        ];
        echo json_encode($responseData);


    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->_jsonHelper->jsonEncode($response)
        );
    }


}