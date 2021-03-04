<?php

namespace Elevate\CartAssignments\Controller\Addon;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class Getdonation extends \Magento\Framework\App\Action\Action
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

  $responseData = [];
  $html = '';
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
            
        $subTotal = $cart->getQuote()->getSubtotal();
       
  
     $result = $cart->getQuote()->getAllVisibleItems();
$itemsIds = array();
foreach ($result as $cartItem) {
    if($cartItem->getProduct()->getSku() == 'BORN-FREE-DONATION'){
      $responseData = [
            'errors' => false,
            'html' => $html
        ];
        echo json_encode($responseData);
        exit;
    }
}


   $subTotal = $subTotal+$cart->getQuote()->getShippingAddress()->getBaseTaxAmount();
    $percentage = ($subTotal / 100);
        
        $round    = $subTotal - floor($subTotal);
        $round    = 1 - $round;

        $round = $percentage;     
               
        $round_final = number_format((float)$round, 2, '.', '');
       
      
       
  $html = '
  <div id="donation_wrapper" style="">
 <div class="container">
  <div class="row">
<div class="col-md-4 col-xs-2">
  <img src="/media/bornfree_2.png">
</div>
<div class="col-md-8 col-xs-10 donate-wording">
  <p>Donate now to Born Free - Your donations will help the welfare of animals living in the wild and in captivity.</p>

</div>
 </div>  
   <div class="row">
<div class="col-md-12">  
    <select id="ev_donation_value" name="ev_donation_value" style="width:100%;margin-top:10px;margin-bottom:10px;">
       <option data-id="round" value="'.$round_final.'">1% of your order (&pound;'.$round_final.')</option>
       <option data-id="fixed" value="5">&pound;5 donation</option>
       <option data-id="fixed" value="10">&pound;10 donation</option>
       <option data-id="fixed" value="15">&pound;15 donation</option>
   </select>

<button  id="donation_button" class="btn btn-minicart-checkout action secondary" onclick="addDonationToCart();">Add Donation</button>
</div> 
</div>   </div></div>
  ';

        $responseData = [
            'errors' => false,
            'html' => $html
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